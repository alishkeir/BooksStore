<?php

namespace App\Exceptions;

use App\Helpers\Discord;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        TokenMismatchException::class,
        ValidationException::class,
        NotFoundHttpException::class,
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
     * @return void
     *
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry')) {
            app('sentry')->captureException($exception);
        }

        $query = json_decode(request()->getContent() ?? false, true) ?? false;

        if (! is_bool($query) && ($query['request'][0]['ref'] ?? false) === 'login') {
            request()->merge([
                'body' => ['password' => '******'],
            ]);
        }
        $payload = json_encode(request()->body);

        $reportable = [
            'project' => ':banana: '.env('APP_NAME', 'Skvadmin'), // Ide írd a projekt nevét, hogy tudjuk
            'env' => 'ENV: '.App::environment(),
            'type' => $exception->getCode().' '.get_class($exception),
            'url' => $_SERVER['REQUEST_URI'] ?? request()->fullUrl(),
            'query' => ($query['request'][0]['path'] ?? '').' '.($query['request'][0]['ref'] ?? ''),
            'payload' => $payload,
            'message' => $exception->getMessage(),
            'details' => 'In: '.$exception->getFile().'. On line: '.$exception->getLine(),
        ];

        if (App::environment('dev')) {
            if ($this->shouldReport($exception)) {
                new Discord($reportable, false);
            }
        }

        if (App::environment('live')) {
            if ($this->shouldReport($exception)) {
                $reportable['project'] = str_replace('banana', 'fire', $reportable['project']);
                new Discord($reportable, false);
            }
        }

        if (App::environment('local')) {
            if ($this->shouldReport($exception)) {
                $reportable['project'] = str_replace('banana', 'house', $reportable['project']);
//                new Discord($reportable, false);
            }
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  Request  $request
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json(['errors' => ['auth' => [$exception->getMessage()]]], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
