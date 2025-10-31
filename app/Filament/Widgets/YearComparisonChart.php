<?php

namespace App\Filament\Widgets;

use App\Models\KelolaPemesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class YearComparisonChart extends ChartWidget
{
    protected static ?string $heading = 'Perbandingan Transaksi Per Tahun';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        // Data tahun ini
        $currentYearData = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = KelolaPemesanan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();
            $currentYearData[] = $count;
        }

        // Data tahun lalu
        $lastYearData = [];
        for ($month = 1; $month <= 12; $month++) {
            $count = KelolaPemesanan::whereYear('created_at', $lastYear)
                ->whereMonth('created_at', $month)
                ->count();
            $lastYearData[] = $count;
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

        return [
            'datasets' => [
                [
                    'label' => "Tahun {$currentYear}",
                    'data' => $currentYearData,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
                [
                    'label' => "Tahun {$lastYear}",
                    'data' => $lastYearData,
                    'borderColor' => 'rgb(156, 163, 175)',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        $currentTotal = KelolaPemesanan::whereYear('created_at', $currentYear)->count();
        $lastTotal = KelolaPemesanan::whereYear('created_at', $lastYear)->count();

        $change = $lastTotal > 0 ? round((($currentTotal - $lastTotal) / $lastTotal) * 100, 1) : 0;
        $changeText = $change >= 0 ? "+{$change}%" : "{$change}%";

        return "{$currentYear}: {$currentTotal} | {$lastYear}: {$lastTotal} | Perubahan: {$changeText}";
    }
}
