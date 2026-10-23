<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationForm extends Model
{
    protected $primaryKey =
        'evaluation_form_id';


    protected $fillable = [

        'event_id',

        'created_by',

        'title',

        'description',

        'source_file_name',

        'source_file_path',

        'status',

        'published_at',

        'closed_at',

    ];


    protected $casts = [

        'published_at' =>
            'datetime',

        'closed_at' =>
            'datetime',

    ];


    public function event()
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'event_id'
        );
    }


    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'user_id'
        );
    }


    public function questions()
    {
        return $this->hasMany(
            EvaluationQuestion::class,
            'evaluation_form_id',
            'evaluation_form_id'
        )
        ->orderBy(
            'sort_order'
        );
    }


    public function submissions()
    {
        return $this->hasMany(
            EvaluationSubmission::class,
            'evaluation_form_id',
            'evaluation_form_id'
        );
    }
}