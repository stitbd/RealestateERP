<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public function company() {
        return $this->belongsTo(Company::class, 'company_id', 'id' );
    }

    public function created_usesr() {
        return $this->belongsTo(User::class, 'created_by', 'id' );
    }

    public function modiifed_user() {
        return $this->belongsTo(User::class, 'modiifed_by', 'id' );
    }
}
