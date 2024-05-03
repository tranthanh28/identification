<?php


namespace App\Filters\App\Traits;


trait TiktokUniqueFilter
{
    public function tiktok_unique($search = null)
    {
        $this->singleSearch($search, 'tiktok_unique');
    }
}
