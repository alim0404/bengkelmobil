<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServisMobilResource\Pages;
use App\Filament\Resources\ServisMobilResource\RelationManagers;
use App\Models\ServisMobil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\TrashedFilter;

class ServisMobilResource extends Resource
{
    protected static ?string $model = ServisMobil::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->prefix('IDR')
                    ->numeric(),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->required(),

                Forms\Components\FileUpload::make('icon')
                    ->image()
                    ->required(),
                Forms\Components\Textarea::make('detail')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->prefix('IDR')
                    ->numeric()
                    ->helperText('Harga ini akan digunakan jika servis tidak memiliki variant'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga'),
                Tables\Columns\ImageColumn::make(name: 'icon'),
                Tables\Columns\TextColumn::make('variants_count')
                    ->counts('variants')
                    ->label('Jumlah Variant')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                //
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\VariantsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServisMobils::route('/'),
            'create' => Pages\CreateServisMobil::route('/create'),
            'edit' => Pages\EditServisMobil::route('/{record}/edit'),
        ];
    }
}
