<?php

namespace Tests\Feature\Authorization;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRoutesClosedTest extends TestCase
{
    use RefreshDatabase;

    public function test_logout_requires_authentication(): void
    {
        $this->postJson('/api/logout')->assertStatus(401);
    }

    public function test_establishments_requires_authentication(): void
    {
        $this->getJson('/api/establishments')->assertStatus(401);
    }

    public function test_menu_categories_remains_public(): void
    {
        $this->getJson('/api/menu/categories')->assertStatus(200);
    }

    public function test_menu_products_remains_public(): void
    {
        $this->getJson('/api/menu/products')->assertStatus(200);
    }
}
