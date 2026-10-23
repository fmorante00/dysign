<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    protected $primaryKey =
        'evaluation_question_id';


    protected $fillable = [

        'evaluation_form_id',

        'question_text',

        'question_type',

        'is_required',

        'rating_min',

        'rating_max',

        'rating_min_label',

        'rating_max_label',

        'sort_order',

    ];


    protected $casts = [

        'is_required' =>
            'boolean',

        'rating_min' =>
            'integer',

        'rating_max' =>
            'integer',

        'sort_order' =>
            'integer',

    ];


    public function form()
    {
        return $this->belongsTo(
            EvaluationForm::class,
            'evaluation_form_id',
            'evaluation_form_id'
        );
    }


    public function options()
    {
        return $this->hasMany(
            EvaluationQuestionOption::class,
            'evaluation_question_id',
            'evaluation_question_id'
        )
        ->orderBy(
            'sort_order'
        );
    }


    public function answers()
    {
        return $this->hasMany(
            EvaluationAnswer::class,
            'evaluation_question_id',
            'evaluation_question_id'
        );
    }
}