<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DreamPlanning extends Model
{
    protected $fillable = ['tujuan_tabungan', 'total_tabungan', 'terkumpul', 'status', 'user_id'];

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
}
