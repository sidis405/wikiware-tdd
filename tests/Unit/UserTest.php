<?php

namespace Tests\Unit;

use App\Post;
use App\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function user_has_posts()
    {
        // arrange
        $user = factory(User::class)->create();
        $posts = factory(Post::class, 10)->create([
            'user_id' => $user->id
        ]);

        // act
        $posts = $user->posts;

        // assert
        $this->assertInstanceOf('Illuminate\Support\Collection', $posts);
        $this->assertInstanceOf('App\Post', $posts->first());
    }
}
