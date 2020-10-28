<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-0">Filters</h5>

        <form action="{{ route(Route::currentRouteName(), isset($curator) ? $curator : null) }}" method="get">
            <div class="row">
                @foreach(request()->except(['page', 'title', 'game']) as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ request()->input($key) }}">
                @endforeach

                <div class="form-group col-3">
                    <label for="title">Title</label>
                    <input id="title" type="text" class="form-control form-control-sm" name="title"
                           value="{{ request()->input('title') }}">
                </div>

                <div class="form-group col-3">
                    <label for="game">Game</label>
                    <select name="game" id="game" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value=""></option>
                        @foreach(\App\Models\Clip::getGames() as $game)
                            <option value="{{ $game }}"
                                    @if(request()->input('game') == $game) selected @endif>{{ $game }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </form>
    </div>
</div>

<table class="table">
    <thead>
    <tr>
        <th>Thumbnail</th>
        <th>Title</th>
        <th>Curator</th>
        <th>Game</th>
        <th>Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach($clips as $clip)
        <tr>
            <td><a href="{{ route('clip.show', $clip) }}"><img
                    src="{{ is_null($clip->thumbnail_tiny) ? 'https://via.placeholder.com/86x45?text=No%20Image' : $clip->thumbnail_tiny }}"
                    alt="Thumbnail for {{ $clip->title }}"></a></td>
            <td class="align-middle"><a href="{{ route('clip.show', $clip) }}">{{ $clip->title }}</a></td>
            <td class="align-middle">
                @if(Route::currentRouteName() === 'home')
                    <img src="{{ $clip->curator->logo_url }}" height="32">
                @endif
                <a href="{{ route('curator.show', $clip->curator) }}">{{ $clip->curator->display_name }}</a>
            </td>
            <td class="align-middle"><a
                    href="{{ route('home', array_merge(request()->except('page'), ['game' => $clip->game])) }}">{{ $clip->game }}</a>
            </td>
            <td class="align-middle">
                <span data-toggle="tooltip" data-placement="top" title="{{ $clip->created_at->format('Y-m-d g:i:s A') }}">
                    {{ $clip->created_at->diffForHumans() }}
                </span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="float-right">
    {{ $clips->links('pagination::bootstrap-4', request()->all()) }}
</div>
