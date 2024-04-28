<?php

namespace App\Resources;

use App\Models\Progress;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Voyager;

class ProgressUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('id',$this->id)->first();

        return [
            'id' => (int)$user->id,
            'name' => $user->name,
            'username' => $user->email,
            'play_count' => Progress::where('user_id',$user->id)->count(),
            'total_points' => Progress::where('user_id',$user->id)->sum('points'),
        ];
    }
}
