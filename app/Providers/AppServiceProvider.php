<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (DB::connection()->getDriverName() === 'sqlite') {
            $pdo = DB::connection()->getPdo();
            $pdo->sqliteCreateFunction('DATE_FORMAT', function ($date, $format) {
                if (!$date) return null;
                $d = new \DateTime($date);
                $map = [
                    '%Y' => 'Y', '%m' => 'm', '%d' => 'd',
                    '%H' => 'H', '%i' => 'i', '%s' => 's',
                ];
                $phpFormat = strtr($format, $map);
                return $d->format($phpFormat);
            }, 2);
        }
    }
}
