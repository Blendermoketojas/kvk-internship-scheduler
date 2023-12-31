<?php

namespace App\Http\Resources\Response;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "fullname" => $this->fullname,
            "address" => $this->address,
            "country" => $this->country,
            "image_path" => $this->image_path,
            "description" => $this->description,
        ];
    }
}
