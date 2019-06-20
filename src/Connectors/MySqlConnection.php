<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone\Connectors;

use Illuminate\Database\MySqlConnection as BaseMySqlConnection;
use ZsSarkany\LaravelDatabaseStickyTimezone\ChangeDetector;

class MySqlConnection extends BaseMySqlConnection
{
    use Traits\TimezoneEnforcerTrait;

    /**
     * Enforcing timezone for PDO instance by execution of "SET timezone = xxxx"
     * if needed.
     *
     * @param \PDO|null      $pdo
     * @param ChangeDetector $changeDetector
     *
     * @return \PDO|null
     */
    protected function enforcePdoTimezone(
        ?\PDO $pdo,
        ChangeDetector $changeDetector
    ): ?\PDO {
        if (is_null($pdo) || $this->pretending()) {
            return $pdo;
        }

        $changeDetector->handle($pdo, function (string $offset) use ($pdo) {
            $pdo->exec('SET time_zone = "' . $offset . '"');
        });

        return $pdo;
    }
}
