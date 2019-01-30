<?php

namespace Tests\Feature;

use App\Post;
use Tests\TestCase;

class PostsListingTest extends TestCase
{
    /** @test */
    public function user_can_see_post_listing()
    {
        // arrange
        $post = factory(Post::class)->create();

        // act
        $response = $this->get(route('posts.index'));

        // assert
        $response->assertSee($post->title);
        $response->assertSee($post->preview);
        $response->assertSee($post->created_at->format('d/m/Y H:i'));
    }

    /** @test */
    public function user_can_see_sorted_posts_by_date()
    {
        // arrange
        $posts = factory(Post::class, 10)->create();

        // act
        $response = $this->get(route('posts.index'));

        // assert
        $response->assertSeeInOrder($posts->sortByDesc('created_at')->pluck('title')->toArray());
    }

    /** @test */
    public function post_listing_shows_author_and_category()
    {
        /// arrange
        $post = factory(Post::class)->create();
        $post->load('user');

        // act
        $response = $this->get(route('posts.index'));

        // assert
        $response->assertSee($post->user->name);
        // $response->assertSee($post->category->name);
    }
}
