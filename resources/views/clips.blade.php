@extends('layouts.app')

@section('content')
    <div class="col-12">
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
            @foreach($clips as $clip)
            <tr>
                <td><img src="{{ $clip->thumbnail_tiny }}" alt="Thumbnail for {{ $clip->title }}"></td>
                <td>{{ $clip->title }}</td>
                <td><a href="{{ route('curator.show', $clip->curator) }}">{{ $clip->curator->display_name }}</a></td>
                <td>{{ $clip->game }}</td>
                <td><a href="{{ route('clip.show', $clip) }}">View</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
