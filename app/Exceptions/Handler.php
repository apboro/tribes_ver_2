<?php

namespace App\Exceptions;

use App\Services\TelegramLogService;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    private $alertCodes = [
        404,
        419,
        422,
        500,
        501,
        502,
        503,
        504,
        505,
        506,
        511,
    ];
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        KnowledgeException::class,
    ];
    private TelegramLogService $telegramLogService;

    public function __construct(
        Container $container,
        TelegramLogService $telegramLogService
    )
    {
        parent::__construct($container);
        $this->telegramLogService = $telegramLogService;
    }

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $e)
    {

        $data = [
            'server' => env('APP_URL'),
            'description' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'code' => $e->getCode(),
        ];


        if(in_array($e->getCode(),$this->alertCodes)) {
            $this->telegramLogService->sendLogMessage((string)view('telegram.report', $data));
        }
        Log::channel('single')->error(
            $this->getExceptionTraceAsString($e) . PHP_EOL
            /*.$this->arrayToPrettyString($_SERVER)*/
        );
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    private function arrayToPrettyString(array $data): string
    {
        $text = "";
        foreach ($data as $key => $entity) {
            if(is_string($entity)){
                $text .= $key." : ".$entity .";". PHP_EOL;
            } elseif (is_array($entity)) {
                $this->arrayToPrettyString($entity);
            }

        }
        return $text;
    }

    private function getExceptionTraceAsString(Throwable $e) {
        $rtn = "";
        $count = 0;
        foreach ($e->getTrace() as $frame) {
            $args = "";
            if (isset($frame['args'])) {
                $args = array();
                foreach ($frame['args'] as $arg) {
                    if (is_string($arg)) {
                        $args[] = "'" . $arg . "'";
                    } elseif (is_array($arg)) {
                        $args[] = "Array";
                    } elseif (is_null($arg)) {
                        $args[] = 'NULL';
                    } elseif (is_bool($arg)) {
                        $args[] = ($arg) ? "true" : "false";
                    } elseif (is_object($arg)) {
                        $args[] = get_class($arg);
                    } elseif (is_resource($arg)) {
                        $args[] = get_resource_type($arg);
                    } else {
                        $args[] = $arg;
                    }
                }
                $args = join(", ", $args);
            }
            $rtn .= sprintf(
                "#%s %s(%s): %s%s%s(%s)\n",
                $count,
                $frame['file'] ?? '',
                $frame['line'] ?? '',
                isset($frame['class']) ? $frame['class'] : '',
                isset($frame['type']) ? $frame['type'] : '', // "->" or "::"
                $frame['function'] ?? '',
                $args
            );
            $count++;
        }
        return $rtn;
    }
}
