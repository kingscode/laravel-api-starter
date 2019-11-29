<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use KoenHoeijmakers\LaravelFilterable\Contracts\Filtering;

final class Index
{
    private Filtering $filtering;

    public function __construct(Filtering $filtering)
    {
        $this->filtering = $filtering;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $builder = User::query();

        $this->filtering->builder($builder)
            ->filterFor('name', function (Builder $builder, string $value) {
                $builder->where('name', 'like', '%' . $value . '%');
            })
            ->filterFor('email', function (Builder $builder, string $value) {
                $builder->where('email', 'like', '%' . $value . '%');
            })
            ->sortFor('name')
            ->sortFor('email')
            ->filter();

        $paginator = $builder->paginate(
            $request->input('perPage')
        );

        return UserResource::collection($paginator)->toResponse($request);
    }
}
