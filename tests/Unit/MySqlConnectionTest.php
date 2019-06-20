<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ZsSarkany\LaravelDatabaseStickyTimezone\Connectors\MySqlConnection;

class MySqlConnectionTest extends TestCase
{
    public function testPdoExecs()
    {
        $pdo = \Mockery::mock('\PDO');

        $conn = new MySqlConnection($pdo);

        $execs = [
            'SET time_zone = "+00:00"',
            'SET time_zone = "-06:00"',
        ];
        $pdo->shouldReceive('exec')->withArgs(function ($exec) use (&$execs) {
            $this->assertGreaterThan(
                0,
                count($execs),
                'Unexpected PDO exec: ' . $exec
            );

            $this->assertSame(
                array_shift($execs),
                $exec,
                'Unexpected PDO exec: ' . $exec
            );

            return true;
        })->andReturn(0);

        date_default_timezone_set('UTC');
        $conn->getPdo();
        $conn->getPdo(); // Make sure, that change detector works
        date_default_timezone_set('America/Belize');
        $conn->getPdo();

        // Make sure, that all the all the expected execs have been performed
        $this->assertEquals(0, count($execs));
    }

    public function testReadPdoExecs()
    {
        $pdo = \Mockery::mock('\PDO');
        $readPdo = \Mockery::mock('\PDO');

        $conn = new MySqlConnection($pdo);
        $conn->setReadPdo($readPdo);

        $execs = [
            'SET time_zone = "+00:00"',
            'SET time_zone = "-06:00"',
        ];
        $readPdo->shouldReceive('exec')->withArgs(function ($exec) use (&$execs) {
            $this->assertGreaterThan(
                0,
                count($execs),
                'Unexpected PDO exec: ' . $exec
            );

            $this->assertSame(
                array_shift($execs),
                $exec,
                'Unexpected PDO exec: ' . $exec
            );

            return true;
        })->andReturn(0);

        date_default_timezone_set('UTC');
        $conn->getReadPdo();
        $conn->getReadPdo(); // Make sure, that change detector works
        date_default_timezone_set('America/Belize');
        $conn->getReadPdo();

        // Make sure, that all the all the expected execs have been performed
        $this->assertEquals(0, count($execs));
    }

    public function testConnectionWithNullPdo()
    {
        $conn = new MySqlConnection(null);
        $conn->setReadPdo(null);

        $this->assertNull($conn->getPdo());
        $this->assertNull($conn->getReadPdo());
    }
}
