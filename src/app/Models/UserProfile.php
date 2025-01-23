<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'post_code',
        'address',
        'building',
        'profile_image_path',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProfileImagePathAttribute($value)
{
    return $value ? asset('storage/' . $value) : asset('storage/images/default.png');

}

}
