<?php

namespace App\Resources;

use App\Models\Progress;
use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Voyager;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int)$this->id,
            'name' => $this->name,
            'username' => $this->email,
            'play_count' => Progress::where('user_id',$this->id)->count(),
            'total_points' => Progress::where('user_id',$this->id)->sum('points'),
        ];
    }
}
