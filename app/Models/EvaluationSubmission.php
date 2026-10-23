<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationSubmission extends Model
{
    protected $primaryKey =
        'evaluation_submission_id';


    protected $fillable = [

        'evaluation_form_id',

        'student_id',

        'submitted_at',

    ];


    protected $casts = [

        'submitted_at' =>
            'datetime',

    ];


    public function form()
    {
        return $this->belongsTo(
            EvaluationForm::class,
            'evaluation_form_id',
            'evaluation_form_id'
        );
    }


    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id',
            'student_id'
        );
    }


    public function answers()
    {
        return $this->hasMany(
            EvaluationAnswer::class,
            'evaluation_submission_id',
            'evaluation_submission_id'
        );
    }
}