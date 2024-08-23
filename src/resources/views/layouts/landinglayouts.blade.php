<!DOCTYPE html>

<html lang="en">

<head>

    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">
    <title>@yield('title')</title>

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Event and Conference Html5 Template">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">




    <!-- PLUGINS CSS STYLE -->
    {{-- favicon --}}
    <link rel="shortcut icon" href="">
    <!-- Bootstrap -->
    <link href="{{ asset('Landingpage/plugins/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('Landingpage/plugins/font-awsome/css/font-awesome.min.css') }}" rel="stylesheet">
    <!-- Magnific Popup -->
    <link href="{{ asset('Landingpage/plugins/magnific-popup/magnific-popup.css') }}" rel="stylesheet">
    <!-- Slick Carousel -->
    <link href="{{ asset('Landingpage/plugins/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('Landingpage/plugins/slick/slick-theme.css') }}" rel="stylesheet">
    <!-- CUSTOM CSS -->
    <link href="{{ asset('Landingpage/css/style.css') }}" rel="stylesheet">


    <!-- FAVICON -->

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }

        .footer {
            background-color: #e0e7ef;
            color: #4a4a4a;
            padding: 40px 0;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .footer-logo {
            text-align: left;
        }

        .footer-logo img {
            max-width: 50px;
            margin-bottom: 20px;
        }

        .footer-logo p {
            font-size: 18px;
            margin: 0;
            color: #4a4a4a;
        }

        .footer-column {
            margin: 0 20px;
            text-align: left;
            flex: 1;
            min-width: 200px;
        }

        .footer-column h3 {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
        }

        .footer-column a {
            display: block;
            margin-bottom: 10px;
            color: #4a4a4a;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-column a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 20px;
            border-top: 1px solid #d1d9e0;
            padding-top: 20px;
            font-size: 14px;
            color: #4a4a4a;
        }

        .footer-bottom .social-icons {
            margin-top: 10px;
        }

        .footer-bottom .social-icons img {
            width: 24px;
            margin: 0 5px;
        }
    </style>


</head>

<body class="body-wrapper">


<!--========================================
=            Navigation Section            =
=========================================-->
<nav class=" navbar main-nav border-less fixed-top navbar-expand-lg p-0">
    <div class="container-fluid p-0">

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="#features">Buy
                        {{-- <span>/</span> --}}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Sell
                        {{-- <span>/</span> --}}
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Services
                        {{-- <span>/</span> --}}
                    </a>
                </li>


            </ul>

            <!-- logo -->
            <a class="navbar-brand" href="#">
                <img class="w-100" src="" alt="logo">
            </a>
            <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center  sm:pt-0">
                @if (Route::has('login'))
                    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
                        @auth
                            @if ( Auth::user()->role_id == 1)
                                <a href="{{ url('/admin/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline" id="signing">Home</a>
                            @elseif( Auth::user()->role_id == 2)
                                <a href="{{ url('/user/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline" id="signing">Home</a>
                            @elseif( Auth::user()->role_id == 3)
                                <a href="{{ url('/merchant/dashboard') }}" class="text-sm text-gray-700 dark:text-gray-500 underline" id="signing">Home</a>
                            @else
                                <p>nothing</p>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-500 underline" id="signing">Sign In</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline" id="signup">Sign
                                    Up</a>
                            @endif
                        @endauth
                    </div>
                @endif


            </div>

        </div>
    </div>
</nav>




@yield('content')




<!--============================
=            Footer            =
=============================-->

<div class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="your-logo.png" alt="Logo">
            <p>Design amazing digital experiences that create more happy in the world.</p>
        </div>
        <div class="footer-column">
            <h3>Product</h3>
            <a href="#">Overview</a>
            <a href="#">Features</a>
            <a href="#">Solutions</a>
            <a href="#">Tutorials</a>
            <a href="#">Pricing</a>
            <a href="#">Releases</a>
        </div>
        <div class="footer-column">
            <h3>Company</h3>
            <a href="#">About us</a>
            <a href="#">Careers</a>
            <a href="#">Press</a>
            <a href="#">News</a>
            <a href="#">Media kit</a>
            <a href="#">Contact</a>
        </div>
        <div class="footer-column">
            <h3>Resources</h3>
            <a href="#">Blog</a>
            <a href="#">Newsletter</a>
            <a href="#">Events</a>
            <a href="#">Help centre</a>
            <a href="#">Tutorials</a>
            <a href="#">Support</a>
        </div>
        <div class="footer-column">
            <h3>Social</h3>
            <a href="#">Twitter</a>
            <a href="#">LinkedIn</a>
            <a href="#">Facebook</a>
            <a href="#">GitHub</a>
            <a href="#">AngelList</a>
            <a href="#">Dribbble</a>
        </div>
        <div class="footer-column">
            <h3>Legal</h3>
            <a href="#">Terms</a>
            <a href="#">Privacy</a>
            <a href="#">Cookies</a>
            <a href="#">Licenses</a>
            <a href="#">Settings</a>
            <a href="#">Contact</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Heritage-Nest. All rights reserved.</p>
        <div class="social-icons">
            <a href="#"><img src="twitter-icon.png" alt="Twitter"></a>
            <a href="#"><img src="linkedin-icon.png" alt="LinkedIn"></a>
            <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
            <a href="#"><img src="custom-icon.png" alt="Custom Icon"></a>
        </div>
    </div>
</div>









<!-- JAVASCRIPTS -->
<!-- jQuey -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{ asset('Landingpage/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('Landingpage/plugins/bootstrap/bootstrap.min.js') }}"></script>
<!-- Shuffle -->
<script src="{{ asset('Landingpage/plugins/shuffle/shuffle.min.js') }}"></script>
<!-- Magnific Popup -->
<script src="{{ asset('Landingpage/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<!-- Slick Carousel -->
<script src="{{ asset('Landingpage/plugins/slick/slick.min.js') }}"></script>
<!-- SyoTimer -->
<script src="{{ asset('Landingpage/plugins/syotimer/jquery.syotimer.min.js') }}"></script>
<!-- Google Mapl -->
<script src="{{ asset('https://maps.googleapis.com/maps/api/js?key=AIzaSyCcABaamniA6OL5YvYSpB3pFMNrXwXnLwU') }}">
</script>
<script src="{{ asset('Landingpage/plugins/google-map/gmap.js') }}"></script>
<!-- Custom Script -->
<script src="{{ asset('Landingpage/js/script.js') }}"></script>





</body>

</html>
