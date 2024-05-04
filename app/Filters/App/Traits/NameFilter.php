<?php


namespace App\Filters\App\Traits;


trait NameFilter
{
    public function name($search = null)
    {
        $this->singleSearch($search, 'name');
    }
}
