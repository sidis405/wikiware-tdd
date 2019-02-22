<?php

namespace Feature;

use App\Post;
use Tests\TestCase;
use App\Events\PostWasUpdated;
use App\Mail\PostUpdatedEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendEmailThatPostWasUpdated;

class PostUpdatingTest extends TestCase
{
    protected $post;

    public function setUp()
    {
        parent::setUp();

        $this->post = factory(Post::class)->create();
    }

    public function updatePost($data, $post = null)
    {
        $post = $post ? $post : $this->post;

        $response = $this->patch(route('posts.update', $post), $data);

        return $response;
    }

    /** @test */
    public function guest_cannot_see_editing_form()
    {
        //arrange
        $this->withExceptionHandling();

        //act
        $response = $this->get(route('posts.edit', $this->post));

        // assert
        $response->assertRedirect(route('login'));
        $response->assertStatus(302);
    }

    /** @test */
    public function guest_cannot_update_post()
    {
        //arrange
        $this->withExceptionHandling();

        //act
        $response = $this->updatePost([]);

        // assert
        $response->assertRedirect(route('login'));
        $response->assertStatus(302);
    }

    /** @test */
    public function user_can_edit_only_own_post()
    {
        // arrange
        $this->signIn();
        $this->withExceptionHandling();

        // act
        $response = $this->get(route('posts.edit', $this->post));

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_update_only_own_post()
    {
        // arrange
        $this->signIn();
        $this->withExceptionHandling();

        // act
        $response = $this->updatePost([
            'title' => 'test title'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_update_post()
    {
        // arrrange
        $this->signIn($this->post->user);

        // act
        $response = $this->updatePost([
            'title' => 'test title'
        ]);

        // assert
        $this->assertDatabaseHas('posts', ['title' => 'test title']);
        $response->assertRedirect(route('posts.edit', $this->post));
    }

    /** @test */
    public function when_post_is_updated_and_event_is_fired()
    {
        Event::fake();

        // arrrange
        $this->signIn($this->post->user);

        // act
        $response = $this->updatePost([
            'title' => 'test title'
        ]);

        // assert
        Event::assertDispatched(PostWasUpdated::class, function ($event) {
            return $event->post->id == $this->post->id;
        });
    }

    /** @test */
    public function when_post_is_updated_a_job_is_queued()
    {
        Queue::fake();

        // arrrange
        $this->signIn($this->post->user);

        // act
        $response = $this->updatePost([
            'title' => 'test title'
        ]);

        // assert
        Queue::assertPushed(SendEmailThatPostWasUpdated::class, function ($job) {
            return $job->post->id == $this->post->id;
        });
    }

    /** @test */
    public function when_post_is_updated_a_mail_is_sent()
    {
        Mail::fake();
        // arrrange
        $this->signIn($this->post->user);

        // act
        $response = $this->updatePost([
            'title' => 'test title'
        ]);

        // assert
        // // mail contains post
        Mail::assertSent(PostUpdatedEmail::class, function ($mail) {
            $mail->build();

            return $mail->post->id === $this->post->id &&
            $mail->hasTo($this->post->user) &&
            $mail->subject == 'Your post was updated';
        });
    }
}
