<?php


namespace App\Filters\App\Traits;


trait FacebookUidFilter
{
    public function facebookUid($search = null)
    {
        $this->singleSearch($search, 'facebook_uid');
    }
}
