<?php


namespace App\Filters\App\Traits;


trait TiktokUniqueFilter
{
    public function tiktokUnique($search = null)
    {
        $this->singleSearch($search, 'tiktok_unique');
    }
}
