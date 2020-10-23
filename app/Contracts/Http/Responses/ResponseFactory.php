<?php

declare(strict_types=1);

namespace App\Contracts\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface ResponseFactory
{
    public function make(string $content = '', int $status = 200, array $headers = []): Response;

    public function noContent(int $status = Response::HTTP_NO_CONTENT, array $headers = []): Response;

    public function json(
        array $data = [],
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse;

    public function paginator(
        LengthAwarePaginator $paginator,
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse;

    public function mappedPaginator(
        LengthAwarePaginator $paginator,
        callable $map,
        int $status = Response::HTTP_OK,
        array $headers = [],
        int $options = 0
    ): JsonResponse;

    public function stream(callable $callback, int $status = Response::HTTP_OK, array $headers = []): StreamedResponse;

    public function redirectTo(string $path, int $status = Response::HTTP_FOUND, array $headers = []): RedirectResponse;
}
