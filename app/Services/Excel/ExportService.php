<?php

namespace App\Services\Excel;

use App\Exports\AbstractExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    public AbstractExport $export;

    /**
     * @throws \Exception
     */
    public function setExport($export): self
    {
        if (!is_a($export, AbstractExport::class)) {
            throw new \Exception('Given object is not a valid exportable object.');
        }

        $this->export = $export;

        return $this;
    }

    public function download(): BinaryFileResponse
    {
        $fileName = $this->export->getFileName();

        return Excel::download($this->export, $fileName, $this->export->getWriterType());
    }
}