@if (session('info') || isset($info))
<div class="alert alert-info">
    @if (session('info'))
        @if (is_array(session('info')))
            <ul>
            @foreach(session('info') as $ln)
                <li>{{$ln}}</li>
            @endforeach
            </ul>
        @else
            {{ session('info') }}
        @endif
    @endif
    @if (isset($info))
        {{ $info }}
    @endif
</div>
@endif
