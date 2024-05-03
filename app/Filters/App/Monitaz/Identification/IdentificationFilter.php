<?php


namespace App\Filters\App\Monitaz\Identification;


use App\Filters\App\Traits\SearchFilter;
use App\Filters\App\Traits\PhoneFilter;
use App\Filters\App\Traits\FacebookUidFilter;
use App\Filters\App\Traits\TiktokUniqueFilter;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class IdentificationFilter extends FilterBuilder
{
    use SearchFilter, PhoneFilter, FacebookUidFilter, TiktokUniqueFilter;

}
