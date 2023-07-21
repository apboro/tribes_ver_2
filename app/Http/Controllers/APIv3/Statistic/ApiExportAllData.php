<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Helper\ArrayHelper;
use App\Http\ApiRequests\Statistic\ExportAllStatisticData;
use App\Http\ApiResources\ExportMessageResource;
use App\Http\ApiResources\ExportModerationResource;
use App\Http\ApiResponses\ApiResponse;
use App\Http\ApiResponses\ApiResponseError;
use App\Http\Controllers\Controller;
use App\Http\Resources\Statistic\MemberResource;
use App\Repositories\Statistic\TelegramMessageStatisticRepository;
use App\Repositories\Statistic\TelegramModerationStatisticRepository;
use App\Repositories\Statistic\TelegramMembersStatisticRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApiExportAllData extends Controller
{

    private TelegramMessageStatisticRepository $statisticMessageRepository;
    private TelegramMembersStatisticRepository $membersStatisticRepository;
    private TelegramModerationStatisticRepository $moderationStatisticRepository;

    private $settings_array = [];

    /**
     * @param TelegramMessageStatisticRepository $statisticMessageRepository
     * @param TelegramMembersStatisticRepository $membersStatisticRepository
     * @param TelegramModerationStatisticRepository $moderationStatisticRepository
     */
    public function __construct(
        TelegramMessageStatisticRepository    $statisticMessageRepository,
        TelegramMembersStatisticRepository    $membersStatisticRepository,
        TelegramModerationStatisticRepository $moderationStatisticRepository
    )
    {
        $this->statisticMessageRepository = $statisticMessageRepository;
        $this->membersStatisticRepository = $membersStatisticRepository;
        $this->moderationStatisticRepository = $moderationStatisticRepository;
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        $this->settings_array = [
            'message_statistic' => [
                'repository' => $this->statisticMessageRepository,
                'resource_class' => ExportMessageResource::class,
                'export_fields' => $this->statisticMessageRepository::EXPORT_FIELDS,
                'csv_file_name' => "message_statistic_{$date}.csv",
                'xlsx_sheet_name' => "Message Statistic"
            ],
            'member_statistic' => [
                'repository' => $this->membersStatisticRepository,
                'resource_class' => MemberResource::class,
                'export_fields' => $this->membersStatisticRepository::EXPORT_FIELDS,
                'csv_file_name' => "member_statistic_{$date}.csv",
                'xlsx_sheet_name' => "Member Statistic"
            ],
            'moderation_statisitc' => [
                'repository' => $this->moderationStatisticRepository,
                'resource_class' => ExportModerationResource::class,
                'export_fields' => $this->moderationStatisticRepository::EXPORT_FIELDS,
                'csv_file_name' => "moderation_statisitc_{$date}.csv",
                'xlsx_sheet_name' => "Moderation Statistic"
            ]
        ];

    }


    /**
     * @param ExportAllStatisticData $request
     * @return ApiResponseError|BinaryFileResponse
     * @throws Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function exportAllData(ExportAllStatisticData $request)
    {
        $create_directory = Storage::makeDirectory('statistic');
        if (!$create_directory) {
            return ApiResponse::error('Ошибка создания директории');
        }
        switch ($request->input('export_type')) {
            default:
            case 'csv':
                $date = Carbon::now()->format("Y_m_d_H_i_s");
                $zip = new \ZipArchive();
                $zip_full_file_path = storage_path() . '/app/statistic/statistic_' . $date . '.zip';
                if (!$zip->open($zip_full_file_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
                    return ApiResponse::error('Ошибка создания архива');
                }
                $files_to_delete = [];
                foreach ($this->settings_array as $value) {
                    $builder = $value['repository']->getListForFile($request->input('community_ids') ?? [], $request);
                    $full_file_path = storage_path() . "/app/statistic/" . $value['csv_file_name'];
                    $files_to_delete[] = $full_file_path;
                    $this->exportCsv($full_file_path, $value['export_fields'], $builder, $value['resource_class']);
                    $zip->addFile($full_file_path, $value['csv_file_name']);

                }
                $zip->close();
                //$this->deleteTmpFiles($files_to_delete);
                return response()->download($zip_full_file_path)->deleteFileAfterSend();
            case 'xlsx':
                $date = Carbon::now()->format("Y_m_d_H_i_s");
                $file_name = "all_statistic_{$date}.xlsx";
                $full_file_path = storage_path() . '/app/statistic/' . $file_name;
                $spreadsheet = new Spreadsheet();
                $spreadsheet->removeSheetByIndex(0);
                foreach ($this->settings_array as $type => $value) {
                    $new_sheet = new Worksheet($spreadsheet, $value['xlsx_sheet_name']);
                    $spreadsheet->addSheet($new_sheet);
                    $builder = $value['repository']->getListForFile($request->input('community_ids') ?? [], $request);
                    $this->prepareSheet($value['export_fields'], $new_sheet, $builder, $value['resource_class']);
                }

                $writer = new Xlsx($spreadsheet);
                $writer->save($full_file_path);
                return response()->download($full_file_path)->deleteFileAfterSend();
        }
    }

    private function exportCsv($fileName, $columnNames, $builder, $sourceClass)
    {

        $handle = fopen($fileName, 'w');
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

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

    private function prepareSheet($columnNames, $sheet, $builder, $sourceClass)
    {
        $alphas = range('A', 'Z');

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
    }

    private function deleteTmpFiles(array $fileNames)
    {
        foreach ($fileNames as $file) {
            unlink($file);
        }
    }

}
