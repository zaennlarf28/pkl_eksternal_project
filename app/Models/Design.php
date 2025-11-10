<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',            // ✅ tambahkan ini
        'user_id',
        'title',
        'fabric_json',
        'thumbnail_path',
    ];
}
