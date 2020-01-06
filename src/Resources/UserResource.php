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
            'steps' => StepResource::collection($this->steps),
            'roles' => RoleResource::collection($this->roles),
            'contacts' => isset($this->contacts) ? ContactResource::collection($this->contacts) : NULL,
            'last_task' => ($this->tasks()->count() > 0) ?
                Carbon::parse($this->tasks()->orderBy('id', 'desc')->first()['datetime'])->toDateString() :
                NULL,
            $this->mergeWhen(Auth::check(), [
                'id' => $this->id,
                'links' => [
                    'show' => route('users.show', $this->id),
                    'edit' => route('users.edit', $this->id),
                    'destroy' => route('users.destroy', $this->id)
                ]
            ])
        ];
    }
}