<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

final class Handler extends ExceptionHandler
{
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
     * @param  \Throwable $e
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $e)
    {
        if ($this->container->bound('sentry') && $this->shouldReport($e)) {
            $this->container->make('sentry')->captureException($e);
        }

        parent::report($e);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->container->make(ResponseFactory::class)->json(['message' => $exception->getMessage()], 401);
    }
}
