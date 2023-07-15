<?php

namespace App\Services\File;

use App\Helper\ArrayHelper;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FIlePrepareService
{
    /**
     * @param Builder|\Illuminate\Database\Eloquent\Builder $builder $builder
     * @param array $columnNames
     * @param ?string $sourceClass
     * @param string $type
     * @param string $name
     * @return array
     */
    public function prepareFile(
        $builder,
        array $columnNames,
        string $sourceClass = null,
        string $type = 'csv',
        string $name = 'noname'
    ): array
    {
        if (!in_array($type, ['csv', 'xlsx'])) {
            return [
                'status' => false,
                'message' => 'Не поддерживаемый формат возвращаемого файла'
            ];
        }

        foreach ($columnNames as $eachCol) {
            if (empty($eachCol['title']) || empty($eachCol['attribute'])) {
                return [
                    'status' => false,
                    'message' => 'Каждый элемент массива $columnNames должен содержать значения "title" и "attribute"'
                ];
            }
        }
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $path = public_path('/storage/statistic_files');
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
        $only_file_name = "{$name}_{$date}.{$type}";
        $file_name = $path . "/" . $only_file_name;
        switch ($type) {
            case 'csv':
                $this->prepareCsv($builder, $columnNames, $sourceClass, $file_name);
                break;
            case 'xlsx':
                $this->prepareXlsx($builder, $columnNames, $sourceClass, $file_name);
                break;
            default:
                break;
        }

        return [
            'result' => true,
            'file_path' => "/storage/statistic_files/" . $only_file_name
        ];
    }

    private function prepareCsv($builder, $columnNames, $sourceClass, $file_name)
    {
        $handle = fopen($file_name, 'w');
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Add CSV headers
        fputcsv($handle, ArrayHelper::getColumn($columnNames, 'title'));

        $builder->chunk(100, function ($results, $page) use ($handle, $columnNames, $sourceClass) {
            foreach ($results as $record) {
                $row = [];
                if ($sourceClass) {
                    $data = (new $sourceClass($record))->toArray(new Request());
                    foreach ($columnNames as $eachCol) {
                        if (is_array($data[$eachCol['attribute']])) {
                            $valData = $data[$eachCol['attribute']];
                            $row[] = last($valData);
                        } else {
                            if ($eachCol['attribute'] === 'amount') {
                                $row[] = $data[$eachCol['attribute']] / 100;
                            } else {
                                $row[] = $data[$eachCol['attribute']];
                            }
                        }
                    }
                } else {
                    foreach ($columnNames as $eachCol) {
                        $row[] = $record->{$eachCol['attribute']};
                    }
                }


                fputcsv($handle, $row);
            }
        });
        fclose($handle);
    }

    private function prepareXlsx($builder, $columnNames, $sourceClass, $file_name)
    {
        $alphas = range('A', 'Z');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        foreach ($columnNames as $colKey => $eachCol) {
            $sheet->setCellValue("{$alphas[$colKey]}1", $eachCol['title']);
        }

        $builder->chunk(10000, function ($results, $page) use ($sheet, $alphas, $columnNames, $sourceClass) {
            foreach ($results as $key => $record) {
                $rowNum = $key + 2;
                if ($sourceClass) {
                    $data = (new $sourceClass($record))->toArray(new Request());
                    foreach ($columnNames as $colKey => $eachCol) {
                        if (is_array($data[$eachCol['attribute']])) {
                            $valData = $data[$eachCol['attribute']];
                            $sheet->setCellValue("{$alphas[$colKey]}$rowNum", last($valData));
                        } else {
                            if ($eachCol['attribute'] === 'amount') {
                                $sheet->setCellValue("{$alphas[$colKey]}$rowNum", $data[$eachCol['attribute']] / 100);
                            } else {
                                $sheet->setCellValue("{$alphas[$colKey]}$rowNum", $data[$eachCol['attribute']]);
                            }
                        }

                    }
                } else {
                    foreach ($columnNames as $colKey => $eachCol) {
                        $sheet->setCellValue("{$alphas[$colKey]}$rowNum", $record->{$eachCol['attribute']});
                    }
                }

            }
        });
        $writer = new Xlsx($spreadsheet);
        $writer->save($file_name);
    }
}