@extends('layouts.app')


@section('content')

    <h1>Latest posts ({{ $posts->count() }})</h1>

    @foreach($posts as $post)
        <div class="card">
            <div class="card-header">
                <a href="#"><h4>{{ $post->title }}</h4></a>
                <span>on {{ $post->created_at->format('d/m/Y H:i') }}</span>
                <span>by {{ $post->user->name }}</span>
            </div>
            <div class="card-body">
                {{ $post->preview }}
            </div>
            <div class="card-footer">
                //
            </div>
        </div>

        <hr>
    @endforeach

@stop
