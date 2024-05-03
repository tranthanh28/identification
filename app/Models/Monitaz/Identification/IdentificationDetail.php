<?php

namespace App\Models\Monitaz\Identification;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdentificationDetail extends AppModel
{
    use SoftDeletes;
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql15';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'identification_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'tiktok_uid',
        'tiktok_unique',
        'facebook_uid',
        'user_has_joined_group',
        'config_id',
    ];

    protected $casts = [
        'data_audience' => 'array',
        'user_has_joined_group' => 'array',
        'post_is_recorded_on_social_listening' => 'array',
        'tiktok_shop_review' => 'array',
        'infomation_shop' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function identification()
    {
        return $this->belongsTo(Identification::class, 'config_id');
    }
}
