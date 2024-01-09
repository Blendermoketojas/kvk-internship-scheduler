<?php

namespace App\Services\ManageComments\Response;

use App\Http\Resources\Response\UserProfileDisplayResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment' => $this->comment,
            'comment_name' => $this->comment_name,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_profile' => new UserProfileDisplayResource($this->user_profile)
        ];
    }
}
