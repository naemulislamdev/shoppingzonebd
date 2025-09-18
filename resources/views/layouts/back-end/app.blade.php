<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ Session::get('direction') }}"
    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <!-- Title -->

    <title>@yield('title')</title>
    <meta name="_token" content="{{ csrf_token() }}">
    <!--to make http ajax request to https-->
    <!--    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">-->
    <!-- Favicon -->
    <link rel="shortcut icon" href="">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400&amp;display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/custom.css">


    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/theme.minc619.css?v=1.0">
    @if (Session::get('direction') === 'rtl')
        <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/menurtl.css">
    @endif
    @stack('css_or_js')
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        :root {
            --theameColor: #045cff;
        }

        .rtl {
            direction: {{ Session::get('direction') }};
        }

        .flex-start {
            display: flex;
            justify-content: flex-start;
        }

        .flex-between {
            display: flex;
            justify-content: space-between;
        }

        .row-reverse {
            display: flex;
            flex-direction: row-reverse;
        }

        .row-center {
            display: flex;
            justify-content: center;
        }

        .select2-results__options {
            text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};
        }

        .scroll-bar {
            max-height: calc(100vh - 100px);
            overflow-y: auto !important;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 1px #cfcfcf;
            /*border-radius: 5px;*/
        }

        ::-webkit-scrollbar {
            width: 3px !important;
            height: 3px !important;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            /*border-radius: 5px;*/
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #003638;
        }

        @media only screen and (max-width: 768px) {

            /* For mobile phones: */
            .map-warper {
                height: 250px;
                padding-bottom: 10px;
            }

        }



        .deco-none {
            color: inherit;
            text-decoration: inherit;
        }

        .qcont:first-letter {
            text-transform: capitalize
        }
    </style>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 23px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #377dff;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #377dff;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        @media only screen and (min-width: 768px) {
            .view-web-site-info {
                display: none;
            }

        }
    </style>
    <script src="{{ asset('assets/back-end') }}/vendor/hs-navbar-vertical-aside/hs-navbar-vertical-aside-mini-cache.js">
    </script>
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/toastr.css">
</head>

