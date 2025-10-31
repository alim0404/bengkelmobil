<?php

namespace App\Filament\Resources\ServisMobilResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';
    
    protected static ?string $title = 'Variant Servis';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Variant')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('harga')
                    ->label('Harga Variant')
                    ->required()
                    ->numeric()
                    ->prefix('IDR'),
                    
                Forms\Components\Textarea::make('deskripsi')
                    ->label('Deskripsi Variant')
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Variant')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}