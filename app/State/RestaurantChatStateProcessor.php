<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class RestaurantChatStateProcessor implements ProcessorInterface
{
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $data = json_decode(request()->getContent(), true);
        $message = $data['message'] ?? null;

        if (!$message) {
            throw new BadRequestHttpException('Missing required body parameter "message"');
        }

        return new StreamedResponse(function () use ($message) {
            // Set headers for SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');

            // Stream the chat completion
            $stream = OpenAI::chat()->createStreamed([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a restaurant concierge assistant.'],
                    ['role' => 'user', 'content' => $message]
                ]
            ]);

            foreach ($stream as $response) {
                $text = $response->choices[0]->delta->content;
                if ($text !== null) {
                    echo "data: " . json_encode(['content' => $text]) . "\n\n";
                    ob_flush();
                    flush();
                }
            }

            echo "data: [DONE]\n\n";
        });
    }
}
