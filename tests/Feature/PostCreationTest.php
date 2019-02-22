<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostCreationTest extends TestCase
{
    /** @test */
    public function guest_cannot_see_post_creation_form()
    {
        //arrange
        $this->withExceptionHandling();

        //act
        $response = $this->get(route('posts.create'));

        //assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function guests_cannot_create_posts()
    {
        //arrange
        $this->withExceptionHandling();

        //act
        $response = $this->post(route('posts.store'), []);

        //assert
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_see_post_creation_form()
    {
        // arrange
        $this->signIn();

        // act
        $response = $this->get(route('posts.create'));

        // assert
        $response->assertStatus(200);
        $response->assertSeeText('Create new post');
        $response->assertViewIs('posts.create');
    }

    /** @test */
    public function user_can_create_post()
    {
        // arrange
        $this->signIn();

        $attributes = [
            'title' => 'Titolo test',
            'slug' => 'slug',
            'preview' => 'preview',
        ];

        // act
        $response = $this->post(route('posts.store'), $attributes);

        // assert
        $response->assertStatus(200);
        // verifica presenza post con $attributes in storage
        $this->assertDatabaseHas('posts', $attributes);
    }


    /** @test */
    public function post_has_required_fields()
    {
        // arrange
        $this->signIn()->withExceptionHandling();

        // act
        $response = $this->post(route('posts.store'), []);

        // assert
        $response->assertSessionHasErrors(['title']);
    }
}
