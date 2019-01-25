<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubProduct extends Model {

    protected $fillable = [
        'has_subset', 'image', 'name', 'description', 'working_time', 'min_price', 'approved'
    ];

    public function fields() {
        return $this->hasMany(Field::class, 'product_id', 'id');
    }

    public function images() {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
