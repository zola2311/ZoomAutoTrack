<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['size' => 'integer'];
    protected $fillable = [
        'model_type',
        'model_id',
        'collection',
        'disk',
        'path',
        'mime_type',
        'size',
        'original_name',
        'transcription',
        'uploaded_by',
    ];
    public function model() { return $this->morphTo(); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
