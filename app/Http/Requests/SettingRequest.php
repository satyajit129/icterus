<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'website_name' => 'required|string',
            'website_email' => 'required|email',
            'copy_right_text' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'hero_title' => 'nullable|string|max:500',
            'hero_subtitle' => 'nullable|string|max:1000',
            'featured_products_title' => 'nullable|string|max:500',
            'featured_products_subtitle' => 'nullable|string|max:1000',
            'fast_delivery_title' => 'nullable|string|max:200',
            'fast_delivery_description' => 'nullable|string|max:500',
            'quality_guarantee_title' => 'nullable|string|max:200',
            'quality_guarantee_description' => 'nullable|string|max:500',
            'support_title' => 'nullable|string|max:200',
            'support_description' => 'nullable|string|max:500',
            'bkash_merchant_number' => 'nullable|string|max:20',
            'mail_mailer' => 'nullable|string|in:smtp,sendmail,mailgun,ses',
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ];
    }

}
