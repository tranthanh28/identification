<?php


namespace App\Filters\App\Monitaz\Identification;


use App\Filters\App\Traits\NameFilter;
use App\Filters\App\Traits\PhoneFilter;
use App\Filters\App\Traits\FacebookUidFilter;
use App\Filters\App\Traits\TiktokUniqueFilter;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class IdentificationFilter extends FilterBuilder
{
    use NameFilter, PhoneFilter, FacebookUidFilter, TiktokUniqueFilter;
}
