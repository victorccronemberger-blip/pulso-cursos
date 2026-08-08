<header class="pf-portal-header">
    <div class="container">
        <a class="pf-portal-brand" href="{{ route('my.courses') }}" aria-label="Área do aluno">
            <img src="{{ get_image(get_frontend_settings('dark_logo')) }}" alt="{{ get_settings('system_title') }}">
            <span>Área do aluno</span>
        </a>

        <div class="pf-portal-header-actions">
            <a class="pf-portal-catalog" href="{{ route('courses') }}">Ver catálogo</a>
            <a class="pf-portal-user" href="{{ route('my.profile') }}">
                @if (auth()->user()->photo)
                    <img src="{{ get_image(auth()->user()->photo) }}" alt="">
                @else
                    <span class="pf-portal-avatar-fallback" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                @endif
                <span>{{ auth()->user()->name }}</span>
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" aria-label="Sair" title="Sair"><i class="fi-rr-sign-out-alt"></i></button>
            </form>
        </div>
    </div>
</header>
