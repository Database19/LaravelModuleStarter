<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename', 'original_filename', 'mime_type', 'size', 'path',
        'attachable_type', 'attachable_id', 'uploaded_by'
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
