<?php

namespace App\Filament\Resources\PlatformUserResource\Pages;

use App\Filament\Resources\PlatformUserResource;
use App\Models\Gym;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListPlatformUsers extends ListRecords
{
    protected static string $resource = PlatformUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users'),
            'owners' => Tab::make('Gym Owners')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_platform_admin', false)
                    ->whereIn('email', Gym::pluck('owner_email'))),
            'staff' => Tab::make('Staff')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_platform_admin', false)
                    ->whereNotNull('gym_id')
                    ->whereNotIn('email', Gym::pluck('owner_email'))),
            'admins' => Tab::make('Platform Admins')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_platform_admin', true)),
        ];
    }
}
