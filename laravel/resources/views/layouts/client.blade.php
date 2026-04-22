<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('company_name', config('app.name')) }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/common/logo.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome for social icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700&display=swap"
        rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/sass/client-app.scss', 'resources/js/app.js'])
    @stack('css')
</head>

<body>
    <header>
        <x-navbar />
    </header>

    <main id="client-layout" class="px-0 container-fluid">
        @yield('content')
    </main>

    <x-footer />

    <!-- Contact icons (always visible) -->
    <div id="contactBtns">
        <a id="phone-contact" href="tel:{{ setting('company_phone') }}" class="text-dark" title="Gọi điện">
            <img src="{{ asset('images/common/phone-icon.png') }}" alt="Phone">
        </a>
        <a id="zalo-contact" href="{{ setting('company_zalo') }}" target="_blank" class="text-dark" title="Zalo">
            <!-- Zalo SVG icon -->
            <img src="{{ asset('images/common/zalo-icon.png') }}" alt="Zalo">
        </a>
    </div>

    <!-- Scroll to top button (shows on scroll) -->
    <div id="toTopBtns" style="position:fixed;bottom:100px;right:32px;z-index:1040">
        <a href="#" id="scrollToTopBtn">
            <i class="fas fa-arrow-up fa-lg text-primary"></i>
        </a>
    </div>
</body>


<!-- Bootstrap 4 JS with jQuery popper -->
<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

<script>
    // Show/hide scroll-to-top button only
    document.addEventListener('DOMContentLoaded', function() {
        const scrollBtn = document.getElementById('scrollToTopBtn');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 600) {
                scrollBtn.style.display = 'flex';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        scrollBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Only allow main dropdown toggle on mobile/tablet
        const mainToggle = document.querySelector('.nav-item.dropdown > span > .dropdown-toggle');
        if (mainToggle) {
            mainToggle.addEventListener('click', function(e) {
                if (window.innerWidth < 992) {
                    e.preventDefault();
                    e.stopPropagation();
                    const menu = this.closest('.nav-item.dropdown')
                        .querySelector('.dropdown-menu');
                    const isOpen = menu.classList.contains('show');
                    if (isOpen) {
                        menu.classList.remove('show');
                        this.classList.remove('show');
                        this.setAttribute('aria-expanded', 'false');
                    } else {
                        menu.classList.add('show');
                        this.classList.add('show');
                        this.setAttribute('aria-expanded', 'true');
                    }
                }
            });
        }
        // // Close menu when clicking a menu item to navigate (mobile)
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                if (e.target.closest('.dropdown-menu .dropdown-item')) {
                    // Always close menu and go to link for 'xem thêm'
                    if (!e.target.classList.contains('dropdown-toggle')) {
                        if (mainToggle) {
                            const menu = mainToggle.parentElement.querySelector('.dropdown-menu');
                            if (menu && menu.classList.contains('show')) {
                                menu.classList.remove('show');
                                mainToggle.classList.remove('show');
                                mainToggle.setAttribute('aria-expanded', 'false');
                            }
                        }
                    }
                }
            }
        });

        // On desktop, flip submenus left/up if they overflow viewport bounds
        const submenus = document.querySelectorAll('.dropdown-submenu');
        const setSubmenuDirection = function(item) {
            if (window.innerWidth < 992) {
                item.classList.remove('open-left', 'open-right', 'open-up', 'open-down');
                return;
            }

            const submenu = item.querySelector(':scope > .dropdown-menu');
            if (!submenu) return;

            item.classList.remove('open-left', 'open-right', 'open-up', 'open-down');
            item.classList.add('open-right', 'open-down');

            const previousDisplay = submenu.style.display;
            const previousVisibility = submenu.style.visibility;
            submenu.style.visibility = 'hidden';
            submenu.style.display = 'block';

            const submenuWidth = submenu.offsetWidth;
            const submenuHeight = submenu.offsetHeight;
            const rect = item.getBoundingClientRect();
            const spaceRight = window.innerWidth - rect.right;
            const spaceLeft = rect.left;
            const spaceBottom = window.innerHeight - rect.top;
            const spaceTop = rect.bottom;

            if (spaceRight < submenuWidth && spaceLeft > submenuWidth) {
                item.classList.remove('open-right');
                item.classList.add('open-left');
            }

            if (spaceBottom < submenuHeight && spaceTop > submenuHeight) {
                item.classList.remove('open-down');
                item.classList.add('open-up');
            }

            submenu.style.display = previousDisplay;
            submenu.style.visibility = previousVisibility;
        };

        submenus.forEach(function(item) {
            item.addEventListener('mouseenter', function() {
                setSubmenuDirection(item);
                const parentMenu = item.closest('.dropdown-menu');
                if (parentMenu) {
                    parentMenu.classList.add('submenu-blur-active');
                }
                item.classList.add('submenu-active');
            });
            item.addEventListener('mouseleave', function() {
                const parentMenu = item.closest('.dropdown-menu');
                if (parentMenu) {
                    parentMenu.classList.remove('submenu-blur-active');
                }
                item.classList.remove('submenu-active');
            });
            item.addEventListener('focusin', function() {
                setSubmenuDirection(item);
                const parentMenu = item.closest('.dropdown-menu');
                if (parentMenu) {
                    parentMenu.classList.add('submenu-blur-active');
                }
                item.classList.add('submenu-active');
            });
            item.addEventListener('focusout', function() {
                const parentMenu = item.closest('.dropdown-menu');
                if (!parentMenu) return;
                setTimeout(function() {
                    if (!item.contains(document.activeElement)) {
                        parentMenu.classList.remove('submenu-blur-active');
                        item.classList.remove('submenu-active');
                    }
                }, 0);
            });
        });

        window.addEventListener('resize', function() {
            submenus.forEach(function(item) {
                setSubmenuDirection(item);
                if (window.innerWidth < 992) {
                    item.classList.remove('submenu-active');
                    const parentMenu = item.closest('.dropdown-menu');
                    if (parentMenu) {
                        parentMenu.classList.remove('submenu-blur-active');
                    }
                }
            });
        });
    });
</script>

<script>
    // Fade-in-up animation on scroll with per-row staggered delay
    document.addEventListener('DOMContentLoaded', function() {
        var observer = new IntersectionObserver(function(entries) {
            // Group newly visible items by their parent row
            var groups = {};
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var parent = entry.target.parentElement;
                    if (!groups[parent]) groups[parent] = [];
                    groups[parent].push(entry.target);
                    observer.unobserve(entry.target);
                }
            });
            // Apply staggered delay within each row group
            Object.keys(groups).forEach(function(key) {
                var items = groups[key];
                items.forEach(function(el, i) {
                    el.style.animationDelay = (i * 0.1) + 's';
                    el.classList.add('visible');
                });
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in-up, .fade-in-left, .fade-in-right').forEach(function(el) {
            observer.observe(el);
        });
    });
</script>

@stack('scripts')

</html>
