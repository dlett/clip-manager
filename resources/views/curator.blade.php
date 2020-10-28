@extends('layouts.app')

@section('head')
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
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
    <div class="col-3">
        <div class="card">
            <img src="{{ $curator->logo_url }}" alt="John" style="width:100%">
            <h4 class="pt-3">{{ $curator->display_name }}</h4>
            <p class="title">{{ $count = $curator->clips()->count() }} {{ \Illuminate\Support\Str::plural('clip', $count) }} created</p>

            <div class="card-footer channel-button">
                <a href="{{ $curator->channel_url }}" class="card-footer channel-button">Twitch Profile</a>
            </div>
        </div>
    </div>

    <div class="col-9">
        @include('partials.clips-table')
    </div>
@endsection
