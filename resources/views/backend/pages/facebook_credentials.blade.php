@extends('backend.layouts.master')
@section('title', 'Facebook Credentials')
@section('custom_css')
    <style>
        .password-toggle {
            position: relative;
        }

        .password-toggle .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
        }

        .password-toggle .toggle-password:hover {
            color: #495057;
        }

        .input-group-copy {
            position: relative;
        }

        .copy-btn {
            position: absolute;
            right: 40px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }

        .copy-btn.copied {
            color: #28a745;
        }

        .password-toggle .copy-btn {
            right: 40px;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Facebook Credentials</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('adminDashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Facebook Credentials</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Facebook API Credentials</h3>
                    <div class="card-options">
                        <span class="badge bg-info">Update Only</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('adminFacebookCredentialsUpdate') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">App ID <span class="text-danger">*</span></label>
                                    <div class="input-group-copy">
                                        <input type="text" name="app_id" id="app_id" class="form-control"
                                            value="{{ old('app_id', $facebookCredential->app_id) }}"
                                            placeholder="Enter Facebook App ID">
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('app_id')"
                                            title="Copy App ID">
                                            <i class="fe fe-copy"></i>
                                        </button>
                                    </div>
                                    @error('app_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Agency ID</label>
                                    <div class="input-group-copy">
                                        <input type="text" name="agency_id" id="agency_id" class="form-control"
                                            value="{{ old('agency_id', $facebookCredential->agency_id) }}"
                                            placeholder="Enter Agency ID">
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('agency_id')"
                                            title="Copy Agency ID">
                                            <i class="fe fe-copy"></i>
                                        </button>
                                    </div>
                                    @error('agency_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">App Secret <span class="text-danger">*</span></label>
                                    <div class="password-toggle">
                                        <input type="password" name="app_secret" id="app_secret" class="form-control"
                                            value="{{ old('app_secret', $facebookCredential->app_secret) }}"
                                            placeholder="Enter Facebook App Secret">
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('app_secret')"
                                            title="Copy App Secret">
                                            <i class="fe fe-copy"></i>
                                        </button>
                                        <button type="button" class="toggle-password"
                                            onclick="togglePassword('app_secret')">
                                            <i class="fe fe-eye" id="app_secret_icon"></i>
                                        </button>
                                    </div>
                                    @error('app_secret')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">User Access Token</label>
                                    <div class="password-toggle">
                                        <input type="password" name="user_access_token" id="user_access_token"
                                            class="form-control"
                                            value="{{ old('user_access_token', $facebookCredential->user_access_token) }}"
                                            placeholder="Enter User Access Token" style="padding: 0.475rem 4.75rem 0.475rem 0.75rem">
                                        <button type="button" class="copy-btn"
                                            onclick="copyToClipboard('user_access_token')" title="Copy User Access Token">
                                            <i class="fe fe-copy"></i>
                                        </button>
                                        <button type="button" class="toggle-password"
                                            onclick="togglePassword('user_access_token')">
                                            <i class="fe fe-eye" id="user_access_token_icon"></i>
                                        </button>
                                    </div>
                                    @error('user_access_token')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">API URL</label>
                                    <div class="input-group-copy">
                                        <input type="url" name="api_url" id="api_url" class="form-control"
                                            value="{{ old('api_url', $facebookCredential->api_url) }}"
                                            placeholder="https://graph.facebook.com/v18.0/">
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('api_url')"
                                            title="Copy API URL">
                                            <i class="fe fe-copy"></i>
                                        </button>
                                    </div>
                                    @error('api_url')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Version</label>
                                    <div class="input-group-copy">
                                        <input type="text" name="version" id="version" class="form-control"
                                            value="{{ old('version', $facebookCredential->version) }}"
                                            placeholder="e.g., v18.0">
                                        <button type="button" class="copy-btn" onclick="copyToClipboard('version')"
                                            title="Copy Version">
                                            <i class="fe fe-copy"></i>
                                        </button>
                                    </div>
                                    @error('version')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="alert alert-info">
                                        <i class="fe fe-info"></i>
                                        <strong>Note:</strong> This form only allows updating existing credentials.
                                        App Secret and User Access Token are encrypted and hidden by default for security.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-save"></i> Update Credentials
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fe-eye');
                icon.classList.add('fe-eye-off');
            } else {
                field.type = 'password';
                icon.classList.remove('fe-eye-off');
                icon.classList.add('fe-eye');
            }
        }

        function copyToClipboard(fieldId) {
            const field = document.getElementById(fieldId);
            const copyBtn = field.parentNode.querySelector('.copy-btn');
            const icon = copyBtn.querySelector('i');

            // Select the text in the input field
            field.select();
            field.setSelectionRange(0, 99999); // For mobile devices

            try {
                // Copy the text to clipboard
                document.execCommand('copy');

                // Visual feedback
                icon.classList.remove('fe-copy');
                icon.classList.add('fe-check');
                copyBtn.classList.add('copied');

                // Show toast notification
                showToast('success', 'Copied to clipboard!');

                // Reset after 2 seconds
                setTimeout(() => {
                    icon.classList.remove('fe-check');
                    icon.classList.add('fe-copy');
                    copyBtn.classList.remove('copied');
                }, 2000);

            } catch (err) {
                // Fallback for modern browsers
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(field.value).then(() => {
                        // Visual feedback
                        icon.classList.remove('fe-copy');
                        icon.classList.add('fe-check');
                        copyBtn.classList.add('copied');

                        // Show toast notification
                        showToast('success', 'Copied to clipboard!');

                        // Reset after 2 seconds
                        setTimeout(() => {
                            icon.classList.remove('fe-check');
                            icon.classList.add('fe-copy');
                            copyBtn.classList.remove('copied');
                        }, 2000);
                    }).catch(() => {
                        showToast('error', 'Failed to copy to clipboard');
                    });
                } else {
                    showToast('error', 'Copy not supported in this browser');
                }
            }
        }

        $(document).ready(function() {
            // Auto-fill API URL if empty
            $('#api_url').on('blur', function() {
                if (!$(this).val()) {
                    $(this).val('https://graph.facebook.com/v18.0/');
                }
            });

            // Auto-fill version if empty
            $('#version').on('blur', function() {
                if (!$(this).val()) {
                    $(this).val('v18.0');
                }
            });
        });
    </script>
@endsection
