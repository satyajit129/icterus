<!doctype html>
<html lang="en" dir="ltr">

<head>
    @include('backend.global.css_support')
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
                        <div class="page-header">
                            <h1 class="page-title">Dashboard 01</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard 01</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->
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

</body>

</html>
