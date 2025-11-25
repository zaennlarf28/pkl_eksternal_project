<?php

// app/Models/DesignSubmission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignSubmission extends Model
{
    protected $fillable = ['user_id','title','model_id','image_path','fabric_json','status','sent_at'];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(\App\Models\User::class);
    }

    // if you have a ModelProduct model
    public function model() {
        return $this->belongsTo(\App\Models\ModelProduct::class, 'model_id');
    }

    public function getImageUrlAttribute() {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
