<?php

namespace App\Filament\Widgets;

use App\Models\KelolaPemesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TransactionStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Total transaksi
        $totalTransactions = KelolaPemesanan::count();
        
        // Transaksi bulan ini
        $thisMonth = KelolaPemesanan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        // Transaksi bulan lalu
        $lastMonth = KelolaPemesanan::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
            
        // Hitung persentase perubahan
        $monthChange = $lastMonth > 0 
            ? (($thisMonth - $lastMonth) / $lastMonth) * 100 
            : 0;
        
        // Total pendapatan
        $totalRevenue = KelolaPemesanan::where('status_pembayaran', true)
            ->sum('total_bayar');
            
        // Pendapatan bulan ini
        $revenueThisMonth = KelolaPemesanan::where('status_pembayaran', true)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_bayar');
        
        // Transaksi pending
        $pendingTransactions = KelolaPemesanan::where('status_pembayaran', false)->count();

        return [
            Stat::make('Total Transaksi', $totalTransactions)
                ->description($thisMonth . ' transaksi bulan ini')
                ->descriptionIcon($monthChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($monthChange >= 0 ? 'success' : 'danger')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
                
            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Rp ' . number_format($revenueThisMonth, 0, ',', '.') . ' bulan ini')
                // ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
                
            Stat::make('Transaksi Pending', $pendingTransactions)
                ->description('Menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),
        ];
    }
}