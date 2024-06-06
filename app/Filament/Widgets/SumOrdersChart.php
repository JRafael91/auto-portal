<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\Order;
use Filament\Support\RawJs;
use Carbon\Carbon;

class SumOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Órdenes por mes';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
        ->dateColumn('info_date')
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->sum('total');
        return [
            'datasets' => [
                [
                    'label' => 'Órdenes',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => ucfirst(Carbon::createFromFormat('Y-m', $value->date)->isoFormat('MMMM') ) ),
        ];
    }

    public function getDescription(): ?string
    {
        return 'Monto total de órdenes';
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                        ticks: {
                            callback: (value) => '$' + value,
                        },
                    },
                },
            }
        JS);
}
}