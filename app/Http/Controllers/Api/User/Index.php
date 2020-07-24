<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Contracts\Http\Responses\ResponseFactory;
use App\Http\Requests\Api\User\IndexRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use KoenHoeijmakers\LaravelFilterable\Contracts\Filtering;

final class Index
{
    private ResponseFactory $responseFactory;

    private Filtering $filtering;

    public function __construct(ResponseFactory $responseFactory, Filtering $filtering)
    {
        $this->responseFactory = $responseFactory;
        $this->filtering = $filtering;
    }

    public function __invoke(IndexRequest $request): JsonResponse
    {
        $builder = User::query()->select(['id', 'name', 'email']);

        $this->filtering->builder($builder)
            ->filterFor('name', static function (Builder $builder, string $value) {
                $builder->where('name', 'like', '%' . $value . '%');
            })
            ->filterFor('email', static function (Builder $builder, string $value) {
                $builder->where('email', 'like', '%' . $value . '%');
            })
            ->sortFor('name')
            ->sortFor('email')
            ->defaultSorting('name')
            ->filter();

        $paginator = $builder->paginate(
            $request->input('per_page')
        );

        return $this->responseFactory->paginator($paginator);
    }
}

