<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogAccess extends Model
{
    use HasFactory;
    protected $table = 'user_log_access_tokens';
}
