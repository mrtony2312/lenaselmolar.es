<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['slug', 'label', 'position'];

    public $timestamps = true;

    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'slug');
    }
}
