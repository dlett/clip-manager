@extends('layouts.app')

@section('title')
    {{ $clip->title }} -
@endsection

@section('content')
    <div class="col-12">
        <h1>{{ $clip->title }}</h1>
        <p>Captured by <a href="{{ route('curator.show', $clip->curator) }}">{{ $clip->curator->name }}</a></p>
        <video controls="controls" autoplay style="width: 100%; ">
            <source src="{{ $clip->video_url }}" type="video/mp4">
        </video>
    </div>
@endsection
