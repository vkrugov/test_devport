<?php

namespace App\Http\Resources;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id'        => $this->id,
            'url'        => $this->url,
            'expired_at' => $this->expired_at,
            'user'       => $this->whenLoaded('user', new UserResource($this->user)),
        ];
    }
}
