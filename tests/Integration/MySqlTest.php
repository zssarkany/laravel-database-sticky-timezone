<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone\Tests\Integration;

use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Orchestra\Testbench\TestCase;
use ZsSarkany\LaravelDatabaseStickyTimezone\DatabaseStickyTimezoneServiceProvider;

class MySqlTest extends TestCase
{
    /**
     * Get application providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getApplicationProviders($app)
    {
        return array_merge(
            [DatabaseStickyTimezoneServiceProvider::class],
            parent::getApplicationProviders($app)
        );
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        Config::set('database.connections.mysql-sticky', array_merge(
            Config::get('database.connections.mysql'),
            ['sticky_timezone' => true]
        ));
    }

    /**
     * Test resolver registration and proper functioning.
     *
     * @return void
     */
    public function testConnectionResolverRegistration()
    {
        $resolver = Connection::getResolver('mysql');

        // Resolver is defined as a closure
        $this->assertInstanceOf(\Closure::class, $resolver);

        // Resolver closure is defined by the servie provider
        $this->assertSame(
            (new \ReflectionFunction($resolver))->getFileName(),
            implode(DIRECTORY_SEPARATOR, [
                dirname(dirname(__DIR__)),
                'src',
                'DatabaseStickyTimezoneServiceProvider.php'
            ])
        );
    }

    public function testResolvedNonStickyConnection()
    {
        $this->assertSame(
            \Illuminate\Database\MySqlConnection::class,
            get_class(DB::connection('mysql'))
        );
    }

    public function testResolvedStickyConnection()
    {
        $this->assertSame(
            \ZsSarkany\LaravelDatabaseStickyTimezone\Connectors\MySqlConnection::class,
            get_class(DB::connection('mysql-sticky'))
        );
    }
}
