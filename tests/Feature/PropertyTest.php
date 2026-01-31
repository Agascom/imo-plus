<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Property;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_property()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/properties', [
            'title' => 'Beautiful Villa',
            'description' => 'A very nice villa',
            'price' => 1000000,
            'frequency' => 'total',
            'transaction_type' => 'sale',
            'property_type' => 'villa',
            'city' => 'Libreville',
            'neighborhood' => 'Akanda',
            'bedrooms' => 4,
            'bathrooms' => 3,
            'area' => 300,
        ]);

        $response->assertStatus(201)
            ->assertJson(['title' => 'Beautiful Villa']);

        $this->assertDatabaseHas('properties', ['title' => 'Beautiful Villa']);
    }

    public function test_can_list_properties()
    {
        $user = User::factory()->create();
        Property::factory()->count(3)->create();

        $response = $this->getJson('/api/properties');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_favorite_property()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create();

        $response = $this->actingAs($user)->postJson("/api/favorites/{$property->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Added to favorites']);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'property_id' => $property->id
        ]);

        // Toggle (Remove)
        $response = $this->actingAs($user)->postJson("/api/favorites/{$property->id}");
        $response->assertJson(['message' => 'Removed from favorites']);
    }

    public function test_property_views_increment()
    {
        $user = User::factory()->create();
        $property = Property::factory()->create(['user_id' => $user->id, 'views_count' => 0]);

        $this->getJson("/api/properties/{$property->id}");

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'views_count' => 1
        ]);
    }
}
