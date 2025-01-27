<?php

namespace App\Models;

use App\Base\Uuid\UuidModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAgedCare extends Model
{
    use HasFactory;
    use UuidModel;
    protected $table = 'user_aged_care';

    protected $fillable = [
        'user_id',
        'participant',
        'number',
        'provider_name',
        'care_package',
        'case_manager_name',
        'case_manager_phone',
        'case_manager_email',
        'chsp_support',
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
