@php
$parent_categories = DB::table('categories')->where('parent_id', 0)->latest('id')->get();
$current_route = Route::currentRouteName();
@endphp

<nav class="glass-nav">
    <div class="container">
        <div class="nav-container">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="nav-logo">
                <img src="{{ get_image(get_frontend_settings('dark_logo')) }}" alt="system logo" class="logo-img">
            </a>

            {{-- Desktop Navigation --}}
            <div class="nav-menu desktop-only">
                <ul class="nav-list">

                    {{-- Courses Dropdown --}}
                    <li class="nav-item has-dropdown">
                        <button class="nav-link @if ($current_route == 'courses') active @endif">
                            <span>{{ get_phrase('Cursos') }}</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>

                        <div class="dropdown-panel">
                            <div class="dropdown-content">
                                @foreach ($parent_categories->take(10) as $parent_category)
                                @php
                                $child_categories = App\Models\Category::where('parent_id', $parent_category->id);
                                @endphp

                                <div class="dropdown-item-wrap">
                                    <a href="{{ route('courses', $parent_category->slug) }}" class="dropdown-link">
                                        <i class="{{ $parent_category->icon }} dropdown-icon"></i>
                                        <span>{{ ucfirst($parent_category->title) }}</span>
                                        @if ($child_categories->count() > 0)
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" class="arrow-right">
                                            <path d="M5 3L9 7L5 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                        </svg>
                                        @endif
                                    </a>

                                    @if ($child_categories->count() > 0)
                                    <div class="sub-dropdown">
                                        @foreach ($child_categories->get() as $child_category)
                                        <a href="{{ route('courses', $child_category->slug) }}" class="sub-dropdown-link">
                                            {{ ucfirst($child_category->title) }}
                                        </a>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach

                                <a href="{{ route('courses') }}" class="dropdown-link view-all @if ($current_route == 'courses') active @endif">
                                    <span>{{ get_phrase('Cursos') }}</span>
                                </a>
                            </div>
                        </div>
                    </li>

                    {{-- Other Dropdown --}}
                    <li class="nav-item has-dropdown">
                        <button class="nav-link">
                            <span>{{ get_phrase('Outros') }}</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>

                        <div class="dropdown-panel">
                            <div class="dropdown-content">
                                {{--<a href="{{ route('ebooks') }}" class="dropdown-link">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M20.7129 4.85907C19.0679 4.07807 17.3291 3.86006 15.5171 4.08306C13.5101 4.33006 12 6.03811 12 8.06011V20.0001C14.785 18.1441 17.569 18.0101 20.354 18.8011C20.677 18.8931 21 18.661 21 18.325V5.31903C21 5.12603 20.8879 4.94207 20.7129 4.85907Z" fill="#2ED3B7" />
                                        <path d="M3.28711 4.85907C4.93211 4.07807 6.67091 3.86006 8.48291 4.08306C10.4899 4.33006 12 6.03811 12 8.06011V20.0001C9.215 18.1441 6.431 18.0101 3.646 18.8011C3.323 18.8931 3 18.661 3 18.325V5.31903C3 5.12603 3.11211 4.94207 3.28711 4.85907Z" fill="#0E5C63" />
                                        <path d="M18 9.75H15C14.586 9.75 14.25 9.414 14.25 9C14.25 8.586 14.586 8.25 15 8.25H18C18.414 8.25 18.75 8.586 18.75 9C18.75 9.414 18.414 9.75 18 9.75ZM17.75 12C17.75 11.586 17.414 11.25 17 11.25H15C14.586 11.25 14.25 11.586 14.25 12C14.25 12.414 14.586 12.75 15 12.75H17C17.414 12.75 17.75 12.414 17.75 12Z" fill="#0E5C63" />
                                    </svg>
                                    {{ get_phrase('E-books') }}
                                </a>--}}
                                <a href="{{ route('course.bundles') }}" class="dropdown-link">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4" d="M11.5 21H9.5C8.5 21 8 20.5 8 19.5V4.5C8 3.5 8.5 3 9.5 3H11.5C12.5 3 13 3.5 13 4.5V19.5C13 20.5 12.5 21 11.5 21Z" fill="#2ED3B7" />
                                        <path opacity="0.4" d="M5.5 21H3.5C2.5 21 2 20.5 2 19.5V4.5C2 3.5 2.5 3 3.5 3H5.5C6.5 3 7 3.5 7 4.5V19.5C7 20.5 6.5 21 5.5 21Z" fill="#2ED3B7" />
                                        <path opacity="0.4" d="M20.7972 20.533L18.855 20.9461C17.884 21.1521 17.2951 20.77 17.0891 19.799L13.993 5.23307C13.786 4.26207 14.1689 3.67308 15.1399 3.46708L17.0821 3.05399C18.0531 2.84799 18.6419 3.23009 18.8479 4.20109L21.9441 18.767C22.1501 19.738 21.7682 20.327 20.7972 20.533Z" fill="#2ED3B7" />
                                        <path d="M13 7.75H8V6.25H13V7.75ZM13 16.25H8V17.75H13V16.25ZM7 6.25H2V7.75H7V6.25ZM7 16.25H2V17.75H7V16.25ZM19.21 5.89001L14.3501 6.93005L14.6599 8.40002L19.52 7.35999L19.21 5.89001ZM21.28 15.6L16.4199 16.64L16.73 18.11L21.5901 17.0699L21.28 15.6Z" fill="#0E5C63" />
                                    </svg>
                                    {{ get_phrase('Pacotes de cursos') }}
                                </a>

                            </div>
                        </div>
                    </li>

                    {{-- Free Learning --}}
                    <li class="nav-item">
                        <a href="{{ route('free.courses') }}" class="nav-link">
                            <span>{{ get_phrase('Aprenda grátis') }}</span>
                        </a>
                    </li>
                    {{--<li class="nav-item">
                        <a href="{{ route('bootcamps') }}" class="dropdown-link">{{ get_phrase('Bootcamps') }}</a>
                    </li>--}}

                    {{-- Language Dropdown
                    <li class="nav-item has-dropdown">
                        <button class="nav-link">
                            <span>EN</span>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>

                        <div class="dropdown-panel">
                            <div class="dropdown-content">
                                <a href="javascript:void(0);" class="dropdown-link">{{ get_phrase('English') }}</a>
                                <a href="javascript:void(0);" class="dropdown-link">{{ get_phrase('Português') }}</a>
                            </div>
                        </div>
                    </li>--}}
                </ul>
            </div>

            {{-- Right Actions --}}
            <div class="nav-actions">

                {{-- Search - Desktop --}}
                <form action="{{ route('courses') }}" method="get" class="search-form desktop-only">
                    <div class="search-box">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" class="search-icon">
                            <path d="M8.5 15.5C12.366 15.5 15.5 12.366 15.5 8.5C15.5 4.63401 12.366 1.5 8.5 1.5C4.63401 1.5 1.5 4.63401 1.5 8.5C1.5 12.366 4.63401 15.5 8.5 15.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path d="M16.5 16.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                        <input
                            type="search"
                            name="search"
                            placeholder="{{ get_phrase('O que você quer aprender?') }}"
                            @if (request()->has('search')) value="{{ request()->input('search') }}" @endif
                        class="search-input"
                        >
                    </div>
                </form>

                {{-- Search - Mobile --}}
                <button class="icon-btn mobile-only search-toggle">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M9 16C12.866 16 16 12.866 16 9C16 5.13401 12.866 2 9 2C5.13401 2 2 5.13401 2 9C2 12.866 5.13401 16 9 16Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M18 18L14.65 14.65" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>

                {{-- User Profile / Login --}}
                @if (isset(auth()->user()->id))
                <div class="dropdown profile-dropdown">
                    <button class="profile-btn" type="button" data-bs-toggle="dropdown">
                        <img src="{{ get_image(Auth()->user()->photo) }}" alt="profile" class="profile-avatar">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" class="desktop-only">
                            <path d="M3.5 5.25L7 8.75L10.5 5.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end profile-menu">
                        <div class="profile-header">
                            <img src="{{ get_image(Auth()->user()->photo) }}" alt="profile" class="profile-avatar-lg">
                            <div>
                                <h6 class="profile-name">{{ ucfirst(Auth()->user()->name) }}</h6>
                                <p class="profile-role">{{ ucfirst(Auth()->user()->role) }}</p>
                            </div>
                        </div>

                        <div class="profile-divider"></div>

                        <div class="profile-links">
                            @if (in_array(auth()->user()->role, ['admin', 'instructor']))
                            <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="profile-link active">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M9 9.75C8.95 9.745 8.89 9.745 8.835 9.75C7.56 9.705 6.54 8.655 6.54 7.365C6.54 6.045 7.605 4.97249 8.9325 4.97249C10.2525 4.97249 11.325 6.045 11.325 7.365C11.3175 8.655 10.305 9.705 9 9.75Z" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M13.635 14.535C12.345 15.705 10.6275 16.425 8.9925 16.425C7.3575 16.425 5.64 15.705 4.35 14.535C4.4175 13.875 4.8375 13.23 5.58 12.72C7.4925 11.4375 10.5075 11.4375 12.405 12.72C13.1475 13.23 13.5675 13.875 13.635 14.535Z" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M9 16.5C13.1421 16.5 16.5 13.1421 16.5 9C16.5 4.85786 13.1421 1.5 9 1.5C4.85786 1.5 1.5 4.85786 1.5 9C1.5 13.1421 4.85786 16.5 9 16.5Z" stroke="currentColor" stroke-width="1.35" />
                                </svg>
                                <span>{{ get_phrase('Painel') }}</span>
                            </a>
                            @endif

                            @if (Auth()->user()->role != 'admin')
                            <a href="{{ route('my.courses') }}" class="profile-link">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M16.5 12.555V3.5025C16.5 2.6025 15.7725 1.935 14.8725 2.01H14.8275C13.275 2.145 10.875 2.9475 9.525 3.7875L9.405 3.87C9.1875 4.005 8.8275 4.005 8.61 3.87L8.415 3.7575C7.065 2.925 4.6725 2.13 3.12 2.0025C2.22 1.9275 1.5 2.6025 1.5 3.495V12.555C1.5 13.275 2.085 13.95 2.805 14.04L3.0225 14.07C4.65 14.2875 7.155 15.1125 8.61 15.9L8.64 15.915C8.8425 16.0275 9.1575 16.0275 9.3525 15.915C10.8075 15.12 13.32 14.2875 14.955 14.07L15.195 14.04C15.915 13.95 16.5 13.275 16.5 12.555Z" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M9 4.1175V15.3675" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                </svg>
                                <span>{{ get_phrase('Meus cursos') }}</span>
                            </a>

                            <a href="{{ route('purchase.history') }}" class="profile-link">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M7.125 10.3125C7.125 11.04 7.6875 11.625 8.3775 11.625H9.7875C10.3875 11.625 10.875 11.115 10.875 10.4775C10.875 9.795 10.575 9.5475 10.1325 9.39L7.875 8.6025C7.4325 8.4525 7.1325 8.205 7.1325 7.515C7.1325 6.885 7.62 6.375 8.22 6.375H9.63C10.32 6.375 10.8825 6.96 10.8825 7.6875" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M9 5.625V12.375" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M16.5 9C16.5 13.14 13.14 16.5 9 16.5C4.86 16.5 1.5 13.14 1.5 9C1.5 4.86 4.86 1.5 9 1.5" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M16.5 4.5V1.5H13.5" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M12.75 5.25L16.5 1.5" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                </svg>
                                <span>{{ get_phrase('Histórico de compras') }}</span>
                            </a>

                            <a href="javascript:void(0);" class="profile-link">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M14.8425 11.1975C13.2975 12.735 11.085 13.2075 9.1425 12.6L5.61 16.125C5.355 16.3875 4.8525 16.545 4.4925 16.4925L2.8575 16.2675C2.3175 16.1925 1.815 15.6825 1.7325 15.1425L1.5075 13.5075C1.455 13.1475 1.62 12.645 1.875 12.39L5.4 8.865C4.8 6.915 5.265 4.7025 6.81 3.165C9.0225 0.9525 12.615 0.9525 14.835 3.165C17.055 5.3775 17.055 8.985 14.8425 11.1975Z" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M5.1675 13.1175L6.8925 14.8425" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M10.875 8.25C11.4963 8.25 12 7.74632 12 7.125C12 6.50368 11.4963 6 10.875 6C10.2537 6 9.75 6.50368 9.75 7.125C9.75 7.74632 10.2537 8.25 10.875 8.25Z" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                </svg>
                                <span>{{ get_phrase('Alterar senha') }}</span>
                            </a>
                            @endif

                            <a href="{{ route('logout') }}" class="profile-link">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M6.675 5.67C6.9075 3.27 8.295 2.1675 11.3325 2.1675H11.43C14.7825 2.1675 16.125 3.51 16.125 6.8625V11.1525C16.125 14.505 14.7825 15.8475 11.43 15.8475H11.3325C8.3175 15.8475 6.93 14.76 6.6825 12.405" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M11.25 9H2.7075" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                    <path d="M4.3875 6.4875L1.875 9L4.3875 11.5125" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" />
                                </svg>
                                <span>{{ get_phrase('Sair') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="btn-primary">{{ get_phrase('Entrar') }}</a>
                @endif

                {{-- Mobile Menu Toggle --}}
                <button class="icon-btn mobile-only menu-toggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M3 7H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        <path d="M3 17H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Mobile Search Overlay --}}
    <div class="mobile-search-overlay">
        <div class="container">
            <form action="{{ route('courses') }}" method="get" class="mobile-search-form">
                <div class="search-box">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" class="search-icon">
                        <path d="M8.5 15.5C12.366 15.5 15.5 12.366 15.5 8.5C15.5 4.63401 12.366 1.5 8.5 1.5C4.63401 1.5 1.5 4.63401 1.5 8.5C1.5 12.366 4.63401 15.5 8.5 15.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M16.5 16.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <input type="search" name="search" placeholder="Digite aqui" class="search-input">
                    <button type="submit" class="search-submit">{{ get_phrase('Buscar') }}</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div class="mobile-menu">
        <div class="mobile-menu-header">
            <a href="{{ route('home') }}">
                <img src="{{ get_image(get_frontend_settings('dark_logo')) }}" alt="system logo" class="logo-img">
            </a>
            <button class="icon-btn menu-close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <div class="mobile-menu-body">

            {{-- Courses Accordion --}}
            <div class="mobile-menu-item">
                <button class="mobile-menu-link accordion-toggle">
                    <span>{{ get_phrase('Cursos') }}</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="mobile-accordion-content">
                    @foreach ($parent_categories->take(10) as $parent_category)
                    @php
                    $child_categories = App\Models\Category::where('parent_id', $parent_category->id);
                    @endphp

                    @if ($child_categories->count() > 0)
                    <div class="mobile-submenu-item">
                        <button class="mobile-submenu-toggle">
                            <i class="{{ $parent_category->icon }}"></i>
                            <span>{{ ucfirst($parent_category->title) }}</span>
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                                <path d="M5 3L9 7L5 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>

                        <div class="mobile-submenu-content">
                            @foreach ($child_categories->get() as $child_category)
                            <a href="{{ route('courses', $child_category->slug) }}" class="mobile-submenu-link">
                                {{ ucfirst($child_category->title) }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <a href="{{ route('courses', $parent_category->slug) }}" class="mobile-submenu-link">
                        <i class="{{ $parent_category->icon }}"></i>
                        <span>{{ ucfirst($parent_category->title) }}</span>
                    </a>
                    @endif
                    @endforeach

                    <a href="{{ route('courses') }}" class="mobile-submenu-link highlight">
                        {{ get_phrase('Cursos') }}
                    </a>
                </div>
            </div>

            {{-- Other Accordion --}}
            <div class="mobile-menu-item">
                <button class="mobile-menu-link accordion-toggle">
                    <span>{{ get_phrase('Outros') }}</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="mobile-accordion-content">
                    <a href="{{ route('free.courses') }}" class="mobile-submenu-link">{{ get_phrase('Aprenda grátis') }}</a>
                    <a href="{{ route('courses') }}" class="mobile-submenu-link">{{ get_phrase('Cursos') }}</a>
                    <a href="{{ route('course.bundles') }}" class="mobile-submenu-link">{{ get_phrase('Pacotes de cursos') }}</a>
                </div>
            </div>

            {{-- Free Learning --}}
            <div class="mobile-menu-item">
                <a href="{{ route('free.courses') }}" class="mobile-menu-link">
                    <span>{{ get_phrase('Aprenda grátis') }}</span>
                </a>
            </div>

            {{-- Language Dropdown --}}
            <div class="mobile-menu-item">
                <button class="mobile-menu-link accordion-toggle">
                    <span>{{ get_phrase('Idioma') }}</span>
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                </button>

                <div class="mobile-accordion-content">
                    <a href="javascript:void(0);" class="mobile-submenu-link">{{ get_phrase('Português') }}</a>
                </div>
            </div>
        </div>
    </div>
</nav>