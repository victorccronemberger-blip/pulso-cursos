$(document).ready(function () {

    'use strict';

    //  Off canvas Menu
    $('.has-menu').on('click', function () {
        $('.droup-menu').toggleClass('off-show');
    });
    // Nice Select
    $('.nice-control').niceSelect();
    //
    $('.user-slider').owlCarousel({
        loop: false,
        autoplay: false,
        margin: 10,
        nav: true,
        navText: ['<i class="fa-solid fa-chevron-left"></i>', '<i class="fa-solid fa-chevron-right"></i>'],
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });


    $('.cook_slider').owlCarousel({
        loop: false,
        autoplay: false,
        margin: 10,
        nav: false,
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            600: {
                items: 3
            },
            1000: {
                items: 2
            }
        }
    });


    // Pricing Range
    $("#slider-range").slider({
        range: true,
        orientation: "horizontal",
        min: 0,
        max: 10000,
        values: [0, 10000],
        step: 100,

        slide: function (event, ui) {
            if (ui.values[0] == ui.values[1]) {
                return false;
            }

            $("#min_price").val(ui.values[0]);
            $("#max_price").val(ui.values[1]);
        }
    });


    $('.gSearch-icon').on('click', function () {
        $('.gSearch-show').toggleClass('active');
    });


});



$(document).ready(function () {
    'use strict';

    var $niceSelect1 = $('.language-dropdown select'),
        $player_1 = $('#player'),
        $player_2 = $('#player2'),
        $counter1 = $('.counter1'),
        $counter2 = $('.counter2'),
        $password = $('.toggle-password'),
        $scale_slider = $('.scale-slider'),
        $counter = $('.counter');

    // Nice Select 
    if ($niceSelect1.length > 0) {
        $($niceSelect1).niceSelect();
    }

    // Player js 
    if ($player_1.length > 0) {
        const player = new Plyr($player_1);
    }
    if ($player_2.length > 0) {
        const player = new Plyr($player_2);
    }

    // Counter Up jquery
    if ($counter1.length > 0) {
        $($counter1).counterUp({
            delay: 10,
            time: 1000,
        });
    }
    // KG Counter 
    if ($counter2.length > 0) {
        $($counter2).counterUp({
            delay: 10,
            time: 1200,
        });
    }

    // Testimonials
    if (typeof $('.your-slider').slick === 'function') {
        $(".slide-show").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            asNavFor: '.slider-nav',
            autoplay: false,
            loop: true,
            fade: false,
            margin: 20,
        });
    }
    if (typeof $('.slider-nav').slick === 'function') {
        $(".slider-nav").slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            asNavFor: '.slide-show',
            dots: false,
            arrows: false,
            variableWidth: true,
            autoplay: false,
            loop: true,
            infinite: true,
            centerMode: true,
            centerPadding: '0',
            focusOnSelect: true,
            autoplaySpeed: 3000, speed: 700,
        });
    }


    // University scale slider plugin
    if ($scale_slider.length > 0) {
        var scaleswiper = new Swiper(".scale-slider", {
            effect: "coverflow",
            // grabCursor: true,
            centeredSlides: true,
            slidesPerView: "1.5",
            loop: true,
            // speed: 2000,
            coverflowEffect: {
                rotate: 0,
                stretch: 100,
                depth: 135,
                modifier: 4,
                slideShadows: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 3000000,
                pauseOnMouseEnter: true,
                disableOnInteraction: false,
            },
            breakpoints: {
                0: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: "1.5",
                    coverflowEffect: {
                        stretch: 21,
                        depth: 200,
                        modifier: 4,
                    },
                },
                768: {
                    slidesPerView: "1.5",
                    coverflowEffect: {
                        stretch: 29,
                        depth: 200,
                        modifier: 4,
                    },
                },
                992: {
                    slidesPerView: "1.5",
                    coverflowEffect: {
                        stretch: 39,
                        depth: 200,
                        modifier: 4,
                    },
                },
                1200: {
                    slidesPerView: "1.5",
                    coverflowEffect: {
                        stretch: 100,
                        depth: 135,
                        modifier: 4,
                    },
                },
                1400: {
                    slidesPerView: "1.5",
                    coverflowEffect: {
                        stretch: 100,
                        depth: 165,
                        modifier: 4,
                    },
                },
            },
        });
    }
});

