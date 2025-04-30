<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Statistics extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', '209k')
                ->description('Total number of users')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Total Posts', '1.2M')
                ->description('Total number of posts')
                ->icon('heroicon-o-document-text')
                ->color('primary'),
        ];
    }
}
