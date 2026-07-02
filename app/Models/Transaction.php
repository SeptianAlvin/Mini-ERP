<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = ['trans_date', 'desc', 'amount', 'category_id', 'receipt_path', 'dream_planning_id', 'user_id'];

    protected static function booted()
    {
        static::addGlobalScope('user', function (\Illuminate\Database\Eloquent\Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function dreamPlanning(){
        return $this->belongsTo(DreamPlanning::class);
    }
}
