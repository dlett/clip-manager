@extends('layouts.app')

@section('content')
    <div class="col-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>Channel</th>
            </tr>
            </thead>
            <tbody>
            @foreach($curators as $curator)
            <tr>
                <td><img width="32" height="32" src="{{ $curator->logo_url }}" alt="Logo for {{ $curator->display_name }}"></td>
                <td class="align-middle"><a href="{{ route('curator.show', $curator->id) }}">{{ $curator->display_name }}</a></td>
                <td class="align-middle"><a href="{{ $curator->channel_url }}">Twitch Channel</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
