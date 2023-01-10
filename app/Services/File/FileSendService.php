<?php

namespace App\Services\File;

use App\Exceptions\StatisticException;
use App\Helper\ArrayHelper;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileSendService
{
    /**
     * @param Builder|\Illuminate\Database\Eloquent\Builder $builder $builder
     * @param ?string $sourceClass
     * @param array $columnNames
     * @param string $type
     * @param string $name
     * @return StreamedResponse
     * @throws StatisticException
     */
    public function sendFile(
        $builder,
        array $columnNames,
        string $sourceClass = null,
        string $type = 'csv',
        string $name = 'noname'): StreamedResponse
    {
        if (!in_array($type, ['csv', 'xlsx'])) {
            throw new StatisticException('Не поддерживаемый формат возвращаемого файла');
        }

        foreach ($columnNames as $eachCol) {
            if (empty($eachCol['title']) || empty($eachCol['attribute'])) {
                throw new StatisticException('Каждый элемент массива $columnNames должен содержать значения "title" и "attribute"');
            }
        }

        if ($type == 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=cp1251;'
            ];
            $callback = function () use ($builder, $columnNames, $sourceClass) {

                // Open output stream
                $handle = fopen('php://output', 'w');
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

                // Close the output stream
                fclose($handle);
            };
        } else {
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ];
            $callback = function () use ($builder, $columnNames, $sourceClass) {
                $alphas = range('A', 'Z');
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                foreach ($columnNames as $colKey => $eachCol) {
                    $sheet->setCellValue("{$alphas[$colKey]}1", $eachCol['title']);
                }

                $builder->chunk(100, function ($results, $page) use ($sheet, $alphas, $columnNames, $sourceClass) {
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
                $writer->save('php://output');
            };
        }
        $headers['Content-Transfer-Encoding'] = 'binary';
        $headers['Pragma'] = 'public';
        $headers['Cache-Control'] = 'max-age=3600, must-revalidate, public';
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        return response()->streamDownload($callback, "{$name}_{$date}.{$type}", $headers);
    }
}