// New Script 
$(document).ready(function () {
    // Sidebar Accordion Menu 
    $('.side-accordion-title').click(function () {
        $(this).parent().toggleClass("active");
        $(this).siblings(".side-accordion-body").slideToggle();
        $(".side-accordion-item").not($(this).parent()).removeClass("active");
        $(".side-accordion-body").not($(this).siblings()).slideUp();
    });
    // Slider Range 
    if ($('#lms-slider-range').length > 0) {
        $("#lms-slider-range").slider({
            range: true,
            min: 0,
            max: 100,
            values: [1, 50],
            slide: function (event, ui) {
                $(".from-slider-value").val(ui.values[0]);
                $(".from-slider-value2").text(ui.values[0]);
                $(".to-slider-value").val(ui.values[1]);
                $(".to-slider-value2").text(ui.values[1]);
            }
        });
    };

    // Nice Select 
    if ($('.lms-select').length > 0) {
        $('.lms-select').niceSelect();
    };


    // Flatpicker date time
    if ($('.flat-input-picker').length > 0) {
        $(".flat-input-picker").flatpickr({
            enableTime: true,
            dateFormat: "d M Y h:i K",
            onReady(_, __, fp) {
                fp.calendarContainer.classList.add("flat-picker-dropdown");
            },
        });
    };

    // Flatpicker date
    if ($('.flat-date-picker').length > 0) {
        $(".flat-date-picker").flatpickr({
            onReady(_, __, fp) {
                fp.calendarContainer.classList.add("flat-picker-dropdown");
            },
        });
    };
    // Flatpicker time
    if ($('.flat-time-picker').length > 0) {
        $(".flat-time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            onReady(_, __, fp) {
                fp.calendarContainer.classList.add("flat-picker-dropdown");
            },
        });
    };

    // Swiper 
    if ($('.date-slider').length > 0) {
        var dateswiper = new Swiper('.date-slider', {
            slidesPerView: 'auto',
            spaceBetween: 23,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    };

    if ($('.lms-example').length > 0) {

    };


})


// Accordion Menu 
if (screen.width < 992) {
    function accordion() {
        var Accordion = function (el, multiple) {
            this.el = el || {};
            this.multiple = multiple || false;
            var links = this.el.find('.menu-item-has-children > a');
            links.on('click', { el: this.el, multiple: this.multiple }, this.dropdown)
        }

        Accordion.prototype.dropdown = function (e) {
            var $el = e.data.el,
                $this = $(this),
                $next = $this.next();

            $next.slideToggle();
            $this.parent().toggleClass('active-submenu');

            if (!e.data.multiple) {
                $el.find('.menu-dropdown').not($next).slideUp().parent().removeClass('active-submenu');
                $el.find('.menu-dropdown').not($next).slideUp();
            };
        }
        var accordion = new Accordion($('.accordion-menu'), false);
    }
    accordion();
}
// Accordion Menu


// Elegant Page Testimonial 
if (typeof Swiper !== 'undefined') {
    var swiper1 = new Swiper('.elegant-testimonial-1', {
        slidesPerView: 1,
        centeredSlides: true,
        loop: true,
        spaceBetween: 21,
        keyboard: true,
        breakpoints: {
            451: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1.5,
            },
            768: {
                slidesPerView: 2.5,
            },
            991: {
                slidesPerView: 3,
            },
            1200: {
                slidesPerView: 4,
            },
        },
    });

    if ($('.meditation-testimonial-1').length > 0) {
        var swiper1 = new Swiper('.meditation-testimonial-1', {
            slidesPerView: 1,
            centeredSlides: true,
            loop: true,
            spaceBetween: 21,
            keyboard: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            // And if we need scrollbar
            scrollbar: {
                el: '.swiper-scrollbar',
            },
            breakpoints: {
                451: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 1.5,
                },
                768: {
                    slidesPerView: 2.5,
                },
                991: {
                    slidesPerView: 3,
                },
                1200: {
                    slidesPerView: 4,
                },
            },
        });
    }



    var swiper2 = new Swiper('.elegant-testimonial-2', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 21,
        centeredSlides: true,
        centeredSlidesBounds: true,
        keyboard: true,
        breakpoints: {
            451: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1.5,
            },
            768: {
                slidesPerView: 2.7,
            },
            991: {
                slidesPerView: 3.2,
            },
            1200: {
                slidesPerView: 3.6,
            },
        },
    });


    var swiper3 = new Swiper(".floor-plans-slider", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        keyboard: true,
        slidesPerView: 2,
        spaceBetween: 28,
        loop: true,
        breakpoints: {
            451: {
                slidesPerView: 3,
            },
            576: {
                slidesPerView: 4,
            },
            768: {
                slidesPerView: 5,
            },
            991: {
                slidesPerView: 4,
            },
            1200: {
                slidesPerView: 5,
            },
        },
    });


    var swiper4 = new Swiper(".dev-student-swiper", {
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        keyboard: true,
        slidesPerView: 1,
        spaceBetween: 20,
        breakpoints: {
            768: {
                slidesPerView: 1,
            },
            991: {
                slidesPerView: 2,
            },
        },
    });




    // Elegant Page Testimonial 
    var swiper5 = new Swiper('.lms-testimonial-1', {
        slidesPerView: 1,
        loop: false,
        spaceBetween: 28,
        keyboard: true,
        navigation: {
            prevEl: ".swiper-button-prev",
            nextEl: ".swiper-button-next",
        },
        breakpoints: {
            451: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
        },
    });

    // Marketplace Banner 
    var swiper6 = new Swiper(".banner-swiper-1", {
        loop: true,
        keyboard: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });

    // Marketplace Page Testimonial 
    var swiper5 = new Swiper('.lms-testimonial-2', {
        slidesPerView: 1,
        loop: true,
        spaceBetween: 28,
        keyboard: true,
        autoplay: true,
        breakpoints: {
            451: {
                slidesPerView: 1,
            },
            576: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            992: {
                slidesPerView: 3,
            },
        },
    });
}


