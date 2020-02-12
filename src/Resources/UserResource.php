<?php

namespace ConfrariaWeb\User\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'roles' => $this->roles,
            'status' => $this->status,
            $this->mergeWhen(Auth::check(), [
                'id' => $this->id,
                'links' => [
                    'show' => route('admin.users.show', $this->id),
                    'edit' => route('admin.users.edit', $this->id),
                    'destroy' => route('admin.users.destroy', $this->id)
                ]
            ])
        ];
    }
}
