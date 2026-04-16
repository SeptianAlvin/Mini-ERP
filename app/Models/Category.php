<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['cat_name', 'type'];

    public function transaction(){
        return $this->hasmany(Transaction::class);

    }
}
