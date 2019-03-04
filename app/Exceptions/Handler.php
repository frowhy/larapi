<?php

namespace App\Exceptions;

use Modules\Core\Supports\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\{
    HttpException, UnauthorizedHttpException
};

class Handler extends ExceptionHandler
{
    const STATUS_CODE = 'status_code';
    const MESSAGE = 'message';
    const DEBUG = 'debug';

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     *
     * @return void
     * @throws \Exception
     */
    public function report(\Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception|HttpException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Exception $exception)
    {
        if ($request->is('api/*') || $request->wantsJson()) {
            if ('web' !== config('core.api.error_format')) {
                if (!$exception instanceof UnauthorizedHttpException && method_exists($exception, 'getStatusCode')) {
                    $response['meta'][self::STATUS_CODE] = $exception->getStatusCode();
                    if ($exception->getMessage()) {
                        $response['meta'][self::MESSAGE] = $exception->getMessage();
                    } else {
                        preg_match("/Symfony\\\\Component\\\\HttpKernel\\\\Exception\\\\(.*?)HttpException/si", get_class($exception), $message);
                        $response['meta'][self::MESSAGE] = $message[1];
                    }
                } elseif ($exception instanceof ModelNotFoundException) {
                    $response['meta'][self::STATUS_CODE] = 404;
                    $response['meta'][self::MESSAGE] = $exception->getMessage();
                } elseif ($exception instanceof AuthenticationException) {
                    $response['meta'][self::STATUS_CODE] = 401;
                    $response['meta'][self::MESSAGE] = $exception->getMessage();
                } else {
                    $response['meta'][self::STATUS_CODE] =
                        method_exists($exception, 'getStatusCode')
                            ? $exception->getStatusCode()
                            : (0 ===
                               $exception->getCode() ? 500 : $exception->getCode());
                    $response['meta'][self::MESSAGE] =
                        null === $exception->getMessage() ? class_basename(get_class($exception))
                            : $exception->getMessage();
                }
                if (config('app.debug')) {
                    $response['meta'][self::DEBUG]['file'] = $exception->getFile();
                    $response['meta'][self::DEBUG]['line'] = $exception->getLine();
                    $response['meta'][self::DEBUG]['trace'] = $exception->getTrace();
                }

                return $this->output(collect($response)->toArray());
            }
        }

        return parent::render($request, $exception);
    }

    public function output(array $response)
    {
        return (new Response($response))->render();
    }
}
