<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Http\Header;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

final class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

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
