<?php

declare(strict_types=1);

namespace ZsSarkany\LaravelDatabaseStickyTimezone;

class ChangeDetector
{
    /**
     * SPL object hash of PDO instance.
     *
     * It is used to catch PDO instance replacements, which could easily happen
     * by calling setPdo or setReadPdo on \Illuminate\Database\Connection.
     *
     * @var string
     */
    protected $objectHash;

    /**
     * Last set offset value for change detection.
     *
     * @var string
     */
    protected $currentOffset;

    /**
     * Invoke callback if change (or uninitialized state) is detected.
     *
     * @param \PDO $pdo
     * @param callable $callback
     *
     * @return void
     */
    public function handle(\PDO $pdo, callable $callback): void
    {
        if (is_null($pdo)) {
            return;
        }

        $objectHash = spl_object_hash($pdo);
        $offset = date('P');

        if ($this->objectHash !== $objectHash || $this->currentOffset !== $offset) {
            call_user_func($callback, $offset);

            $this->objectHash = $objectHash;
            $this->currentOffset = $offset;
        }
    }
}
