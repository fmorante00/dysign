<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationAnswerOption extends Model
{
    protected $primaryKey =
        'evaluation_answer_option_id';


    protected $fillable = [

        'evaluation_answer_id',

        'evaluation_question_option_id',

    ];


    public function answer()
    {
        return $this->belongsTo(
            EvaluationAnswer::class,
            'evaluation_answer_id',
            'evaluation_answer_id'
        );
    }


    public function option()
    {
        return $this->belongsTo(
            EvaluationQuestionOption::class,
            'evaluation_question_option_id',
            'evaluation_question_option_id'
        );
    }
}