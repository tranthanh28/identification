<?php

namespace App\Models\Monitaz\Identification;

use App\Models\App\AppModel;
use App\Models\Core\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Identification extends AppModel
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
    protected $table = 'identifications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'facebook_uid',
        'tiktok_unique',
        'file_name',
        'status',
        'user_created',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function identificationDetail()
    {
        return $this->hasOne(IdentificationDetail::class, 'config_id');
    }
}
