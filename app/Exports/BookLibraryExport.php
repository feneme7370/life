<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookLibraryExport implements FromCollection, WithHeadings, WithMapping
{
    public $books;
    public function __construct($books)
    {
        $this->books = $books;
    }
    
    public function collection()
    {
        return $this->books;
    }

    public function headings(): array
    {
        return [
            'Titulo',
            'Slug',
            'Título Original',
            'Autor(es)',
            'Genero(s)',
            'Etiqueta(s)',
            'Saga',
            'Volumen',
            'Publicación',
            'Favorito',
            'Calificacion',
            'Abandonado',
            'Páginas',
            'Fecha de lectura',
            'Imagen URL',
            'Sinopsis',
            'Resumen',
            'Resumen limpio',
            'Notas',
            'Notas limpias',
        ];
    }

    public function map($book): array
    {
        return [
            $book->title,
            $book->slug,
            $book->original_title,
            $book->book_subjects->pluck('name')->join(', '),
            $book->book_genres->pluck('name')->join(', '),
            $book->book_tags->pluck('name')->join(', '),
            $book->book_collections->pluck('name')->join(', '),
            $book->number_collection,
            $book->release_date,
            $book->is_favorite ? '1' : '0',
            $book->rating,
            $book->status == 5 ? '1' : '0',
            $book->pages,
            $book->reads
                ->map(function ($read) {
                    $start = $read->start_read
                        ? \Carbon\Carbon::parse($read->start_read)->format('d/m/Y')
                        : '';

                    $end = $read->end_read
                        ? \Carbon\Carbon::parse($read->end_read)->format('d/m/Y')
                        : 'En progreso';

                    return trim($start . ' → ' . $end);
                })
                ->filter()
                ->join(' | '),
            $book->cover_image_url,
            $book->synopsis,
            $book->summary,
            $this->cleanNotes($book->summary),
            $book->notes,
            $this->cleanNotes($book->notes),
        ];
    }

    private function cleanNotes(?string $html): string
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
