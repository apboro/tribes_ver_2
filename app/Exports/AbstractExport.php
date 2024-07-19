<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Excel;

abstract class AbstractExport
{
    use Exportable;

    protected string $fileName = 'export';

    protected string $writerType = Excel::XLSX;

    public function getFileName(): string
    {
        $format = '%s-%s.%s';

        return sprintf($format, $this->fileName, Carbon::now()->toDateString(), strtolower($this->writerType));
    }

    public function getWriterType(): string
    {
        return $this->writerType;
    }
}