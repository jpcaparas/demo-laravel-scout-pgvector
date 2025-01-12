<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\RequestBody;
use App\Http\Requests\RestaurantChatFormRequest;
use App\Http\Requests\RestaurantSearchFormRequest;
use App\State\RestaurantChatStateProcessor;
use App\State\RestaurantSearchStateProcessor;
use ArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

use function PHPSTORM_META\override;

/**
 * @todo Fix swagger-generated docs
 */
#[ApiResource(
    paginationItemsPerPage: 10,
    operations: [
        new Get,
        new GetCollection,
        // https://api-platform.com/docs/core/openapi/#:~:text=Overriding%20the%20OpenAPI%20Specification
        new Post(
            uriTemplate: '/restaurants/search',
            rules: RestaurantSearchFormRequest::class,
            processor: RestaurantSearchStateProcessor::class,
            openapi: new Operation(
                summary: 'Search for restaurants',
                requestBody: new RequestBody(content: new ArrayObject(
                    [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'query' => [
                                        'type' => 'string',
                                        'example' => 'Italian',
                                        'description' => 'The search query to find restaurants',
                                    ],
                                ],
                            ],
                        ],
                    ]
                    )),
                responses: [
                    200 => [
                        'description' => 'Search results',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/Restaurant',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    201 => [
                        'description' => 'Invalid input', // It's impossible to remove this response at the moment with API platform
                    ],
                ],
            ),
        ),
        new Post(
            uriTemplate: '/restaurants/chat',
            rules: RestaurantChatFormRequest::class,
            processor: RestaurantChatStateProcessor::class,
        ),
    ],
)]
class Restaurant extends Model
{
    use HasFactory, Searchable, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'cuisine_type',
        'description',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'email',
        'website',
        'price_range',
        'rating',
        'accepts_reservations',
        'opening_hours',
        'facilities',
    ];

    protected $casts = [
        'price_range' => 'decimal:1',
        'rating' => 'decimal:1',
        'accepts_reservations' => 'boolean',
        'opening_hours' => 'array',
        'facilities' => 'array',
    ];

    public function searchableAs(): string
    {
        return 'openai'; // This would result in the index name: openai_restaurants
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'cuisine_type' => $this->cuisine_type,
            'description' => $this->description,
            'city' => $this->city,
            'state' => $this->state,
        ];
    }
}
