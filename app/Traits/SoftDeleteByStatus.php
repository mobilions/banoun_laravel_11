<?php

namespace App\Traits;

trait SoftDeleteByStatus
{
    public function scopeActive($query)
    {
        $table = $query->getModel()->getTable();
        return $query->where("{$table}.delete_status", 0);
    }

    public function markAsDeleted(): bool
    {
        $this->delete_status = 1;
        return $this->save();
    }
}

