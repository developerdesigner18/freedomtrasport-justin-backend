<?php

namespace App\Models;

use App\Base\Uuid\UuidModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrivateInfo extends Model
{
    use HasFactory;
    use UuidModel;
    protected $table = 'user_private_info';

    protected $fillable = [
        'user_id',
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
