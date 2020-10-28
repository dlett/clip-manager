<table class="table">
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
            <td><img src="{{ is_null($clip->thumbnail_tiny) ? 'https://via.placeholder.com/16' : $clip->thumbnail_tiny }}" alt="Thumbnail for {{ $clip->title }}"></td>
            <td>{{ $clip->title }}</td>
            <td><a href="{{ route('curator.show', $clip->curator) }}">{{ $clip->curator->display_name }}</a></td>
            <td>{{ $clip->game }}</td>
            <td><a href="{{ route('clip.show', $clip) }}">View</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="float-right">
    {{ $clips->links('pagination::bootstrap-4') }}
</div>
