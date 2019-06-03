<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone;

use Illuminate\Database\Connection;
use Illuminate\Support\ServiceProvider;

class DatabaseStickyTimezoneServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        Connection::resolverFor(
            'mysql',
            function ($connection, $database, $prefix, $config) {
                $connector = (
                    $config['sticky_timezone'] ?? false
                    ? Connectors\MySqlConnection::class
                    : \Illuminate\Database\MySqlConnection::class
                );

                return new $connector($connection, $database, $prefix, $config);
            }
        );
    }
}
