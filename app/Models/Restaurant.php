<?php

namespace App\Models;

use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\QueryParameter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

#[ApiResource]
#[QueryParameter(key: 'cuisine_type', filter: PartialSearchFilter::class)]
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
