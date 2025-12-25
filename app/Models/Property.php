<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'price',
        'bedrooms',
        'bathrooms',
        'size',
        'address',
        'city',
        'area',
        'latitude',
        'longitude',
        'amenities',
        'property_type',
        'is_available',
        'is_verified',
        'images',
        'street',
        'building_name',
        'landmark',
        'map_lat',
        'map_lng',
        'location_confirmed',
        'formatted_address',
        'geocoding_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'map_lat' => 'decimal:8',
        'map_lng' => 'decimal:8',
        'amenities' => 'array',
        'images' => 'array',
        'geocoding_data' => 'array',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'location_confirmed' => 'boolean',
    ];

    /**
     * Get the landlord that owns the property.
     */
    public function landlord(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];
        if ($this->building_name) $parts[] = $this->building_name;
        if ($this->street) $parts[] = $this->street;
        if ($this->address) $parts[] = $this->address;
        if ($this->landmark) $parts[] = "Near {$this->landmark}";
        if ($this->city) $parts[] = $this->city;
        
        return implode(', ', $parts);
    }

    /**
     * Get coordinates for mapping.
     * Prefers manually pinned location, falls back to geocoded coordinates.
     */
    public function getCoordinatesAttribute(): array
    {
        if ($this->map_lat && $this->map_lng) {
            return [
                'lat' => $this->map_lat,
                'lng' => $this->map_lng,
                'type' => 'manual'
            ];
        }
        
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
                'type' => 'geocoded'
            ];
        }
        
        return [
            'lat' => null,
            'lng' => null,
            'type' => 'none'
        ];
    }

    /**
     * Scope for available properties.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope for verified properties.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for properties in a specific area.
     */
    public function scopeInArea($query, $area)
    {
        return $query->where('area', $area);
    }

    /**
     * Scope for properties near a location (in kilometers).
     */
    public function scopeNearLocation($query, $lat, $lng, $radius = 5)
    {
        // Using Haversine formula for distance calculation
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(latitude))))";
        
        return $query
            ->select('*')
            ->selectRaw("{$haversine} AS distance")
            ->whereRaw("{$haversine} < ?", [$radius])
            ->orderBy('distance');
    }
}