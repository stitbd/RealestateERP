<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    public function created_usesr() {
        return $this->belongsTo(User::class, 'created_by', 'id' );
    }

    public function modiifed_user() {
        return $this->belongsTo(User::class, 'modiifed_by', 'id' );
    }
}
