<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Models\Restaurant;

final class RestaurantSearchStateProcessor implements ProcessorInterface
{
    public function __construct() {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $query = request()->json()->get('query');

        return response()->json(Restaurant::search($query)
            ->orderByDesc('_score')
            ->paginate());
    }
}
