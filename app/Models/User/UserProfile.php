<?php

namespace App\Models\User;
// namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $table = 'user_profiles';
    protected $fillable = ['name', 'user_id'];
    public $timestamps = false;

    
}
