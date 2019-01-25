<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class SubProduct extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'data' => [
                'id' => $this->id,
                'image' => $this->image,
                'images' => new ProductImageCollection($this->images),
                'name' => $this->name,
                'description' => $this->description,
                'working_time' => $this->working_time,
                'fields' => $this->fields->map(function($field) {
                    return [
                        'name' => $field->name,
                        'type' => $field->type,
                        'values' => $field->type == 'selector' ? new FieldCollection($field->values) : 'text'
                    ];
                }),
            ]
        ];
    }
}
