<?php


namespace App\Filters\App\Monitaz\Reaction;


use App\Filters\App\Traits\DateRangeFilter;
use App\Filters\App\Traits\SearchFilter;
use App\Filters\App\Traits\StatusFilter;
use App\Filters\FilterBuilder;
use Illuminate\Database\Eloquent\Builder;

class ReactionFilter extends FilterBuilder
{
    use DateRangeFilter, SearchFilter, StatusFilter;

}
