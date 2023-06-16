<?php

namespace App\Http\Controllers\APIv3\Statistic;

use App\Helper\ArrayHelper;
use App\Http\ApiRequests\Statistic\ExportAllStatisticData;
use App\Http\ApiResources\ExportMessageResource;
use App\Http\Controllers\Controller;
use App\Repositories\Statistic\TelegramMessageStatisticRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiExportAllData extends Controller
{

    private TelegramMessageStatisticRepository $statisticRepository;

    public function __construct(
        TelegramMessageStatisticRepository $statisticRepository
    )
    {
        $this->statisticRepository = $statisticRepository;
    }

    public function exportAllData(ExportAllStatisticData $request)
    {
        $columnNames = $this->statisticRepository::EXPORT_FIELDS;

        $builder = $this->statisticRepository->getMessagesListForFile(
            $request->input('community_ids') ?? []
        );
        $builder = $builder->orderBy('count_messages', 'DESC');
        $headers = [
            'Content-Type' => 'text/csv; charset=cp1251;'
        ];
        $sourceClass = ExportMessageResource::class;
        //$zip = new ZipArchive();
        //$zip->open('test.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
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
        $headers['Content-Transfer-Encoding'] = 'binary';
        $headers['Pragma'] = 'public';
        $headers['Cache-Control'] = 'max-age=3600, must-revalidate, public';
        $date = Carbon::now()->format("Y_m_d_H_i_s");
        return response()->streamDownload($callback, "test_{$date}.csv", $headers);
    }

}
