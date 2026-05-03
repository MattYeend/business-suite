<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string,mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'avatar_url' => $this->avatar ? asset(
                'storage/' . $this->avatar
            ) : null,
            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'team' => [
                'id' => $this->team_id,
                'name' => $this->teamName,
            ],
            'flags' => [
                'is_user' => $this->is_user,
                'is_admin' => $this->is_admin,
                'is_super_admin' => $this->is_super_admin,
                'is_real' => $this->is_real,
            ],
            'initials' => $this->initials,
            'role_display' => $this->roleDisplay,
            'primary_role' => $this->primaryRole,
            'roles_list' => $this->rolesList,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'meta' => $this->meta,
            'timestamps' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
                'deleted_at' => $this->deleted_at?->toISOString(),
            ],
            'can_edit' => $request->user()?->can('update', $this->resource),
        ];
    }
}
