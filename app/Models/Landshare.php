<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landshare extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'shareqty', 'sotangsho', 'size', 'sector',
        'road', 'block', 'facing', 'image', 'note'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

