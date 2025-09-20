<?php

namespace App\Services;

use App\Mail\OrderApprovalMail;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    /**
     * Configure mail settings from database
     */
    public function configureMailSettings(): void
    {
        $settings = Setting::first();

        if ($settings) {
            Config::set('mail.mailers.smtp.host', $settings->mail_host ?? config('mail.mailers.smtp.host'));
            Config::set('mail.mailers.smtp.port', $settings->mail_port ?? config('mail.mailers.smtp.port'));
            Config::set('mail.mailers.smtp.username', $settings->mail_username ?? config('mail.mailers.smtp.username'));
            Config::set('mail.mailers.smtp.password', $settings->mail_password ?? config('mail.mailers.smtp.password'));
            Config::set('mail.mailers.smtp.encryption', $settings->mail_encryption ?? config('mail.mailers.smtp.encryption'));
            Config::set('mail.from.address', $settings->mail_from_address ?? config('mail.from.address'));
            Config::set('mail.from.name', $settings->mail_from_name ?? config('mail.from.name'));
            Config::set('mail.default', $settings->mail_mailer ?? config('mail.default'));
        }
    }

    /**
     * Send order approval email
     */
    public function sendOrderApprovalEmail(Order $order): bool
    {
        try {
            // Configure mail settings
            $this->configureMailSettings();

            // Get product and settings
            $product = $order->product;
            $settings = Setting::first();

            if (!$product) {
                Log::error('Product not found for order', ['order_id' => $order->id]);
                return false;
            }

            if (!$settings) {
                Log::error('Settings not found for email sending', ['order_id' => $order->id]);
                return false;
            }

            // Send email
            Mail::to($order->customer_email)->send(new OrderApprovalMail($order, $product, $settings));

            Log::info('Order approval email sent successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_email' => $order->customer_email
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send order approval email', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Test email configuration
     */
    public function testEmailConfiguration(string $testEmail): bool
    {
        try {
            // Configure mail settings
            $this->configureMailSettings();

            $settings = Setting::first();

            Mail::to($testEmail)->send(new \App\Mail\TestEmail($settings));

            Log::info('Test email sent successfully', ['test_email' => $testEmail]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'test_email' => $testEmail,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }
}
