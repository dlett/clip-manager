@extends('layouts.app')

@section('head')
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            max-width: 300px;
            margin: auto;
            text-align: center;
        }

        .title {
            color: grey;
            font-size: 18px;
        }

        .channel-button {
            border: none;
            outline: 0;
            display: inline-block;
            padding: 8px;
            color: white;
            background-color: #6441a5;
            text-align: center;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
        }

        .channel-button:hover {
            opacity: 0.8;
            color: #fff;
        }

    </style>
@endsection

@section('content')
    <div class="col-4">
        <div class="card">
            <img src="{{ $curator->logo_url }}" alt="John" style="width:100%">
            <h1>{{ $curator->display_name }}</h1>
            <p class="title">{{ $count = $curator->clips()->count() }} {{ \Illuminate\Support\Str::plural('clip', $count) }} created</p>
            <p><a href="{{ $curator->channel_url }}" class="channel-button">Twitch Profile</a></p>
        </div>
    </div>

    <div class="col-8">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Curator</th>
                <th>Game</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($curator->clips as $clip)
                <tr>
                    <td><img src="{{ $clip->thumbnail_tiny }}" alt="Thumbnail for {{ $clip->title }}"></td>
                    <td>{{ $clip->title }}</td>
                    <td><a href="{{ route('curator.show', $clip->curator->id) }}">{{ $clip->curator->display_name }}</a></td>
                    <td>{{ $clip->game }}</td>
                    <td><a href="{{ route('clip.show', $clip) }}">View</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
