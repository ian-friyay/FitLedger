<?php

namespace App\Services;

use App\Models\CheckIn;
use App\Models\Gym;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    /**
     * Export gym attendance check-ins to an Excel-friendly CSV.
     */
    public function exportAttendance(Gym $gym, array $filters = []): StreamedResponse
    {
        $query = CheckIn::query()
            ->where('gym_id', $gym->id)
            ->with(['member', 'subscription.plan'])
            ->latest('scanned_at');

        $this->applyDateFilter($query, 'scanned_at', $filters['range'] ?? 'this_month', $filters['from'] ?? null, $filters['to'] ?? null);

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        $filename = "attendance-report-{$gym->slug}-" . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Microsoft Excel

            fputcsv($handle, [
                'Timestamp',
                'Date',
                'Day of Week',
                'Peak Hour Window',
                'Member Name',
                'Phone',
                'Status',
                'Entry Allowed',
                'Current Plan',
                'Notes',
            ]);

            $query->chunk(250, function ($records) use ($handle) {
                foreach ($records as $item) {
                    $scannedAt = $item->scanned_at ?? $item->created_at;

                    fputcsv($handle, [
                        $scannedAt ? $scannedAt->format('Y-m-d H:i:s') : 'N/A',
                        $scannedAt ? $scannedAt->format('Y-m-d') : 'N/A',
                        $scannedAt ? $scannedAt->format('l') : 'N/A',
                        $scannedAt ? $scannedAt->format('g:00 A') : 'N/A',
                        $item->member?->name ?? 'Unknown Member',
                        $item->member?->phone ?? '',
                        $item->status->getLabel(),
                        $item->status->allowsEntry() ? 'YES' : 'NO',
                        $item->subscription?->plan?->name ?? 'None',
                        $item->message ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Export gym revenue and payments to an Excel-friendly CSV.
     */
    public function exportRevenue(Gym $gym, array $filters = []): StreamedResponse
    {
        $query = Payment::query()
            ->where('gym_id', $gym->id)
            ->with(['member', 'subscription.plan'])
            ->latest('created_at');

        $this->applyDateFilter($query, 'created_at', $filters['range'] ?? 'this_month', $filters['from'] ?? null, $filters['to'] ?? null);

        if (! empty($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status', $filters['status']);
        }

        $filename = "revenue-report-{$gym->slug}-" . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Microsoft Excel

            fputcsv($handle, [
                'Date',
                'Reference Number',
                'Member Name',
                'Phone',
                'Membership Plan',
                'Amount (PHP)',
                'Currency',
                'Payment Method',
                'Payment Status',
                'Paid At',
            ]);

            $query->chunk(250, function ($records) use ($handle) {
                foreach ($records as $item) {
                    fputcsv($handle, [
                        $item->created_at->format('Y-m-d H:i:s'),
                        $item->reference_number ?? 'N/A',
                        $item->member?->name ?? 'N/A',
                        $item->member?->phone ?? '',
                        $item->subscription?->plan?->name ?? 'General',
                        number_format((float) $item->amount, 2, '.', ''),
                        $item->currency ?? 'PHP',
                        strtoupper($item->payment_method ?? 'CASH'),
                        ucfirst($item->status->value ?? 'Pending'),
                        $item->paid_at ? $item->paid_at->format('Y-m-d H:i:s') : 'Pending',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Apply date range filters to Eloquent queries.
     */
    private function applyDateFilter(Builder $query, string $column, string $range, ?string $from = null, ?string $to = null): void
    {
        match ($range) {
            'today' => $query->whereDate($column, today()),
            'this_week' => $query->whereBetween($column, [now()->startOfWeek(), now()->endOfWeek()]),
            'this_month' => $query->whereBetween($column, [now()->startOfMonth(), now()->endOfMonth()]),
            'last_month' => $query->whereBetween($column, [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()]),
            'this_year' => $query->whereBetween($column, [now()->startOfYear(), now()->endOfYear()]),
            'custom' => $from && $to ? $query->whereBetween($column, [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]) : null,
            default => null, // 'all_time'
        };
    }
}
