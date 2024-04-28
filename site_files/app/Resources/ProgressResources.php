<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Voyager;

class ProgressResources extends JsonResource
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
            'q1' => $this->q1,
            'q2' => $this->q2,
            'q3' => $this->q3,
            'q4' => $this->q4,
            'points' => $this->points,
        ];
    }
}
