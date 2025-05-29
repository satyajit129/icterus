<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sash – Bootstrap 5  Admin & Dashboard Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">
    <title>Sash – Bootstrap 5 Admin & Dashboard Template</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/toastr.min.css') }}">

</head>

<body class="app sidebar-mini ltr login-img">

    <div class="">
        <!-- PAGE -->
        <div class="page">
            <div class="">
                <!-- Theme-Layout -->

                <!-- CONTAINER OPEN -->
                {{-- <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <a href="index.html"><img style="width: 100px; height: auto;" src="{{ asset('image/logo.png') }}" class="header-brand-img img-fluid" alt=""></a>
                    </div>
                </div> --}}

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <form action="{{ route('adminLoginRequest') }}" class="login100-form validate-form" accept="multipart/form-data" method="POST">
                            @csrf
                            <span class="login100-form-title pb-5">
                                Admin Login
                            </span>
                            <div class="panel panel-primary">
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs justify-content-center">
                                            <li class="mx-0"><a href="#tab5" class="active" data-bs-toggle="tab">Enter Your Login Credentials Here</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body p-0 pt-5">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 border-start-0 form-control ms-0" type="email" name="email" placeholder="Email" autocomplete="off" required>
                                            </div>
                                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted toggle-password">
                                                    <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 border-start-0 form-control ms-0" type="password" name="password" placeholder="Password" autocomplete= "off" required>
                                            </div>

                                            <div class="text-end pt-4">
                                                <p class="mb-0"><a href="forgot-password.html" class="text-primary ms-1">Forgot Password?</a></p>
                                            </div>
                                            <div class="container-login100-form-btn">
                                                <button type="submit" class="login100-form-btn btn-primary">
                                                        Login
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <!-- CONTAINER CLOSED -->
            </div>
        </div>
        <!-- End PAGE -->

    </div>
    <!-- BACKGROUND-IMAGE CLOSED -->

    <!-- JQUERY JS -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.toggle-password').on('click', function () {
                var $wrapper = $(this).closest('.input-group');
                var $input = $wrapper.find('input');
                var $icon = $(this).find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('zmdi-eye').addClass('zmdi-eye-off');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('zmdi-eye-off').addClass('zmdi-eye');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            @if(session('success'))
                showToast('success', "{{ session('success') }}");
            @endif

            @if(session('error'))
                showToast('error', "{{ session('error') }}");
            @endif

            @if(session('warning'))
                showToast('warning', "{{ session('warning') }}");
            @endif
        });

        function showToast(type, message) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000"
            };

            toastr[type](message);
        }
    </script>


</body>

</html>