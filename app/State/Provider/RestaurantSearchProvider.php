<?php

// app/State/Provider/RestaurantSearchProvider.php

namespace App\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Models\Restaurant;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RestaurantSearchProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        // Get query parameter from request
        // TODO: Leverage the parameter bag provided by the $operation object
        $query = request()->query('q');

        if (! $query) {
            throw new BadRequestHttpException('Missing required query parameter "q"');
        }

        // Perform Scout search with vector similarity
        return Restaurant::search($query)
            ->orderByDesc('_score') // Sort by relevance
            ->paginate();
    }
}
