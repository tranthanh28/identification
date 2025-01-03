<?php

namespace App\Models\Monitaz\FileMr;

use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileMr extends Model
{
    protected $connection = "mysql2";

    protected $fillable = [
        'name',
        'created_at',
        'status',
        'updated_at',
        'model'
    ];

    protected $table = "file_mr";
}
