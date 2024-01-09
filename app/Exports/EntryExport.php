<?php

namespace App\Exports;

use App\Exports\ScholarExport;
use App\Exports\ProgramExport;
use App\Exports\SubprogramExport;
use App\Exports\CategoryExport;
use App\Exports\StatusExport;
use App\Exports\SchoolExport;
use App\Exports\CourseExport;
use App\Exports\LevelExport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EntryExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Scholars' => new ScholarExport(),
            'Program' => new ProgramExport(),
            'Subprogram' => new SubprogramExport(),
            'Categories' => new CategoryExport(),
            'Statuses' => new StatusExport(),
            'Courses' => new CourseExport(),
            'Levels' => new LevelExport(),
        ];
    }
}
