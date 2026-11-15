<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceDiscrepancyDocument extends Model
{
    protected $table = 'attendance_discrepancy_documents';

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'discrepancy_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
    ];

    public function discrepancy(): BelongsTo
    {
        return $this->belongsTo(
            AttendanceDiscrepancy::class,
            'discrepancy_id',
            'discrepancy_id'
        );
    }
}