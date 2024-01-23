<?php

namespace App\Http\Resources\Benefit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'academic_year' => $this->semester->academic_year,
            'semester' => $this->semester->semester->name,
            'level' => $this->level,
            'benefits' => $this->benefits,
            'total' => $this->benefits->sum('amount'),
        ];
    }
}
