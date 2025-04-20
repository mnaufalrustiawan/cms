<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavLinkResource\Pages;
use App\Filament\Resources\NavLinkResource\RelationManagers;
use App\Models\NavLink;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NavLinkResource extends Resource
{
    protected static ?string $model = NavLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama Tautan'),
                Forms\Components\TextInput::make('url')
                    ->required()
                    ->label('URL Tautan'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Tautan Aktif')
                    ->default(true),
                Forms\Components\TextInput::make('order')  // Menambahkan field order
                    ->numeric()
                    ->default(0)
                    ->label('Urutan')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Tautan'),
                Tables\Columns\TextColumn::make('url')->label('URL Tautan'),
                Tables\Columns\ToggleColumn::make('is_active')
                ->label('Aktif'),
                Tables\Columns\TextColumn::make('order')
                ->label('Urutan'),
            ])
            ->defaultSort('order', 'asc')  // Urutkan berdasarkan 'order' dari yang paling kecil
            ->filters([
                //
            ])
            ->actions([
            // Tombol untuk menaikkan urutan
            Tables\Actions\Action::make('move_up')
                ->label('Naik')
                ->action(function ($record) {
                    // Cari record sebelumnya yang memiliki urutan lebih rendah
                    $previousRecord = NavLink::where('order', '<', $record->order)
                                             ->orderByDesc('order')
                                             ->first();

                    if ($previousRecord) {
                        // Tukar urutan antara record dan previousRecord
                        $tempOrder = $record->order;
                        $record->order = $previousRecord->order;
                        $previousRecord->order = $tempOrder;
                        $record->save();
                        $previousRecord->save();
                    }
                }),

            // Tombol untuk menurunkan urutan
            Tables\Actions\Action::make('move_down')
                ->label('Turun')
                ->action(function ($record) {
                    // Cari record berikutnya yang memiliki urutan lebih tinggi
                    $nextRecord = NavLink::where('order', '>', $record->order)
                                         ->orderBy('order')
                                         ->first();

                    if ($nextRecord) {
                        // Tukar urutan antara record dan nextRecord
                        $tempOrder = $record->order;
                        $record->order = $nextRecord->order;
                        $nextRecord->order = $tempOrder;
                        $record->save();
                        $nextRecord->save();
                    }
                }),
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
            'index' => Pages\ListNavLinks::route('/'),
            'create' => Pages\CreateNavLink::route('/create'),
            'edit' => Pages\EditNavLink::route('/{record}/edit'),
        ];
    }
}
