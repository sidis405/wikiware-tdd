<?php

namespace Tests\Unit;

use App\Post;
use Tests\TestCase;

class PostTest extends TestCase
{

    /** @test */
    public function post_has_author()
    {
        // arrange
        $post = factory(Post::class)->create();

        // act
        $user = $post->user;

        // assert
        $this->assertInstanceOf('App\User', $user);
    }
}
