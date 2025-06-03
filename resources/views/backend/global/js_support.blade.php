<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/toastr.min.js') }}"></script>

<!-- ✅ Add Perfect Scrollbar BEFORE sidemenu.js -->
<script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>

<script src="{{ asset('js/sidemenu.js') }}"></script>
<script src="{{ asset('js/sticky.js') }}"></script>

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