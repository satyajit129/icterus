@extends('backend.layouts.master')

@section('title', 'Settings')

@section('custom_css')
@endsection
@section('content')
    <div class="page-header">
        <h1 class="page-title">Settings</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Settings</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">General Settings</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminSettingsUpdate') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Website Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="website_name"
                                            placeholder="Website Name"
                                            value="{{ old('website_name', $settings->website_name ?? '') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Website Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="website_email"
                                            placeholder="Website Email"
                                            value="{{ old('website_email', $settings->website_email ?? '') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Copy Right Text</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="copy_right_text"
                                            placeholder="Website Copy Right Text"
                                            value="{{ old('copy_right_text', $settings->copy_right_text ?? '') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Phone Number</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone" placeholder="Phone Number"
                                            value="{{ old('phone', $settings->phone ?? '') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">WhatsApp Number</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="whatsapp"
                                            placeholder="WhatsApp Number"
                                            value="{{ old('whatsapp', $settings->whatsapp ?? '') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Address</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="address" rows="3" placeholder="Business Address">{{ old('address', $settings->address ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Hero Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3">Hero Section Content</h5>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Hero Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="hero_title"
                                            placeholder="Hero Section Title"
                                            value="{{ old('hero_title', $settings->hero_title ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Hero Subtitle</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="hero_subtitle" rows="2" placeholder="Hero Section Subtitle">{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Featured Products Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3">Featured Products Section</h5>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Featured Products Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="featured_products_title"
                                            placeholder="Featured Products Title"
                                            value="{{ old('featured_products_title', $settings->featured_products_title ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Featured Products Subtitle</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="featured_products_subtitle" rows="2"
                                            placeholder="Featured Products Subtitle">{{ old('featured_products_subtitle', $settings->featured_products_subtitle ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Features Section -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3">Features Section</h5>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Fast Delivery Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="fast_delivery_title"
                                            placeholder="Fast Delivery Title"
                                            value="{{ old('fast_delivery_title', $settings->fast_delivery_title ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Fast Delivery Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="fast_delivery_description" rows="2"
                                            placeholder="Fast Delivery Description">{{ old('fast_delivery_description', $settings->fast_delivery_description ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Quality Guarantee Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="quality_guarantee_title"
                                            placeholder="Quality Guarantee Title"
                                            value="{{ old('quality_guarantee_title', $settings->quality_guarantee_title ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Quality Guarantee Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="quality_guarantee_description" rows="2"
                                            placeholder="Quality Guarantee Description">{{ old('quality_guarantee_description', $settings->quality_guarantee_description ?? '') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Support Title</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="support_title"
                                            placeholder="Support Title"
                                            value="{{ old('support_title', $settings->support_title ?? '') }}">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Support Description</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="support_description" rows="2" placeholder="Support Description">{{ old('support_description', $settings->support_description ?? '') }}</textarea>
                                    </div>
                                </div>

                                <!-- Payment Settings -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h5 class="text-primary mb-3">Payment Settings</h5>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Bkash Merchant Number</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="bkash_merchant_number"
                                            placeholder="Bkash Merchant Number (e.g., 01741909808)"
                                            value="{{ old('bkash_merchant_number', $settings->bkash_merchant_number ?? '') }}">
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Logo
                                        @if (isset($settings->logo) && $settings->logo)
                                            <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#logoModal"
                                                title="View Logo">
                                                <span class="badge bg-primary">View</span>
                                            </a>
                                        @endif

                                    </label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="file" name="logo">
                                    </div>
                                </div>

                                <!-- Favicon Upload Field -->
                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">
                                        Favicon
                                        @if (isset($settings->favicon) && $settings->favicon)
                                            <a data-bs-toggle="modal" href="#faviconModal" title="View Favicon">
                                                <span class="badge bg-primary" style="cursor: pointer;">View</span>
                                            </a>
                                        @endif
                                    </label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="file" name="favicon">
                                    </div>
                                </div>

                                <!-- Email Configuration Section -->
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <h4 class="text-primary mb-3">
                                            <i class="fe fe-mail me-2"></i>Email Configuration
                                        </h4>
                                        <p class="text-muted">Configure email settings for sending order approval emails
                                        </p>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Mail Driver</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="mail_mailer">
                                            <option value="smtp"
                                                {{ old('mail_mailer', $settings->mail_mailer ?? 'smtp') == 'smtp' ? 'selected' : '' }}>
                                                SMTP</option>
                                            <option value="sendmail"
                                                {{ old('mail_mailer', $settings->mail_mailer ?? '') == 'sendmail' ? 'selected' : '' }}>
                                                Sendmail</option>
                                            <option value="mailgun"
                                                {{ old('mail_mailer', $settings->mail_mailer ?? '') == 'mailgun' ? 'selected' : '' }}>
                                                Mailgun</option>
                                            <option value="ses"
                                                {{ old('mail_mailer', $settings->mail_mailer ?? '') == 'ses' ? 'selected' : '' }}>
                                                Amazon SES</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">SMTP Host</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mail_host"
                                            placeholder="smtp.gmail.com or your cPanel mail server"
                                            value="{{ old('mail_host', $settings->mail_host ?? '') }}">
                                        <small class="text-muted">e.g., smtp.gmail.com, mail.yourdomain.com</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">SMTP Port</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mail_port" placeholder="587"
                                            value="{{ old('mail_port', $settings->mail_port ?? '587') }}">
                                        <small class="text-muted">Common ports: 587 (TLS), 465 (SSL), 25
                                            (Non-encrypted)</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">SMTP Username</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mail_username"
                                            placeholder="your-email@domain.com"
                                            value="{{ old('mail_username', $settings->mail_username ?? '') }}">
                                        <small class="text-muted">Your email address or cPanel email account</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">SMTP Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="mail_password"
                                            placeholder="Your email password or app password"
                                            value="{{ old('mail_password', $settings->mail_password ?? '') }}">
                                        <small class="text-muted">Use app password for Gmail, regular password for
                                            cPanel</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">Encryption</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="mail_encryption">
                                            <option value="tls"
                                                {{ old('mail_encryption', $settings->mail_encryption ?? 'tls') == 'tls' ? 'selected' : '' }}>
                                                TLS</option>
                                            <option value="ssl"
                                                {{ old('mail_encryption', $settings->mail_encryption ?? '') == 'ssl' ? 'selected' : '' }}>
                                                SSL</option>
                                            <option value=""
                                                {{ old('mail_encryption', $settings->mail_encryption ?? '') == '' ? 'selected' : '' }}>
                                                None</option>
                                        </select>
                                        <small class="text-muted">TLS for port 587, SSL for port 465</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">From Email Address</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="mail_from_address"
                                            placeholder="noreply@yourdomain.com"
                                            value="{{ old('mail_from_address', $settings->mail_from_address ?? '') }}">
                                        <small class="text-muted">Email address that will appear as sender</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label">From Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mail_from_name"
                                            placeholder="Your Company Name"
                                            value="{{ old('mail_from_name', $settings->mail_from_name ?? '') }}">
                                        <small class="text-muted">Name that will appear as sender</small>
                                    </div>
                                </div>

                                {{-- <div class="row mb-4">
                                    <label class="col-md-3 form-label">Test Email</label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <input type="email" class="form-control" id="testEmail"
                                                placeholder="Enter email to test configuration">
                                            <button type="button" class="btn btn-outline-primary" id="sendTestEmail">
                                                <i class="fe fe-send me-2"></i>Send Test Email
                                            </button>
                                        </div>
                                        <small class="text-muted">Test your email configuration before saving</small>
                                    </div>
                                </div> --}}

                                <div class="row mb-4">
                                    <label class="col-md-3 form-label"></label>
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-dark float-end">
                                            <i class="fe fe-upload me-2"></i>Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Logo Modal -->
    @if (isset($settings->logo) && $settings->logo)
        <div class="modal effect-scale" id="logoModal" tabindex="-1" role="dialog" aria-labelledby="logoModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title" id="logoModalLabel">Logo Preview</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('uploads/' . $settings->logo) }}" alt="Logo" class="img-fluid"
                            style="max-width: 100%; height: auto;">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Favicon Modal -->
    @if (isset($settings->favicon) && $settings->favicon)
        <div class="modal effect-scale" id="faviconModal" tabindex="-1" role="dialog"
            aria-labelledby="faviconModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered text-center" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title" id="faviconModalLabel">Favicon Preview</h6>
                        <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('uploads/' . $settings->favicon) }}" alt="Favicon" class="img-fluid"
                            style="max-width: 100px;">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@section('custom_js')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
    <!-- jQuery (required for toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Toastr
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Test email functionality
            $('#sendTestEmail').click(function() {
                const testEmail = $('#testEmail').val().trim();

                if (!testEmail) {
                    toastr.error('Please enter an email address to test', 'Validation Error');
                    return;
                }

                if (!isValidEmail(testEmail)) {
                    toastr.error('Please enter a valid email address', 'Validation Error');
                    return;
                }

                const button = $(this);
                const originalText = button.html();

                // Show loading
                button.html('<span class="spinner-border spinner-border-sm me-2"></span>Sending...');
                button.prop('disabled', true);

                // Send test email
                $.ajax({
                    url: '{{ route('adminSettingsTestEmail') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        test_email: testEmail
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Test email sent successfully! Check your inbox.',
                                'Success');
                        } else {
                            toastr.error(response.message || 'Failed to send test email',
                                'Error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to send test email';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage, 'Error');
                    },
                    complete: function() {
                        // Reset button
                        button.html(originalText);
                        button.prop('disabled', false);
                    }
                });
            });

            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
@endsection
