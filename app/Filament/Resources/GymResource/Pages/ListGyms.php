<?php

namespace App\Filament\Resources\GymResource\Pages;

use App\Filament\Resources\GymResource;
use App\Models\Gym;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListGyms extends ListRecords
{
    protected static string $resource = GymResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $pendingCount = Gym::query()->where('subscription_status', 'pending_approval')->count();

        return [
            'all' => Tab::make('All Gyms'),
            'pending' => Tab::make('Pending Approval')
                ->badge($pendingCount > 0 ? (string) $pendingCount : null)
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('subscription_status', 'pending_approval')),
            'active' => Tab::make('Active Gyms')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make('Inactive / Suspended')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)->where('subscription_status', '!=', 'pending_approval')),
        ];
    }
}
