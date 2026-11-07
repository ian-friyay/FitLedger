<?php

namespace App\Filament\Resources\CheckInResource\Pages;

use App\Enums\CheckInStatus;
use App\Filament\Resources\CheckInResource;
use App\Services\ReportExportService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListCheckIns extends ListRecords
{
    protected static string $resource = CheckInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportAttendance')
                ->label('Export Attendance CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->modalHeading('Export Gym Attendance Report')
                ->modalDescription('Download an Excel-friendly CSV containing check-in logs, dates, member details, and peak workout hours.')
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
                        ->label('Filter by Status')
                        ->options([
                            'all' => 'All Scans (Approved, Due & Denied)',
                            'active' => 'Approved Active Only',
                            'due' => 'Payment Due / Expiring Only',
                            'expired' => 'Expired Only',
                        ])
                        ->default('all'),
                ])
                ->action(function (array $data): StreamedResponse {
                    $gym = Filament::getTenant();
                    return app(ReportExportService::class)->exportAttendance($gym, $data);
                }),
        ];
    }
}
