<?php

namespace App\Http\Resources\User;

use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * @return bool
     */
    protected function isShowFiled(): bool
    {
        return Auth::id() === $this->id || Auth::check() && Auth::user()->hasRole(Role::ADMIN);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'phone'      => $this->when($this->isShowFiled(), $this->nphoneame),
            'email'      => $this->when($this->isShowFiled(), $this->email),
            'roles'      => RoleResource::collection($this->whenLoaded('roles')),
            'created_at' => $this->when($this->isShowFiled(), $this->created_at),
            'updated_at' => $this->when($this->isShowFiled(), $this->updated_at),
        ];
    }
}
