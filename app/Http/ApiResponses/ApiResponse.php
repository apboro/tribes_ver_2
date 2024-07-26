<?php
declare(strict_types=1);

namespace App\Http\ApiResponses;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;


abstract class ApiResponse implements Responsable
{
    /**
     * HTTP codes for responses.
     */
    protected const CODE_OK = 200;
    protected const CODE_REDIRECT = 301;
    protected const CODE_NOT_MODIFIED = 304;
    protected const CODE_ERROR = 400;
    protected const CODE_UNAUTHORIZED = 401;
    protected const CODE_FORBIDDEN = 403;
    protected const CODE_NOT_FOUND = 404;
    protected const CODE_TOKEN_MISMATCH = 419;
    protected const CODE_VALIDATION_ERROR = 422;
    protected const CODE_SERVER_ERROR = 500;

    /** @var int Must be defined in concrete class */
    protected int $statusCode;

    /** @var array Additional headers for response */
    protected array $headers;

    protected ?Carbon $lastModified;

    /** @var string|null Response message */
    protected ?string $message = null;

    /** @var array|null Response payload */
    protected ?array $payload = [];

    public function __construct(array $headers = [])
    {
        $this->headers = $headers;
    }

    /**
     * Set message attached to response.
     *
     * @param string|null $message
     *
     * @return  $this
     */
    public function message(?string $message): self
    {
        $this->message = $message ? $this->localize($message) : null;
        return $this;
    }

    /**
     * Localize message.
     *
     * @param string $message
     *
     * @return string
     */
    protected function localize(string $message): string
    {
        $key = 'responses/' . $message;

        return trans()->has($key) ? trans($key) : $message;
    }

    /**
     * Set response payload.
     *
     * @param array|null $payload
     *
     * @return  $this
     */
    public function payload(?array $payload): self
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Add last modifier header to response.
     * Notice: modified timestamp will be converted to GMT timezone.
     *
     * @param Carbon|null $timestamp
     *
     * @return ApiResponse
     */
    public function lastModified(?Carbon $timestamp): self
    {
        $this->lastModified = $timestamp;

        return $this;
    }

    /**
     * Compose headers for response.
     *
     * @return  array
     */
    protected function getHeaders(): array
    {
        $headers = $this->headers;

        if (isset($this->lastModified)) {
            $headers['Last-Modified'] = $this->lastModified->clone()->setTimezone('GMT')->format('D, d M Y H:i:s') . ' GMT';
        }

        return $headers;
    }

    /**
     * Common API response factory.
     *
     * @param array|Arrayable $data
     * @param array $headers
     *
     * @return  ApiResponseCommon
     */
    public static function common($data, array $headers = []): ApiResponseCommon
    {
        return (new ApiResponseCommon($headers))->data($data);
    }

    /**
     * Not found API response factory.
     *
     * @param string $message
     * @param array $headers
     *
     * @return  ApiResponseNotFound
     */
    public static function notFound(string $message, array $headers = []): ApiResponseNotFound
    {
        return (new ApiResponseNotFound($headers))->message($message);
    }

    /**
     * Not modified API response factory.
     *
     * @param array $headers
     *
     * @return  ApiResponseNotModified
     */
    public static function notModified(array $headers = []): ApiResponseNotModified
    {
        return (new ApiResponseNotModified($headers));
    }

    /**
     * Token mismatch API response factory.
     *
     * @param string $message
     * @param array $headers
     *
     * @return  ApiResponseTokenMismatch
     */
    public static function tokenMismatch(string $message = 'common.token_mismatch', array $headers = []): ApiResponseTokenMismatch
    {
        return (new ApiResponseTokenMismatch($headers))->message($message);
    }

    /**
     * Access forbidden API response factory.
     *
     * @param string $message
     * @param array $headers
     *
     * @return  ApiResponseForbidden
     */
    public static function forbidden(string $message = 'common.forbidden', array $headers = []): ApiResponseForbidden
    {
        return (new ApiResponseForbidden($headers))->message($message);
    }

    /**
     * Unauthorized API response factory.
     *
     * @param string $message
     * @param array $headers
     *
     * @return  ApiResponseUnauthorized
     */
    public static function unauthorized(string $message = 'common.unauthorized', array $headers = []): ApiResponseUnauthorized
    {
        return (new ApiResponseUnauthorized($headers))->message($message);
    }

    /**
     * Success API response factory.
     *
     * @param string|null $message
     * @param array $headers
     *
     * @return  ApiResponseSuccess
     */
    public static function success(?string $message = null, array $headers = []): ApiResponseSuccess
    {
        return (new ApiResponseSuccess($headers))->message($message);
    }

    /**
     * Success API response factory.
     *
     * @param string $message
     * @param array $headers
     *
     * @return  ApiResponseError
     */
    public static function error(string $message, array $headers = []): ApiResponseError
    {
        return (new ApiResponseError($headers))->message($message);
    }

    /**
     * Form validation error API response factory.
     *
     * @param array $errors
     * @param string $message
     * @param array $headers
     *
     * @return  ApiResponseValidationError
     */
    public static function validationError(array $errors = [], string $message = 'common.validation_error', array $headers = []): ApiResponseValidationError
    {
        return (new ApiResponseValidationError($headers))->errors($errors)->message($message);
    }

    /**
     * List API response factory.
     *
     * @param array $headers
     *
     * @return  ApiResponseList
     */
    public static function list(array $headers = []): ApiResponseList
    {
        return new ApiResponseList($headers);
    }

    /**
     * List API response factory.
     *
     * @param array $headers
     *
     * @return ApiResponseListPagination
     */
    public static function listPagination(array $headers = []): ApiResponseListPagination
    {
        return new ApiResponseListPagination($headers);
    }
}
