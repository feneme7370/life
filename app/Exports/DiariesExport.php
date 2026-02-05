<?php

namespace App\Exports;

use App\Models\Diary\Diary;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DiariesExport implements FromCollection, WithHeadings, WithMapping
{
    public $diaries;
    public $humor_status;
    public function __construct($diaries)
    {
        $this->diaries = $diaries;
        $this->humor_status = Diary::humor_status();
    }
    
    public function collection()
    {
        return $this->diaries;
    }

    public function headings(): array
    {
        return [
            'Dia',
            'Humor',
            'Titulo',
            'Contenido',
            'Contenido limpio',
        ];
    }

    public function map($diary): array
    {
        return [
            $diary->day,
            $this->humor_status[$diary->humor],
            $diary->title,
            $diary->content,
            $this->cleanContent($diary->content),
        ];
    }

    private function cleanContent(?string $html): string
    {
        if (!$html) return '';

        $text = str_replace(
            ['</p>', '<br>', '<br/>', '<br />'],
            "\n",
            $html
        );

        return trim(
            html_entity_decode(
                strip_tags($text),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            )
        );
    }
}
