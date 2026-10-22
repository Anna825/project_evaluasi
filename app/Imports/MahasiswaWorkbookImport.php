<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MahasiswaWorkbookImport implements WithMultipleSheets
{
    protected array $imports = [];

    public function __construct(array $sheetNames)
    {
        foreach ($sheetNames as $sheetName) {
            $this->imports[$sheetName] = new MahasiswaImport();
        }
    }

    public function sheets(): array
    {
        return $this->imports;
    }

    public function failures(): Collection
    {
        return collect($this->imports)
            ->flatMap(fn ($import) => $import->failures())
            ->values();
    }
}
