<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ZsSarkany\LaravelDatabaseStickyTimezone\ChangeDetector;

class ChangeDetectorTest extends TestCase
{
    public function testPdoChangeDetection()
    {
        $pdoInitial = \Mockery::mock('\PDO');

        $detector = new ChangeDetector();
        $counter = 0;
        $handler = function (string $offset) use (&$counter) {
            $counter++;
        };

        /*
         * Values used for detection are nulls by default, therefore change
         * should be detected obviously.
         */
        $detector->handle($pdoInitial, $handler);
        $this->assertSame(1, $counter, 'Initial changes have been detected');

        /*
         * Repeated change detection check should not increment handler counter,
         * because the PDO instance (and time zone) is the same.
         */
        $detector->handle($pdoInitial, $handler);
        $this->assertSame(1, $counter, 'Change has been detected unexpectedly');

        /*
         * Handler counter should be incremented if handler gets called with
         * a new pdo instance. (Different SPL object hash.)
         */
        $pdoNew = \Mockery::mock('\PDO');
        $detector->handle($pdoNew, $handler);
        $this->assertSame(2, $counter, 'PDO change has not been detected');

        /*
         * Repeated change detection check should not increment handler counter,
         * because the PDO instance (and time zone) is the same.
         */
        $detector->handle($pdoNew, $handler);
        $this->assertSame(2, $counter, 'Change has been detected unexpectedly');
    }

    public function testTimezoneChangeDetection()
    {
        $pdo = \Mockery::mock('\PDO');

        $detector = new ChangeDetector();
        $counter = 0;
        $handler = function (string $offset) use (&$counter) {
            $counter++;
        };

        date_default_timezone_set('UTC');

        /*
         * Values used for detection are nulls by default, therefore change
         * should be detected obviously.
         */
        $detector->handle($pdo, $handler);
        $this->assertSame(1, $counter, 'Initial changes have been detected');

        /*
         * Repeated change detection check should not increment handler counter,
         * because the time zone (and PDO instance) is the same.
         */
        $detector->handle($pdo, $handler);
        $this->assertSame(1, $counter, 'Change has been detected unexpectedly');

        /*
         * Handler counter should be incremented if handler gets called with
         * new time zone set previously.
         */
        date_default_timezone_set('America/Belize');
        $detector->handle($pdo, $handler);
        $this->assertSame(2, $counter, 'Time zone change has not been detected');

        /*
         * Repeated change detection check should not increment handler counter,
         * because the time zone (and PDO instance) is the same.
         */
        $detector->handle($pdo, $handler);
        $this->assertSame(2, $counter, 'Change has been detected unexpectedly');
    }

    public function testNullPdo()
    {
        $detector = new ChangeDetector();
        $counter = 0;
        $handler = function (string $offset) use (&$counter) {
            $counter++;
        };

        /*
         * Values used for detection are nulls by default, therefore change
         * should be detected obviously.
         */
        $detector->handle(null, $handler);
        $this->assertSame(0, $counter, 'Handler callback has been called with NULL PDO');
    }
}
