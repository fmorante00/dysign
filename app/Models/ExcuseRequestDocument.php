<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcuseRequestDocument extends Model
{
    protected $primaryKey =
        'excuse_request_document_id';


    protected $fillable = [

        'excuse_request_id',

        'file_name',

        'file_path',

        'mime_type',

        'file_size',

    ];


    /*
    |--------------------------------------------------------------------------
    | EXCUSE REQUEST
    |--------------------------------------------------------------------------
    */

    public function excuseRequest()
    {
        return $this->belongsTo(
            ExcuseRequest::class,
            'excuse_request_id',
            'excuse_request_id'
        );
    }
}