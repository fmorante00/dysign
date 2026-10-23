<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationAnswer extends Model
{
    protected $primaryKey =
        'evaluation_answer_id';


    protected $fillable = [

        'evaluation_submission_id',

        'evaluation_question_id',

        'answer_text',

        'answer_number',

        'answer_boolean',

    ];


    protected $casts = [

        'answer_number' =>
            'integer',

        'answer_boolean' =>
            'boolean',

    ];


    public function submission()
    {
        return $this->belongsTo(
            EvaluationSubmission::class,
            'evaluation_submission_id',
            'evaluation_submission_id'
        );
    }


    public function question()
    {
        return $this->belongsTo(
            EvaluationQuestion::class,
            'evaluation_question_id',
            'evaluation_question_id'
        );
    }


    public function selectedOptions()
    {
        return $this->hasMany(
            EvaluationAnswerOption::class,
            'evaluation_answer_id',
            'evaluation_answer_id'
        );
    }
}