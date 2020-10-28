@extends('layouts.app')

@section('title')
    {{ $clip->title }} -
@endsection

@section('content')
    <div class="col-12">
        <h1>{{ $clip->title }}</h1>
        <p>Captured by <a href="{{ route('curator.show', $clip->curator->id) }}">{{ $clip->curator->name }}</a></p>
        <video controls="controls" autoplay>
            <source src="{{ $clip->video_file_path }}" type="video/mp4">
        </video>
    </div>
@endsection
