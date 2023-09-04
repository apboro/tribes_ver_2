<?php

namespace App\Services\File;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;

class PrepareXlsByCollectionService
{

    /**
     * Создание многостраничного xlsx-файла по массиву коллекций элементов Eloquent Collection
     * @param fileName название файла
     * @param data массив коллекций элементов Eloquent Collection
     * @param listNames массив с названиями страниц файла
     * @param columnNames массив данных для файла
     */
    public function prepareManyPagesXLS(string $fileName, array $data, array $listNames, array $columnNames)
    {
        $path = public_path('/storage/statistic_files');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $fullFilePath = $path . '/' . $fileName;
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        foreach ($data as $type => $value) {
            $new_sheet = new Worksheet($spreadsheet, $listNames[$type]);
            $spreadsheet->addSheet($new_sheet);
            $this->prepareSheet($new_sheet, $columnNames[$type], $value);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($fullFilePath);
        return true;
    }

    private function prepareSheet($sheet, $columnNames, $data)
    {
        $alphas = range('A', 'Z');

        $colNumber = 0;
        foreach ($columnNames as $colKey => $eachCol) {
            $sheet->setCellValue("{$alphas[$colNumber]}1", $eachCol);
            $colNumber++;
        }

        foreach ($data as $key => $record) {
            $rowNum = $key + 2;
            $colNumber = 0;
            foreach ($columnNames as $colKey => $eachCol) {
                $sheet->setCellValue("{$alphas[$colNumber]}$rowNum", $record->$colKey);
                $colNumber++;
            }
        }
    }
    
}