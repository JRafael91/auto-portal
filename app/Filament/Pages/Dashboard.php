<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string $routePath = 'dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
}
