@php
    $currentRoute = Route::currentRouteName();
    $items = [
        ['routes' => ['my.courses'], 'route' => 'my.courses', 'icon' => 'fi-rr-book-open-cover', 'label' => 'Meus cursos'],
        ['routes' => ['my.exams', 'my.exam.details'], 'route' => 'my.exams', 'icon' => 'fi-rr-document', 'label' => 'Simulados e provas'],
        ['routes' => ['my.course.bundles', 'my.course.bundle.details', 'my.course.bundle.invoice'], 'route' => 'my.course.bundles', 'icon' => 'fi-rr-books', 'label' => 'Pacotes de cursos'],
        ['routes' => ['wishlist'], 'route' => 'wishlist', 'icon' => 'fi-rr-heart', 'label' => 'Lista de desejos'],
        ['routes' => ['cart'], 'route' => 'cart', 'icon' => 'fi-rr-shopping-cart', 'label' => 'Carrinho'],
        ['routes' => ['purchase.history', 'invoice'], 'route' => 'purchase.history', 'icon' => 'fi-rr-receipt', 'label' => 'Compras e faturas'],
        ['routes' => ['message', 'message.inbox'], 'route' => 'message', 'icon' => 'fi-rr-comment-alt', 'label' => 'Mensagens'],
        ['routes' => ['support.ticket.index', 'support.ticket.create', 'support.ticket.message'], 'route' => 'support.ticket.index', 'icon' => 'fi-rr-headset', 'label' => 'Suporte'],
        ['routes' => ['my.profile'], 'route' => 'my.profile', 'icon' => 'fi-rr-user', 'label' => 'Meu perfil'],
        ['routes' => ['become.instructor'], 'route' => 'become.instructor', 'icon' => 'fi-rr-user-add', 'label' => 'Quero ensinar'],
    ];
@endphp

<aside class="col-lg-3 col-md-4 pf-portal-sidebar-column">
    <nav class="pf-portal-sidebar" aria-label="Navegação da área do aluno">
        <div class="pf-portal-profile">
            @if (auth()->user()->photo)
                <img src="{{ get_image(auth()->user()->photo) }}" alt="">
            @else
                <span class="pf-portal-avatar-fallback" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
            @endif
            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <span>{{ auth()->user()->email }}</span>
            </div>
        </div>

        <ul>
            @foreach ($items as $item)
                @if (Route::has($item['route']))
                    <li class="{{ in_array($currentRoute, $item['routes'], true) ? 'active' : '' }}">
                        <a href="{{ route($item['route']) }}">
                            <i class="{{ $item['icon'] }}" aria-hidden="true"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>
