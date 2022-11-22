<?php

namespace App\Traits;

trait ModelTableTrait
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return (new self())->getTable();
    }
}