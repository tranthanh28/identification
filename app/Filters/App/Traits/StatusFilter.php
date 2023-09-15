<?php


namespace App\Filters\App\Traits;


trait StatusFilter
{
    public function status($status = null)
    {
        $this->whereInClause('status', array_values(explode(',', $status)));
    }
}
