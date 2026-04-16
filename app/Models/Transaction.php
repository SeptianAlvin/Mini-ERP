<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['trans_date', 'desc', 'amount','category_id'];

    public function category(){
        return $this->belongsto(Category::class);
    }
}
