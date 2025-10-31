<?php

namespace App\Filament\Resources;

use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\BengkelResource\Pages;
use App\Filament\Resources\BengkelResource\RelationManagers;
use App\Models\Bengkel;
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
use App\Filament\Resources\BengkelResource\RelationManagers\PhotosRelationManager;

class BengkelResource extends Resource
{
    protected static ?string $model = Bengkel::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nomer_telepon')
                    ->label('No. Telepon')
                    ->required()
                    ->maxLength(20),

                Forms\Components\TextInput::make('nama_cs')
                    ->label('Nama Customer Service')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status_operasional')
                    ->options([
                        true => 'Buka',
                        false => 'Tutup',
                    ])
                    ->required(),

                Forms\Components\Select::make('status_kapasitas')
                    ->options([
                        true => 'Tersedia',
                        false => 'Penuh',
                    ])
                    ->required(),

                Forms\Components\Select::make('kota_id')
                    ->relationship('kota', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Repeater::make('servis')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('servis_mobil_id')
                            ->label('Layanan Servis Mobil')
                            ->relationship('servismobil', 'nama')
                            ->searchable()
                            ->preload()
                            ->required()
                    ]),

                Forms\Components\FileUpload::make('gambar_pratinjau')
                    ->image()
                    ->required(),
                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Bengkel')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('nomer_telepon')
                    ->label('No. Telepon'),

                Tables\Columns\TextColumn::make('nama_cs')
                    ->label('Nama CS'),

                Tables\Columns\TextColumn::make('status_operasional')
                    ->label('Status Operasional')
                    ->badge()
                    ->formatStateUsing(fn(bool $state) => $state ? 'Buka' : 'Tutup')
                    ->color(fn($state) => $state ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('status_kapasitas')
                    ->label('Status Kapasitas')
                    ->badge()
                    ->formatStateUsing(fn(bool $state) => $state ? 'Tersedia' : 'Penuh')
                    ->color(fn($state) => $state ? 'success' : 'danger'),

            ])
            ->filters([
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
            ]);
    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBengkels::route('/'),
            'create' => Pages\CreateBengkel::route('/create'),
            'edit' => Pages\EditBengkel::route('/{record}/edit'),
        ];
    }
}