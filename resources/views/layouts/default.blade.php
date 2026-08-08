@php
    $isStudentPortal = auth()->check() && request()->is(
        'my-courses',
        'my-course-bundles*',
        'my-exams*',
        'my-profile',
        'wishlist',
        'purchase-history',
        'invoice/*',
        'message*',
        'support/ticket*',
        'become-an-instructor'
    );
@endphp
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    @include('layouts.seo')
    @stack('meta')

    <!-- fav icon -->
    <link rel="shortcut icon" href="{{ get_image(get_frontend_settings('favicon')) }}" />

    <!-- owl carousel -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/owl.theme.default.min.css') }}">

    <!-- Jquery Ui Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/jquery-ui.css') }}">

    <!-- Nice Select Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/nice-select.css') }}">

    <!-- Fontawasome Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/all.min.css') }}">

    {{-- New Css Link --}}
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/vendors/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/vendors/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/vendors/slick/slick-theme.css') }}">

    <!-- Flat Pickr -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/vendors/flatpickr/flatpickr.min.css') }}">

    <!-- FlatIcons Css -->
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-bold-rounded/css/uicons-bold-rounded.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-regular-rounded/css/uicons-regular-rounded.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/global/icons/uicons-solid-rounded/css/uicons-solid-rounded.css') }}" />

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/custome-front/custom-fronts.css') }}">

    <!-- Player Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/plyr.css') }}">

    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/bootstrap.min.css') }}">

    <!-- Main Css -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/responsive.css') }}">

    <!-- Yaireo Tagify -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/tagify-master/dist/tagify.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom Style -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/custom_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/new_responsive.css') }}">

    @if (Route::currentRouteName() == 'home' || Route::currentRouteName() == 'admin.page.preview')
    <!-- Toro Home Style -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/new_sections.css') }}">
    @endif

    @if (Route::currentRouteName() == 'courses')
    <!-- Courses Page Style -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home_v2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/courses_page.css') }}?v=20260807-2">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/courses_media.css') }}?v=20260807-1">
    @endif

    @if (Route::currentRouteName() == 'course.details')
    <!-- Course Detail Style -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home_v2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/course_detail.css') }}?v=20260807-2">
    @endif

    @if ($isStudentPortal)
    <!-- Student Portal Style -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home_v2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/student_portal.css') }}?v=20260807-6">
    @endif

    @if (Route::currentRouteName() == 'login' || request()->is('login'))
    <!-- Login Page Style -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home_v2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/login_page.css') }}?v=20260807-2">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/login_page_hotfix.css') }}?v=20260807-1">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/auth_viewport.css') }}?v=20260807-1">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/auth_flow.css') }}?v=20260807-1">
    @endif

    @if (Route::currentRouteName() == 'register.form' || request()->is('register'))
    <!-- Register Page Style -->
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/toro_home_v2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/login_page.css') }}?v=20260807-2">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/login_page_hotfix.css') }}?v=20260807-1">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/auth_viewport.css') }}?v=20260807-1">
    <link rel="stylesheet" href="{{ asset('assets/frontend/default/css/auth_flow.css') }}?v=20260807-1">
    @endif

    <!-- Jquery Js -->
    <script src="{{ asset('assets/frontend/default/js/jquery-3.7.1.min.js') }}"></script>
    @stack('css')

</head>

<body class="{{ $isStudentPortal ? 'pf-student-app' : '' }}">
    @php $current_route_name = Route::currentRouteName(); @endphp
    @php
        if (session('home')) {
            $home_page = App\Models\Builder_page::where('id', session('home'))->firstOrNew();
        } else {
            $home_page = App\Models\Builder_page::where('status', 1)->firstOrNew();
        }
    @endphp

    @if (in_array($current_route_name, ['login', 'register.form']) || request()->is('login', 'register'))
        <main class="pf-auth-page">
            @yield('content')
        </main>
    @elseif ($isStudentPortal)
        @include('frontend.default.student.portal_header')
        <main class="pf-portal-main">
            @yield('content')
        </main>
    @elseif ($home_page->is_permanent == 1)
        @include('components.home_made_by_developer.top_bar')
        @include('components.home_made_by_developer.header')
        <section>
            @yield('content')
        </section>
        @include('components.home_made_by_developer.footer')
    @else
    @if (Route::currentRouteName() == 'home' || Route::currentRouteName() == 'admin.page.preview')
            <section>
                @yield('content')
            </section>
        @else
            @php $builder_files = $home_page->html ? json_decode($home_page->html, true) : []; @endphp
            @if (in_array('top_bar', $builder_files))
                @include('components.home_made_by_builder.top_bar')
            @endif

            @if (in_array('header', $builder_files))
                @include('components.home_made_by_builder.header')
            @endif

            <section>
                @yield('content')
            </section>

            @if (in_array('footer', $builder_files))
                @include('components.home_made_by_builder.footer')
            @endif
        @endif
    @endif

    <!-- Bootstrap Js -->
    <script src="{{ asset('assets/frontend/default/js/bootstrap.bundle.min.js') }}"></script>

    <!-- nice select js -->
    <script src="{{ asset('assets/frontend/default/js/jquery.nice-select.min.js') }}"></script>

    {{-- New Js Link  --}}
    <script src="{{ asset('assets/frontend/default/vendors/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/default/vendors/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/default/vendors/counterup/jquery.waypoints.js') }}"></script>
    <script src="{{ asset('assets/frontend/default/vendors/slick/slick.min.js') }}"></script>

    <script src="{{ asset('assets/frontend/default/vendors/flatpickr/flatpickr.min.js') }}"></script>

    <!-- owl carousel js -->
    <script src="{{ asset('assets/frontend/default/js/owl.carousel.min.js') }}"></script>


    <!-- Player Js -->
    <script src="{{ asset('assets/frontend/default/js/plyr.js') }}"></script>


    <!-- Yaireo Tagify -->
    <script src="{{ asset('assets/global/tagify-master/dist/tagify.min.js') }}"></script>


    <!-- Jquery Ui Js -->
    <script src="{{ asset('assets/frontend/default/js/jquery-ui.min.js') }}"></script>


    <!-- price range Js -->
    <script src="{{ asset('assets/frontend/default/js/price_range_script.js') }}"></script>


    <!-- Main Js -->
    <script src="{{ asset('assets/frontend/default/js/script.js') }}"></script>

    @if(get_frontend_settings('cookie_status'))
        @include('frontend.default.cookie')
    @endif

    <!-- End Footer -->
    @include('frontend.default.modal')
    <!-- toster file -->
    @include('frontend.default.toaster')
    <!-- custom scripts -->
    @include('frontend.default.scripts')
    @stack('js')
</body>

</html>
