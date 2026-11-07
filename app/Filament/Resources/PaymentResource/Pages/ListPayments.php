<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Services\ReportExportService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportRevenue')
                ->label('Export Revenue CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->modalHeading('Export Gym Revenue & Transactions')
                ->modalDescription('Download an Excel-friendly CSV with payment transactions, references, GCash details, and member info.')
                ->form([
                    Select::make('range')
                        ->label('Time Period')
                        ->options([
                            'this_month' => 'This Month (' . now()->format('F Y') . ')',
                            'last_month' => 'Last Month (' . now()->subMonth()->format('F Y') . ')',
                            'today' => 'Today (' . today()->format('M j, Y') . ')',
                            'this_week' => 'This Week',
                            'this_year' => 'This Year (' . now()->format('Y') . ')',
                            'all_time' => 'All Time',
                            'custom' => 'Custom Date Range',
                        ])
                        ->default('this_month')
                        ->reactive()
                        ->required(),

                    DatePicker::make('from')
                        ->label('From Date')
                        ->visible(fn ($get) => $get('range') === 'custom')
                        ->required(fn ($get) => $get('range') === 'custom'),

                    DatePicker::make('to')
                        ->label('To Date')
                        ->visible(fn ($get) => $get('range') === 'custom')
                        ->required(fn ($get) => $get('range') === 'custom'),

                    Select::make('status')
                        ->label('Transaction Status')
                        ->options([
                            'all' => 'All Transactions',
                            'paid' => 'Completed / Paid Only',
                            'pending' => 'Pending Dues Only',
                            'failed' => 'Failed Only',
                        ])
                        ->default('paid'),
                ])
                ->action(function (array $data): StreamedResponse {
                    $gym = Filament::getTenant();
                    return app(ReportExportService::class)->exportRevenue($gym, $data);
                }),
        ];
    }
}
