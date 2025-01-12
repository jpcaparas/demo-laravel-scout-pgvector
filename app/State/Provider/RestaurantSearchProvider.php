<?php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Models\Restaurant;

class RestaurantSearchProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $query = request()->json()->get('query');

        return Restaurant::search($query)
            ->orderByDesc('_score')
            ->paginate();
    }
}
