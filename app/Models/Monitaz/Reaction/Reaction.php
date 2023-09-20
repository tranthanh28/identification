<?php

namespace App\Models\Monitaz\Reaction;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reaction extends AppModel
{
    protected $fillable = ['name', 'created_at', 'status', 'post_ids', 'file_name'];


    protected $table = "reactions";

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  $date
     * @return string
     */
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
