<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'admission_number' => $this->admission_number,
            'roll_number' => $this->roll_number,
            'full_name' => $this->full_name,
            'class' => $this->class ? [
                'id' => $this->class->id,
                'name' => $this->class->name,
                'section' => $this->class->section,
            ] : null,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'parent_phone' => $this->parent_phone,
            'user' => [
                'email' => $this->user->email,
                'mobile' => $this->user->mobile,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
