<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Models\Restaurant;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class RestaurantChatStateProcessor implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $data = json_decode(request()->getContent(), true);
        $message = $data['message'] ?? null;

        if (! $message) {
            throw new BadRequestHttpException('Missing required body parameter "message"');
        }

        // First search for relevant restaurants
        $restaurants = Restaurant::search($message)->get();

        // Build context from search results
        $restaurantContext = "Here are some relevant restaurants:\n";
        foreach ($restaurants as $restaurant) {
            $restaurantContext .= "- {$restaurant->name} ({$restaurant->cuisine_type}) in {$restaurant->city}: {$restaurant->description}\n";
        }

        return new StreamedResponse(function () use ($message, $restaurantContext) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            $stream = OpenAI::chat()->createStreamed([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a restaurant concierge assistant. Use the restaurant information provided to make informed recommendations.'],
                    ['role' => 'system', 'content' => $restaurantContext],
                    ['role' => 'user', 'content' => $message],
                ],
            ]);

            foreach ($stream as $response) {
                $text = $response->choices[0]->delta->content;
                if ($text !== null) {
                    echo 'data: '.json_encode(['content' => $text])."\n\n";
                    ob_flush();
                    flush();
                }
            }

            echo "data: [DONE]\n\n";
        });
    }
}
