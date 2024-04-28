<?php

namespace App\Resources;

use App\Models\Progress;
use App\Models\Question;
use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Voyager;

class LevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $already_played = false;
        $progress = NULL;

        if (auth('sanctum')->check()) {
            $progress = Progress::
            where('level_id', $this->id)->
            where('user_id', auth('sanctum')->user()->id)->first();
            if ($progress) {
                $already_played = true;
                $progress = ProgressResources::make($progress);
            }
        }

        return [
            'id' => (int)$this->id,
            'title' => $this->title,
            'type' => $this->type,
            'vidoe_url' => $this->vidoe_url,
            'points' => $this->points,
            'already_played' => $already_played,
            'progress' => $progress,
            'questions' => QuestionResource::collection(Question::where('level_id', $this->id)->get())
        ];
    }
}
