<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseFormCleanupTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_creation_allows_course_only_fields_without_quantity(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/products', [
            'name' => 'Cloud Computing',
            'sku' => 'IT-205',
            'description' => 'Cloud architecture and deployment.',
            'category' => 'IT',
            'unit_price' => 2100.00,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Cloud Computing',
            'sku' => 'IT-205',
            'category' => 'IT',
            'quantity' => 0,
        ]);

        $this->actingAs($user)
            ->get('/products/create')
            ->assertOk()
            ->assertDontSeeText('Seats')
            ->assertDontSee('Please enter the supplier name.')
            ->assertSeeText('Course name');
    }
}
