<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'project_id ' => $this->project_id ,
            'name' => $this->name,
            'description' => $this->description,
            'deadline' => $this->deadline,
            'priority_level' => $this->priority_level,
            'created_at' => $this->created_at,
            'status' => $this->status,
//            'status_badge' => $this->statusBadge($this->status),

            'user' => $this->whenLoaded('user', function() {
                return new UserResource($this->user);
            })
        ];
    }

    /**
     * get status name
     */
    protected function statuses($status): string
    {
        return [
            '1' => 'Active',
            '2' => 'Inactive',
        ][$status] ?? '---';
    }

    /**
     * get status bg
     */
    protected function statusBadge($status): string
    {
        return [
            '1' => 'badge badge-success',
            '2' => 'badge badge-secondary',
        ][$status] ?? 'badge badge-default';
    }
}
