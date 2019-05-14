<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception $exception
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Exception
     * @codeCoverageIgnore
     */
    public function report(Exception $exception)
    {
        if ($this->container->bound('sentry') && $this->shouldReport($exception)) {
            $this->container->make('sentry')->captureException($exception);
        }

        parent::report($exception);
    }
}
