<?php
/**
 * Created by PhpStorm.
 * User: frowhy
 * Date: 2017/8/1
 * Time: 下午3:25
 */

namespace Modules\Core\Supports;

use Asm89\Stack\CorsService;
use Illuminate\Contracts\Support\{
    Arrayable, Renderable, Responsable
};
use Modules\Core\Contracts\Support\Boolable;
use SoapBox\Formatter\Formatter;

class Response implements Responsable, Arrayable, Renderable, Boolable
{
    protected $response;
    protected $statusCode;

    const OK = 'core::response.ok';
    const NOT_FOUND = 'core::response.not_found';
    const BAD_REQUEST = 'core::response.bad_request';
    const FORBIDDEN = 'core::response.forbidden';
    const INTERNAL_SERVER_ERROR = 'core::response.internal_server_error';
    const UNAUTHORIZED = 'core::response.unauthorized';
    const METHOD_NOT_ALLOWED = 'core::response.method_not_allowed';

    public function __construct(array $response)
    {
        $this->response = $response;
        $this->statusCode = $response['status_code'] ?? 200;

        return $this;
    }

    private function format()
    {
        list($response, $status_code) = [$this->response, $this->statusCode];
        $formatter = Formatter::make($response, Formatter::ARR);
        $format = self::param('output_format') ?? (config('core.api.output_format'));
        $status_code = (self::param('status_sync') ?? config('core.api.status_sync')) ? $status_code : 200;
        if (in_array($format, ['application/xml', 'xml'])) {
            $response = response($formatter->toXml(), $status_code, ['Content-Type' => 'application/xml']);
        } elseif (in_array($format, ['application/x-yaml', 'yaml'])) {
            $response = response($formatter->toYaml(), $status_code, ['Content-Type' => 'application/x-yaml']);
        } elseif (in_array($format, ['text/csv', 'csv'])) {
            $response = response($formatter->toCsv(), $status_code, ['Content-Type' => 'text/csv']);
        } elseif (in_array($format, ['application/json', 'json'])) {
            $response = response($formatter->toJson(), $status_code, ['Content-Type' => 'application/json']);
        } else {
            $response = response($response, $status_code);
        }
        return $response;
    }

    private function cors(\Illuminate\Http\Response $response)
    {
        if (config('core.api.cors_enabled')) {
            /** @var CorsService $cors */
            $cors = app(CorsService::class);
            $request = request();

            if ($cors->isCorsRequest(request())) {
                if (!$response->headers->has('Access-Control-Allow-Origin')) {
                    $response = $cors->addActualRequestHeaders($response, $request);
                }
            }
        }

        return $response;
    }

    public static function param(string $param)
    {
        $request = app('Illuminate\Http\Request');
        if ($request->has($param)) {
            return $request->get($param);
        } else {
            $header_param = title_case(kebab_case(studly_case($param)));
            if ($request->hasHeader($header_param)) {
                return $request->header($header_param);
            }
        }

        return null;
    }

    /**
     * Return an response.
     *
     * @param array $response
     *
     * @return Response
     */
    private static function call(array $response)
    {
        return new self($response);
    }

    /**
     * Return an error response.
     *
     * @param string $message
     * @param int $statusCode
     *
     * @return Response
     */
    private static function error(string $message, int $statusCode)
    {
        $response['meta']['status_code'] = $statusCode;
        $response['meta']['message'] = $message;

        return self::call($response);
    }

    /**
     * Return an success response.
     *
     * @param string $message
     * @param null $data
     * @param bool $force
     *
     * @return Response
     */
    public static function success($message = null, $data = null, bool $force = false)
    {
        if (!is_string($message) && !is_null($message)) {
            $force = is_null($data) ? false : $data;
            $data = $message;
            $message = null;
        }
        if (($force && is_array($data))) {
            $_data = $data;
        } elseif (is_array($data) && array_has($data, 'data')) {
            $_data = array_get($data, 'data');
        } else {
            if (is_string($data) && json_decode($data)) {
                $_data = json_decode($data);
            } else {
                $_data = $data;
            }
        }
        if ((is_array($data) && array_has($data, 'meta'))) {
            $_meta = array_get($data, 'meta');
        } else {
            $_meta = [];
        }
        $_meta =
            array_prepend($_meta, is_null($message) ? __(self::OK)
                : $message, 'message');
        $_meta = array_prepend($_meta, 200, 'status_code');
        array_set($response, 'meta', $_meta);
        if (!is_null($_data)) {
            array_set($response, 'data', $_data);
        }

        return self::call($response);
    }

    /**
     * Return a 404 not found.
     *
     * @param string $message
     *
     * @return Response
     */
    public static function errorNotFound(?string $message = null)
    {
        return self::error($message ?? __(self::NOT_FOUND), 404);
    }

    /**
     * Return a 400 bad request.
     *
     * @param string $message
     *
     * @return Response
     */
    public static function errorBadRequest(?string $message = null)
    {
        return self::error($message ?? __(self::BAD_REQUEST), 400);
    }

    /**
     * Return a 403 forbidden.
     *
     * @param string $message
     *
     * @return Response
     */
    public static function errorForbidden(?string $message = null)
    {
        return self::error($message ?? __(self::FORBIDDEN), 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     *
     * @return Response
     */
    public static function errorInternalServerError(?string $message = null)
    {
        return self::error($message ?? __(self::INTERNAL_SERVER_ERROR), 500);
    }

    /**
     * Return a 401 unauthorized.
     *
     * @param string $message
     *
     * @return Response
     */
    public static function errorUnauthorized(?string $message = null)
    {
        return self::error($message ?? __(self::UNAUTHORIZED), 401);
    }

    /**
     * Return a 405 method not allowed.
     *
     * @param string $message
     *
     * @return Response
     */
    public static function errorMethodNotAllowed(?string $message = null)
    {
        return self::error($message ?? __(self::METHOD_NOT_ALLOWED), 405);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return $this->render();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array) array_get($this->response, 'data');
    }

    /**
     * Get the evaluated contents of the object.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return $this->cors($this->format());
    }

    /**
     * Get the true and false of the instance.
     *
     * @return bool
     */
    public function isTrue()
    {
        return $this->statusCode === 200;
    }
}
