<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use KoenHoeijmakers\LaravelFilterable\Contracts\Filtering;

final class Index
{
    /**
     * @var \KoenHoeijmakers\LaravelFilterable\Contracts\Filtering
     */
    protected $filtering;

    /**
     * Index constructor.
     *
     * @param \KoenHoeijmakers\LaravelFilterable\Contracts\Filtering $filtering
     */
    public function __construct(Filtering $filtering)
    {
        $this->filtering = $filtering;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(Request $request)
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

        return UserResource::collection($builder->paginate($request->input('perPage')));
    }
}
