<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestionOption extends Model
{
    protected $primaryKey =
        'evaluation_question_option_id';


    protected $fillable = [

        'evaluation_question_id',

        'option_text',

        'sort_order',

    ];


    protected $casts = [

        'sort_order' =>
            'integer',

    ];


    public function question()
    {
        return $this->belongsTo(
            EvaluationQuestion::class,
            'evaluation_question_id',
            'evaluation_question_id'
        );
    }


    public function selectedAnswers()
    {
        return $this->hasMany(
            EvaluationAnswerOption::class,
            'evaluation_question_option_id',
            'evaluation_question_option_id'
        );
    }
}