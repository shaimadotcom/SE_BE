<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use TCG\Voyager\Voyager;

class QuestionResource extends JsonResource
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
            'text' => $this->text,
            'answer1' => $this->answer1 . '',
            'answer2' => $this->answer2 . '',
            'answer3' => $this->answer3 . '',
            'answer4' => $this->answer4 . '',
            'correct_answer' => $this->correct_answer
        ];
    }
}
