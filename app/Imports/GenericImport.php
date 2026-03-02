<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class GenericImport implements ToCollection, WithHeadingRow
{
    protected string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function collection(Collection $rows)
    {
        $columns = Schema::getColumnListing($this->table);

        foreach ($rows as $row) {
            $data = [];

            foreach ($columns as $column) {
                if (isset($row[$column])) {
                    $data[$column] = $row[$column];
                }
            }

            // 🔁 Si existe UUID, hacemos updateOrInsert
            if (isset($data['uuid'])) {
                DB::table($this->table)->updateOrInsert(
                    ['uuid' => $data['uuid']],
                    $data
                );
            } else {
                DB::table($this->table)->insert($data);
            }
        }
    }
}
