<!doctype html>
<html lang="en" dir="ltr">

<head>
    @include('backend.global.css_support')
    @yield('custom_css')
</head>

<body class="app sidebar-mini ltr light-mode">

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">

            <!-- app-Header -->
            @include('backend.layouts.header')
            <!-- /app-Header -->

            <!--APP-SIDEBAR-->
            @include('backend.layouts.sidebar')
            <!--/APP-SIDEBAR-->

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">
                        <!-- PAGE-HEADER -->
                        
                        <!-- PAGE-HEADER END -->
                        @yield('content')
                    </div>
                    <!-- CONTAINER END -->
                </div>
            </div>
            <!--app-content close-->
        </div>
        <!-- FOOTER START -->
        @include('backend.layouts.footer')
        <!-- FOOTER END -->
    </div>
    @include('backend.global.js_support')
    @yield('custom_js')

</body>

</html>
