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
        return array_merge(
            $this->base(),
            $this->team(),
            $this->flags(),
            $this->roles(),
            $this->meta(),
            $this->timestamps(),
            $this->permissions($request),
        );
    }

    /**
     * Get the base user data.
     *
     * @return array
     */
    private function base(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'timezone' => $this->timezone,
            'locale' => $this->locale,
            'initials' => $this->initials,
            'role_display' => $this->roleDisplay,
            'primary_role' => $this->primaryRole,
            'roles_list' => $this->rolesList,
        ];
    }

    /**
     * Get the user's team data.
     *
     * @return array
     */
    private function team(): array
    {
        return [
            'team' => [
                'id' => $this->team_id,
                'name' => $this->teamName,
            ],
        ];
    }

    /**
     * Get the user's role flags.
     *
     * @return array
     */
    private function flags(): array
    {
        return [
            'flags' => [
                'is_user' => $this->is_user,
                'is_admin' => $this->is_admin,
                'is_super_admin' => $this->is_super_admin,
                'is_real' => $this->is_real,
            ],
        ];
    }

    /**
     * Get the user's roles.
     *
     * @return array
     */
    private function roles(): array
    {
        return [
            'roles' => RoleResource::collection(
                $this->whenLoaded('roles')
            ),
        ];
    }

    /**
     * Get the user's meta data.
     *
     * @return array
     */
    private function meta(): array
    {
        return ['meta' => $this->meta];
    }

    /**
     * Get the user's timestamps.
     *
     * @return array
     */
    private function timestamps(): array
    {
        return [
            'timestamps' => [
                'created_at' => $this->created_at?->toISOString(),
                'updated_at' => $this->updated_at?->toISOString(),
                'deleted_at' => $this->deleted_at?->toISOString(),
            ],
        ];
    }

    /**
     * Get the user's permissions related to this resource.
     *
     * @return array
     */
    private function permissions(Request $request): array
    {
        return [
            'can_edit' => $request->user()?->can('update', $this->resource),
        ];
    }
}
