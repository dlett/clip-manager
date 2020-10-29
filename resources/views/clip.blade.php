@extends('layouts.app')

@section('title')
    {{ $clip->title }} -
@endsection

@section('content')
    <div class="col-12">
        <video controls="controls" autoplay style="width: 100%; ">
            <source src="{{ $clip->video_url }}" type="video/mp4">
        </video>
        <div class="row">
            <div class="col-6">
                <h3 class="mb-0">{{ $clip->title }}</h3>

            </div>
            <div class="col-6">
                <small style="font-size: 14px; color: #797b7c" class="float-right">{{ $clip->views_at_import }} views
                    • {{ $clip->created_at->format('F j, Y g:i a') }}</small>

            </div>
        </div>
        <p>Captured by <a href="{{ route('curator.show', $clip->curator) }}">{{ $clip->curator->name }}</a></p>
    </div>
@endsection
