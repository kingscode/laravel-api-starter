<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Header;
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

    public function render($request, Throwable $e)
    {
        $request->headers->set(Header::ACCEPT, 'application/json');

        return parent::render($request, $e);
    }
}
