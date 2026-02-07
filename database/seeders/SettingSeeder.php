<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'app_name', 'value' => 'PulsaPro', 'group' => 'general'],
            ['key' => 'app_tagline', 'value' => 'Beli Pulsa, Paket Data, Token PLN & Game Murah', 'group' => 'general'],
            ['key' => 'app_description', 'value' => 'Langsung pilih produk dan bayar. Tidak perlu daftar akun. Tersedia pulsa semua operator, paket data, token PLN, voucher game, dan e-wallet.', 'group' => 'general'],
            ['key' => 'app_logo_url', 'value' => '', 'group' => 'general'],
            ['key' => 'app_favicon_url', 'value' => '', 'group' => 'general'],

            // SEO
            ['key' => 'seo_title', 'value' => 'PulsaPro - Beli Pulsa, Paket Data, Token PLN & Game Murah', 'group' => 'seo'],
            ['key' => 'seo_description', 'value' => 'Beli pulsa semua operator, paket data, token PLN, voucher game, dan e-wallet dengan harga termurah. Proses instan, tanpa daftar.', 'group' => 'seo'],
            ['key' => 'seo_keywords', 'value' => 'pulsa murah, paket data, token pln, voucher game, e-wallet, isi ulang, ppob', 'group' => 'seo'],
            ['key' => 'seo_og_image', 'value' => '', 'group' => 'seo'],

            // Contact
            ['key' => 'contact_whatsapp', 'value' => '6281234567890', 'group' => 'contact'],
            ['key' => 'contact_email', 'value' => 'support@pulsapro.com', 'group' => 'contact'],
            ['key' => 'contact_telegram', 'value' => '', 'group' => 'contact'],

            // Payment method toggle
            ['key' => 'payment_manual_enabled', 'value' => '1', 'group' => 'payment'],
            ['key' => 'payment_gateway_enabled', 'value' => '0', 'group' => 'payment'],

            // Payment Gateway - active driver
            ['key' => 'pg_active_driver', 'value' => 'tripay', 'group' => 'payment_gateway'],

            // Tripay
            ['key' => 'pg_tripay_mode', 'value' => 'sandbox', 'group' => 'payment_gateway'],
            ['key' => 'pg_tripay_merchant_code', 'value' => '', 'group' => 'payment_gateway'],
            ['key' => 'pg_tripay_api_key', 'value' => '', 'group' => 'payment_gateway'],
            ['key' => 'pg_tripay_private_key', 'value' => '', 'group' => 'payment_gateway'],

            // Xendit
            ['key' => 'pg_xendit_secret_key', 'value' => '', 'group' => 'payment_gateway'],
            ['key' => 'pg_xendit_callback_token', 'value' => '', 'group' => 'payment_gateway'],

            // Duitku
            ['key' => 'pg_duitku_mode', 'value' => 'sandbox', 'group' => 'payment_gateway'],
            ['key' => 'pg_duitku_merchant_code', 'value' => '', 'group' => 'payment_gateway'],
            ['key' => 'pg_duitku_api_key', 'value' => '', 'group' => 'payment_gateway'],

            // DigiFlazz Seller API
            ['key' => 'digiflazz_username', 'value' => '', 'group' => 'digiflazz'],
            ['key' => 'digiflazz_api_key', 'value' => '', 'group' => 'digiflazz'],
            ['key' => 'digiflazz_webhook_secret', 'value' => '', 'group' => 'digiflazz'],

            // Profit
            ['key' => 'profit_margin_percent', 'value' => '5', 'group' => 'profit'],
            ['key' => 'profit_margin_flat', 'value' => '500', 'group' => 'profit'],
            ['key' => 'profit_mode', 'value' => 'percent', 'group' => 'profit'],

            // Appearance
            ['key' => 'primary_color', 'value' => '#4f46e5', 'group' => 'appearance'],
            ['key' => 'hero_badge_text', 'value' => 'Bayar Tagihan & Isi Ulang', 'group' => 'appearance'],
            ['key' => 'hero_title', 'value' => 'Isi Pulsa & Bayar Tagihan', 'group' => 'appearance'],
            ['key' => 'hero_subtitle', 'value' => 'Cepat, Murah, Tanpa Ribet', 'group' => 'appearance'],
            ['key' => 'cta_title', 'value' => 'Mau Harga Lebih Murah?', 'group' => 'appearance'],
            ['key' => 'cta_description', 'value' => 'Daftar sebagai member untuk mendapatkan harga reseller, saldo system, dan komisi referral.', 'group' => 'appearance'],
            ['key' => 'footer_text', 'value' => '', 'group' => 'appearance'],
        ];

        foreach ($settings as $s) {
            Setting::create($s);
        }
    }
}
