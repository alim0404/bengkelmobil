<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelolaPemesananResource\Pages;
use App\Filament\Resources\KelolaPemesananResource\RelationManagers;
use App\Models\KelolaPemesanan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\ForceDeleteBulkAction; // ✅ Perbaiki ini
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\DeleteBulkAction;

class KelolaPemesananResource extends Resource
{
    protected static ?string $model = KelolaPemesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';



    // Animasi berkedip untuk badge
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status_pembayaran', false)->count();
    }

    // ✅ Warna badge merah jika ada pemesanan belum dibayar
    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('status_pembayaran', false)->count();
        return $count > 0 ? 'danger' : 'success';
    }

    // ✅ Tooltip untuk badge
    public static function getNavigationBadgeTooltip(): ?string
    {
        $count = static::getModel()::where('status_pembayaran', false)->count();
        return $count > 0 ? "{$count} pemesanan belum dibayar" : 'Semua pemesanan sudah dibayar';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Pemesan')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('trx_id')
                    ->label('Transaksi ID')
                    ->required()
                    ->maxLength(255), 

                Forms\Components\TextInput::make('nomer_telepon')
                    ->label('Nomer Telepon')
                    ->required()
                    ->maxLength(20),

                Forms\Components\FileUpload::make('bukti')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->required(),

                Forms\Components\TextInput::make('total_bayar')
                    ->label('Total Bayar (Rp)')
                    ->numeric()
                    ->prefix('IDR')
                    ->required(),

                Forms\Components\Select::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        0 => 'Belum Dibayar',
                        1 => 'Sudah Dibayar',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('waktu_mulai')
                    ->label('Tanggal Pemesanan')
                    ->displayFormat('d-m-Y')
                    ->required(),

                Forms\Components\TimePicker::make('jam_mulai')
                    ->label('Jam Pemesanan')
                    ->default(now()->format('H:i:s'))
                    ->required(),

                Forms\Components\Textarea::make('catatan')
                    ->label('Catatan Pemesanan')
                    ->rows(3)
                    ->columnSpanFull()
                    ->placeholder('Catatan khusus dari customer (jika ada)'),

                Forms\Components\Select::make('servis_mobil_id')
                    ->label('Jenis Servis')
                    ->relationship('service_details', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('bengkel_id')
                    ->label('Bengkel')
                    ->relationship('store_details', 'nama')
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        
            ->columns([

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pemesan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('trx_id')
                    ->label('Transaksi ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nomer_telepon')
                    ->label('Nomer Telepon')
                    ->searchable()
                    ->sortable(),


                Tables\Columns\TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->money('IDR', true),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Status Bayar')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state ? 'Sudah Dibayar' : 'Belum Dibayar')
                    ->color(fn($state) => $state ? 'success' : 'danger'),

            

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) > 50) {
                            return $state;
                        }
                        return null;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),


            ])
            ->filters([
                //
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelolaPemesanans::route('/'),
            'create' => Pages\CreateKelolaPemesanan::route('/create'),
            'edit' => Pages\EditKelolaPemesanan::route('/{record}/edit'),
        ];
    }
}