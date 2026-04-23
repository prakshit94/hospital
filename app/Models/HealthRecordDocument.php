<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecordDocument extends Model
{
    protected $fillable = [
        'health_checkup_id',
        'original_name',
        'path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function checkup()
    {
        return $this->belongsTo(HealthCheckup::class, 'health_checkup_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Human-readable file size (e.g. "1.2 MB").
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size ?? 0;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}
