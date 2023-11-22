<?php

namespace App\Services\ManageInternships\ResponseResources;

use App\Http\Resources\Response\UserProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GetActiveInternshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'is_active'=> $this->is_active,
            'created_at' =>$this->created_at,
            'updated_at' => $this->updated_at,
            'user_profile' => new UserProfileResource($this->user_profile),
            'company' => $this->company
        ];
    }
}
