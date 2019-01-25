<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource {
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
                'phone_number' => $this->phone_number,
                'name' => $this->name,
                'family' => $this->family,
                'image' => $this->image,
                'email' => $this->email
            ]
        ];
    }
}
