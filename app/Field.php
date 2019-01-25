<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model {

    protected $fillable = [
        'name', 'type'
    ];

    public function values() {

        return $this->hasMany(FieldValue::class);
    }
}
