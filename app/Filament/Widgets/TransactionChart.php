<?php

namespace App\Filament\Widgets;

use App\Models\KelolaPemesanan;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TransactionChart extends ChartWidget
{
    protected static ?string $heading = 'Transaksi Pemesanan';

    protected static ?int $sort = 2;

    // Opsi filter untuk memilih periode
    public ?string $filter = 'year';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
            'year' => 'Tahun Ini',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        // Tentukan periode berdasarkan filter
        switch ($activeFilter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $perInterval = 'perHour';
                $format = 'H:00';
                break;
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $perInterval = 'perDay';
                $format = 'D, d M';
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $perInterval = 'perDay';
                $format = 'd M';
                break;
            case 'year':
            default:
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $perInterval = 'perMonth';
                $format = 'M Y';
                break;
        }

        // Ambil data transaksi
        $data = Trend::model(KelolaPemesanan::class)
            ->between(
                start: $start,
                end: $end,
            )
            ->$perInterval()
            ->count();

        // Ambil data transaksi yang sudah dibayar
        $dataPaid = Trend::query(
            KelolaPemesanan::query()->where('status_pembayaran', true)
        )
            ->between(
                start: $start,
                end: $end,
            )
            ->$perInterval()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Total Transaksi',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Transaksi Terbayar',
                    'data' => $dataPaid->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) use ($format) {
                return Carbon::parse($value->date)->format($format);
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // Tambahkan description untuk info lebih detail
    public function getDescription(): ?string
    {
        $total = KelolaPemesanan::count();
        $paid = KelolaPemesanan::where('status_pembayaran', true)->count();
        $unpaid = $total - $paid;

        return "Total: {$total} | Terbayar: {$paid} | Belum Dibayar: {$unpaid}";
    }
}