<body class="footer-offset">
    <!-- Builder -->
    @include('layouts.back-end.partials._front-settings')
    <!-- End Builder -->
    {{-- loader --}}
    <div class="row">
        <div class="col-12" style="margin-top:10rem;position: fixed;z-index: 9999;">
            <div id="loading" style="display: none;">
                <center>
                    <img width="200"
                        src="{{ asset('storage/company') }}/{{ \App\CPU\Helpers::get_business_settings('loader_gif') }}"
                        onerror="this.src='{{ asset('assets/front-end/img/loader.gif') }}'">
                </center>
            </div>
        </div>
    </div>
    {{-- loader --}}

    <!-- JS Preview mode only -->
    @include('layouts.back-end.partials._header')
    @include('layouts.back-end.partials._side-bar')

    <!-- END ONLY DEV -->

    <main id="content" role="main" class="main pointer-event" style="background-color: #ffffff">
        <!-- Content -->
        @yield('content')
        <!-- End Content -->

        <!-- Footer -->
        @include('layouts.back-end.partials._footer')
        <!-- End Footer -->

        @include('layouts.back-end.partials._modals')

    </main>
    <!-- ========== END MAIN CONTENT ========== -->

    <!-- ========== END SECONDARY CONTENTS ========== -->
    <script src="{{ asset('assets/back-end') }}/js/custom.js"></script>
    <!-- JS Implementing Plugins -->

    {{-- @stack('script') --}}

    <!-- JS Front -->
    <script src="{{ asset('assets/back-end') }}/js/vendor.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/theme.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/sweet_alert.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}
    @if (Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}")
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}")
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info("{{ Session::get('info') }}")
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            toastr.warning("{{ Session::get('warning') }}")
        </script>
    @endif

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif
    <!-- JS Plugins Init. -->
    <script>
        $(document).on('ready', function() {
            // ONLY DEV
            // =======================================================
            if (window.localStorage.getItem('hs-builder-popover') === null) {
                $('#builderPopover').popover('show')
                    .on('shown.bs.popover', function() {
                        $('.popover').last().addClass('popover-dark')
                    });

                $(document).on('click', '#closeBuilderPopover', function() {
                    window.localStorage.setItem('hs-builder-popover', true);
                    $('#builderPopover').popover('dispose');
                });
            } else {
                $('#builderPopover').on('show.bs.popover', function() {
                    return false
                });
            }
            // END ONLY DEV
            // =======================================================

            // BUILDER TOGGLE INVOKER
            // =======================================================
            $('.js-navbar-vertical-aside-toggle-invoker').click(function() {
                $('.js-navbar-vertical-aside-toggle-invoker i').tooltip('hide');
            });

            // INITIALIZATION OF MEGA MENU
            // =======================================================
            /*var megaMenu = new HSMegaMenu($('.js-mega-menu'), {
                desktop: {
                    position: 'left'
                }
            }).init();*/


            // INITIALIZATION OF NAVBAR VERTICAL NAVIGATION
            // =======================================================
            var sidebar = $('.js-navbar-vertical-aside').hsSideNav();


            // INITIALIZATION OF TOOLTIP IN NAVBAR VERTICAL MENU
            // =======================================================
            $('.js-nav-tooltip-link').tooltip({
                boundary: 'window'
            })

            $(".js-nav-tooltip-link").on("show.bs.tooltip", function(e) {
                if (!$("body").hasClass("navbar-vertical-aside-mini-mode")) {
                    return false;
                }
            });


            // INITIALIZATION OF UNFOLD
            // =======================================================
            $('.js-hs-unfold-invoker').each(function() {
                var unfold = new HSUnfold($(this)).init();
            });


            // INITIALIZATION OF FORM SEARCH
            // =======================================================
            $('.js-form-search').each(function() {
                new HSFormSearch($(this)).init()
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function() {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format(
                    'MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function() {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });
        });
    </script>
    <script>
        function openInfoWeb() {
            var x = document.getElementById("website_info");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
    @stack('script')


    <script src="{{ asset('assets/back-end') }}/js/bootstrap.min.js"></script>
    {{-- light box --}}
    <audio id="myAudio">
        <source src="{{ asset('assets/back-end/sound/notification.mp3') }}" type="audio/mpeg">
    </audio>
    <script>
        var audio = document.getElementById("myAudio");

        function playAudio() {
            audio.play();
        }

        function pauseAudio() {
            audio.pause();
        }
    </script>
    <script>
        @if (\App\CPU\Helpers::module_permission_check('order_management'))
            setInterval(function() {
                $.get({
                    url: '{{ route('admin.get-order-data') }}',
                    dataType: 'json',
                    success: function(response) {
                        let data = response.data;
                        if (data.new_order > 0) {
                            playAudio();
                            $('#popup-modal').appendTo("body").modal('show');
                        }
                    },
                });
            }, 10000);
        @endif

        function check_order() {
            location.href = '{{ route('admin.orders.list', ['status' => 'all']) }}';
        }
    </script>
    <script>
        $("#search-bar-input").keyup(function() {
            $("#search-card").css("display", "block");
            let key = $("#search-bar-input").val();
            if (key.length > 0) {
                $.get({
                    url: '{{ url('/') }}/admin/search-function/',
                    dataType: 'json',
                    data: {
                        key: key
                    },
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(data) {
                        $('#search-result-box').empty().html(data.result)
                    },
                    complete: function() {
                        $('#loading').hide();
                    },
                });
            } else {
                $('#search-result-box').empty();
            }
        });

        $(document).mouseup(function(e) {
            var container = $("#search-card");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.hide();
            }
        });

        function form_alert(id, message) {
            Swal.fire({
                title: '{{ \App\CPU\translate('Are you sure') }}?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }
    </script>

    <script>
        function call_demo() {
            toastr.info('{{ \App\CPU\translate('Update option is disabled for demo') }}!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>

    <!-- IE Support -->
    <script>
        if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write(
            '<script src="{{ asset('assets/back-end') }}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
    </script>
    @stack('script_2')

    <!-- ck editor -->

    <!-- ck editor -->

    <script>
        initSample();
    </script>

    <script></script>
    <script>
        function getRndInteger() {
            return Math.floor(Math.random() * 90000) + 100000;
        }
    </script>
     <script>
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.querySelector('.navbar-vertical-content');
        const activeItem = container.querySelector('.nav-item.active');
        const activeItemAside = container.querySelector('.navbar-vertical-aside-has-menu.active');

        if (activeItem) {
            activeItem.scrollIntoView({
                behavior: 'smooth',
                block: 'center', // or 'start' for top
                inline: 'nearest'
            });
        }

        if (activeItemAside) {
            activeItemAside.scrollIntoView({
                behavior: 'smooth',
                block: 'center', // or 'start' for top
                inline: 'nearest'
            });
        }
    });
</script>
<script>
        document.addEventListener('DOMContentLoaded', function() {
                    // Cache all menu items
                    const menuItems = [];
                    const menuLinks = document.querySelectorAll(
                        '.navbar-vertical-aside-has-menu a.nav-link, .navbar-vertical-aside-has-menu a.js-navbar-vertical-aside-menu-link'
                        );

                    menuLinks.forEach(link => {
                        // Skip empty text links
                        const textElement = link.querySelector('.text-truncate');
                        if (!textElement || !textElement.textContent.trim()) return;

                        const iconElement = link.querySelector('i.nav-icon');
                        const iconClass = iconElement ? iconElement.className : 'tio-label-outlined';

                        menuItems.push({
                            text: textElement.textContent.trim(),
                            url: link.getAttribute('href'),
                            icon: iconClass,
                            element: link
                        });
                    });

                    // Create search container
                    const searchContainer = document.createElement('div');
                    searchContainer.className = 'menu-search-container';

                    // Move the search input into the container
                    const searchInputs = document.querySelectorAll('input[name="manue_search"]');
                    const searchInput = searchInputs[searchInputs.length - 1]; // Get the last one
                    searchInput.parentNode.insertBefore(searchContainer, searchInput);
                    searchContainer.appendChild(searchInput);

                    // Create results container
                    const resultsContainer = document.createElement('div');
                    resultsContainer.className = 'menu-search-results';
                    searchContainer.appendChild(resultsContainer);

                    // Search functionality
                    searchInput.addEventListener('input', function(e) {
                            const searchTerm = e.target.value.trim().toLowerCase();
                            resultsContainer.innerHTML = '';

                            if (searchTerm.length < 2) {
                                resultsContainer.style.display = 'none';
                                return;
                            }

                            const filteredItems = menuItems.filter(item =>
                                item.text.toLowerCase().includes(searchTerm))
                                .slice(0, 10);

                                if (filteredItems.length === 0) {
                                    const noResults = document.createElement('div');
                                    noResults.className = 'menu-search-result-item';
                                    noResults.textContent = 'No results found';
                                    resultsContainer.appendChild(noResults);
                                } else {
                                    filteredItems.forEach(item => {
                                        const resultItem = document.createElement('div');
                                        resultItem.className = 'menu-search-result-item';

                                        // Highlight matching text
                                        const startIndex = item.text.toLowerCase().indexOf(searchTerm);
                                        const endIndex = startIndex + searchTerm.length;
                                        const before = item.text.substring(0, startIndex);
                                        const match = item.text.substring(startIndex, endIndex);
                                        const after = item.text.substring(endIndex);

                                        resultItem.innerHTML = `
                    <i class="${item.icon}"></i>
                    <span class="text">
                        ${before}<span class="menu-search-highlight">${match}</span>${after}
                    </span>
                `;

                                        resultItem.addEventListener('click', function() {
                                            // Close any open submenus first
                                            document.querySelectorAll(
                                                '.navbar-vertical-aside-has-menu.show').forEach(
                                                menu => {
                                                    menu.classList.remove('show');
                                                });

                                            // If the item is in a dropdown, open its parent menu
                                            let parentMenu = item.element.closest(
                                                '.navbar-vertical-aside-has-menu');
                                            while (parentMenu) {
                                                parentMenu.classList.add('show');
                                                const toggle = parentMenu.querySelector(
                                                    '.nav-link-toggle');
                                                if (toggle) {
                                                    const submenu = parentMenu.querySelector(
                                                        '.nav-sub');
                                                    if (submenu) submenu.style.display = 'block';
                                                }
                                                parentMenu = parentMenu.parentElement.closest(
                                                    '.navbar-vertical-aside-has-menu');
                                            }

                                            // Navigate to the URL
                                            if (item.url && item.url !== 'javascript:') {
                                                window.location.href = item.url;
                                            }

                                            // Clear search
                                            searchInput.value = '';
                                            resultsContainer.style.display = 'none';
                                        });

                                        resultsContainer.appendChild(resultItem);
                                    });
                                }

                                resultsContainer.style.display = 'block';
                            });

                        // Close results when clicking outside
                        document.addEventListener('click', function(e) {
                            if (!searchContainer.contains(e.target)) {
                                resultsContainer.style.display = 'none';
                            }
                        });

                        // Handle keyboard navigation
                        searchInput.addEventListener('keydown', function(e) {
                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                const firstItem = resultsContainer.querySelector('.menu-search-result-item');
                                if (firstItem) firstItem.focus();
                            }
                        });

                        resultsContainer.addEventListener('keydown', function(e) {
                            const items = resultsContainer.querySelectorAll('.menu-search-result-item');
                            const currentItem = document.activeElement;
                            const currentIndex = Array.from(items).indexOf(currentItem);

                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                if (currentIndex < items.length - 1) {
                                    items[currentIndex + 1].focus();
                                }
                            } else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                if (currentIndex > 0) {
                                    items[currentIndex - 1].focus();
                                } else {
                                    searchInput.focus();
                                }
                            } else if (e.key === 'Enter' && currentItem) {
                                e.preventDefault();
                                currentItem.click();
                            }
                        });
                    });
    </script>
</body>

</html>
