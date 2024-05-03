<?php


namespace App\Filters\App\Traits;


trait PhoneFilter
{
    public function phone($search = null)
    {
        $this->singleSearch($search, 'phone');
    }
}