// navbar

document.addEventListener('DOMContentLoaded', function () {

    // Desktop Dropdowns
    const desktopDropdowns = document.querySelectorAll('.desktop-only .has-dropdown');

    desktopDropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.nav-link');
        const panel = dropdown.querySelector('.dropdown-panel');

        if (!button || !panel) return;

        button.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Close other dropdowns
            desktopDropdowns.forEach(otherDropdown => {
                if (otherDropdown !== dropdown) {
                    otherDropdown.classList.remove('active');
                }
            });

            dropdown.classList.toggle('active');
        });
    });

    // Dropdown Items with Sub-menus
    const dropdownItems = document.querySelectorAll('.dropdown-item-wrap');

    dropdownItems.forEach(item => {
        const link = item.querySelector('.dropdown-link');
        const subDropdown = item.querySelector('.sub-dropdown');

        if (!link || !subDropdown) return;

        link.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Close other items
            dropdownItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('active');
                }
            });

            item.classList.toggle('active');
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.has-dropdown')) {
            desktopDropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });
        }

        if (!e.target.closest('.dropdown-item-wrap')) {
            dropdownItems.forEach(item => {
                item.classList.remove('active');
            });
        }
    });

    // Mobile Menu
    const menuToggle = document.querySelector('.menu-toggle');
    const menuClose = document.querySelector('.menu-close');
    const mobileMenu = document.querySelector('.mobile-menu');
    const body = document.body;
    const html = document.documentElement;
    let scrollPosition = 0;

    function openMobileMenu() {
        // Save current scroll position
        scrollPosition = window.pageYOffset;

        mobileMenu.classList.add('active');
        body.classList.add('menu-open');
        html.classList.add('menu-open');

        // Lock scroll position
        body.style.top = `-${scrollPosition}px`;
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        body.classList.remove('menu-open');
        html.classList.remove('menu-open');

        // Restore scroll position
        body.style.top = '';
        window.scrollTo(0, scrollPosition);

        // Close all accordions when menu closes
        document.querySelectorAll('.mobile-menu-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelectorAll('.mobile-submenu-item').forEach(item => {
            item.classList.remove('active');
        });
    }

    if (menuToggle) {
        menuToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            openMobileMenu();
        });
    }

    if (menuClose) {
        menuClose.addEventListener('click', function (e) {
            e.stopPropagation();
            closeMobileMenu();
        });
    }

    // Close mobile menu when clicking on overlay (outside menu)
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function (e) {
            // Only close if clicking directly on the mobile-menu element (the overlay)
            if (e.target === mobileMenu) {
                closeMobileMenu();
            }
        });
    }

    // Mobile Accordion
    const accordionToggles = document.querySelectorAll('.accordion-toggle');

    accordionToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const menuItem = this.closest('.mobile-menu-item');

            // Close other accordions
            document.querySelectorAll('.mobile-menu-item').forEach(item => {
                if (item !== menuItem) {
                    item.classList.remove('active');
                }
            });

            menuItem.classList.toggle('active');
        });
    });

    // Mobile Submenu Toggles
    const submenuToggles = document.querySelectorAll('.mobile-submenu-toggle');

    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const submenuItem = this.closest('.mobile-submenu-item');

            // Close other submenus in the same parent
            const parent = submenuItem.closest('.mobile-accordion-content');
            if (parent) {
                parent.querySelectorAll('.mobile-submenu-item').forEach(item => {
                    if (item !== submenuItem) {
                        item.classList.remove('active');
                    }
                });
            }

            submenuItem.classList.toggle('active');
        });
    });

    // Search Overlay
    const searchToggle = document.querySelector('.search-toggle');
    const searchOverlay = document.querySelector('.mobile-search-overlay');

    if (searchToggle && searchOverlay) {
        searchToggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            searchOverlay.classList.toggle('active');

            if (searchOverlay.classList.contains('active')) {
                const searchInput = searchOverlay.querySelector('.search-input');
                if (searchInput) {
                    setTimeout(() => searchInput.focus(), 100);
                }
            }
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.mobile-search-overlay') && !e.target.closest('.search-toggle')) {
                searchOverlay.classList.remove('active');
            }
        });
    }

    // Navbar Scroll Effect
    const navbar = document.querySelector('.glass-nav');
    let lastScroll = 0;

    window.addEventListener('scroll', function () {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });

    // Escape Key Handler
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            // Close desktop dropdowns
            desktopDropdowns.forEach(dropdown => {
                dropdown.classList.remove('active');
            });

            dropdownItems.forEach(item => {
                item.classList.remove('active');
            });

            // Close mobile menu
            closeMobileMenu();

            // Close search overlay
            if (searchOverlay) {
                searchOverlay.classList.remove('active');
            }
        }
    });

});