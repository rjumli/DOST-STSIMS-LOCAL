<?php

namespace App\Exports;

use App\Models\ListDropdown;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Http\Resources\DefaultResource;

class LevelExport implements FromCollection, WithHeadings, WithTitle, WithEvents
{
    public function collection()
    {
        $data = ListDropdown::select('name','id')->where('classification','Level')->get();
        return DefaultResource::collection($data);
    }

    public function headings(): array
    {
        return ["name","id"];
    }

    public function title(): string
    {
        return 'Level';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
               
                $event->sheet->getProtection()->setPassword('password');
                $event->sheet->getProtection()->setSheet(true);
               
            }
        ];
    }
}
