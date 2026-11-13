<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassBookingResource\Pages;
use App\Models\ClassBooking;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClassBookingResource extends Resource
{
    protected static ?string $model = ClassBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Classes';

    protected static ?int $navigationSort = 2;

    protected static ?string $tenantOwnershipRelationshipName = 'gym';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('gym_class_id')
                    ->relationship(
                        'gymClass',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('gym_id', Filament::getTenant()?->getKey())
                    )
                    ->label('Class')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('member_id')
                    ->relationship(
                        'member',
                        'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('gym_id', Filament::getTenant()?->getKey())
                    )
                    ->label('Member')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'booked' => 'Booked',
                        'cancelled' => 'Cancelled',
                        'attended' => 'Attended',
                    ])
                    ->default('booked')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gymClass.name')
                    ->label('Class')
                    ->sortable(),
                Tables\Columns\TextColumn::make('member.name') // TAMA NA ITO
                    ->label('Member')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'booked' => 'warning',
                        'cancelled' => 'danger',
                        'attended' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassBookings::route('/'),
            'create' => Pages\CreateClassBooking::route('/create'),
            'edit' => Pages\EditClassBooking::route('/{record}/edit'),
        ];
    }
}