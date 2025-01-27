<?php

namespace App\Models;

use App\Base\Uuid\UuidModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNiisq extends Model
{
    use HasFactory;
    use UuidModel;
    protected $table = 'user_niisq';

    protected $fillable = [
        'user_id',
        'participant',
        'number',
        'plan_manager_name',
        'plan_manager_phone',
        'plan_manager_email',
        'health_awareness',
        'health_details',
        'other',
    ];

    /**
     * The user wallet that the user_id belongs to.
     * @tested
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
