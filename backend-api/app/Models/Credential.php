<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credential extends Model
{
    protected $fillable = ['site_name', 'account_user', 'password_encrypted', 'user_id'];
}
