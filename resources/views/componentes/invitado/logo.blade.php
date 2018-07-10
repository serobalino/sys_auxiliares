<div class="m-login__logo">
    <a href="{{route('home')}}">
        <img src="{{asset('images/logos/logo.svg')}}" alt="ASECONT PUYO LOGO" style="max-height: 160px">
    </a>
    @if (session('status') || isset($estado))
        <div class="m-login__desc">
            @if(session('status'))
                {{session('status')}}
            @else
                {{$estado}}
            @endif
        </div>
    @endif
</div>