<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone\Connectors\Traits;

use ZsSarkany\LaravelDatabaseStickyTimezone\ChangeDetector;

trait TimezoneEnforcerTrait
{
    /**
     * Change detector for $pdo property
     *
     * @var ChangeDetector
     */
    protected $pdoChangeDetector;

    /**
     * Change detector for $readPdo property
     *
     * @var ChangeDetector
     */
    protected $readPdoChangeDetector;

    /**
     * Get the current PDO connection.
     *
     * @return \PDO|null
     */
    public function getPdo(): ?\PDO
    {
        if (!$this->pdoChangeDetector) {
            $this->pdoChangeDetector = new ChangeDetector;
        }

        return $this->enforcePdoTimezone(
            parent::getPdo(),
            $this->pdoChangeDetector
        );
    }

    /**
     * Get the current PDO connection used for reading.
     *
     * @return \PDO|null
     */
    public function getReadPdo(): ?\PDO
    {
        if (!$this->readPdoChangeDetector) {
            $this->readPdoChangeDetector = new ChangeDetector;
        }

        return $this->enforcePdoTimezone(
            parent::getReadPdo(),
            $this->readPdoChangeDetector
        );
    }
}
