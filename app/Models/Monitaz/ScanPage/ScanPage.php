<?php

namespace App\Models\Monitaz\ScanPage;

use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScanPage extends Model
{
    protected $fillable = ['name', 'created_at', 'status', 'list_fb_ids','pass_day', 'file_name'];

    protected $table = "scan_pages";
}
