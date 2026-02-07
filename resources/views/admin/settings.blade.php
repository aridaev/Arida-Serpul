@extends('layouts.admin')
@section('title', 'Settings - Admin Pulsa Pro')
@section('header', 'Settings')

@section('content')
<form method="POST" action="{{ route('admin.update-settings') }}" enctype="multipart/form-data" class="max-w-4xl space-y-6">
    @csrf

    {{-- General --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">General</h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ $settings['general']->firstWhere('key','app_name')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tagline</label>
                    <input type="text" name="app_tagline" value="{{ $settings['general']->firstWhere('key','app_tagline')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Deskripsi Aplikasi</label>
                <textarea name="app_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">{{ $settings['general']->firstWhere('key','app_description')->value ?? '' }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Logo (upload)</label>
                    @if(!empty($settings['general']->firstWhere('key','app_logo_url')->value))
                    <div class="mb-2"><img src="{{ $settings['general']->firstWhere('key','app_logo_url')->value }}" class="h-8"></div>
                    @endif
                    <input type="file" name="app_logo_file" accept=".png,.jpg,.jpeg,.svg,.webp" class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-3 file:border-0 file:bg-gray-100 file:text-gray-700 file:text-xs">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Favicon (upload)</label>
                    @if(!empty($settings['general']->firstWhere('key','app_favicon_url')->value))
                    <div class="mb-2"><img src="{{ $settings['general']->firstWhere('key','app_favicon_url')->value }}" class="h-6"></div>
                    @endif
                    <input type="file" name="app_favicon_file" accept=".png,.ico,.svg" class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-3 file:border-0 file:bg-gray-100 file:text-gray-700 file:text-xs">
                </div>
            </div>
        </div>
    </div>

    {{-- SEO --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">SEO</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Meta Title</label>
                <input type="text" name="seo_title" value="{{ $settings['seo']->firstWhere('key','seo_title')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Meta Description</label>
                <textarea name="seo_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">{{ $settings['seo']->firstWhere('key','seo_description')->value ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Meta Keywords</label>
                <input type="text" name="seo_keywords" value="{{ $settings['seo']->firstWhere('key','seo_keywords')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">OG Image (upload)</label>
                @if(!empty($settings['seo']->firstWhere('key','seo_og_image')->value))
                <div class="mb-2"><img src="{{ $settings['seo']->firstWhere('key','seo_og_image')->value }}" class="h-16 rounded"></div>
                @endif
                <input type="file" name="seo_og_image_file" accept=".png,.jpg,.jpeg,.webp" class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-3 file:border-0 file:bg-gray-100 file:text-gray-700 file:text-xs">
            </div>
        </div>
    </div>

    {{-- Appearance --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Tampilan</h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Primary Color</label>
                    <div class="flex gap-2">
                        <input type="color" name="primary_color" value="{{ $settings['appearance']->firstWhere('key','primary_color')->value ?? '#4f46e5' }}" class="h-9 w-12 rounded border border-gray-300 cursor-pointer">
                        <input type="text" value="{{ $settings['appearance']->firstWhere('key','primary_color')->value ?? '#4f46e5' }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm bg-gray-50" readonly>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Hero Badge Text</label>
                    <input type="text" name="hero_badge_text" value="{{ $settings['appearance']->firstWhere('key','hero_badge_text')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ $settings['appearance']->firstWhere('key','hero_title')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" value="{{ $settings['appearance']->firstWhere('key','hero_subtitle')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">CTA Title</label>
                    <input type="text" name="cta_title" value="{{ $settings['appearance']->firstWhere('key','cta_title')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">CTA Description</label>
                    <input type="text" name="cta_description" value="{{ $settings['appearance']->firstWhere('key','cta_description')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Footer Text (opsional)</label>
                <input type="text" name="footer_text" value="{{ $settings['appearance']->firstWhere('key','footer_text')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500" placeholder="Kosongkan untuk default">
            </div>
        </div>
    </div>

    {{-- Contact --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Kontak</h3>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">WhatsApp</label>
                <input type="text" name="contact_whatsapp" value="{{ $settings['contact']->firstWhere('key','contact_whatsapp')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500" placeholder="6281xxx">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="contact_email" value="{{ $settings['contact']->firstWhere('key','contact_email')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Telegram</label>
                <input type="text" name="contact_telegram" value="{{ $settings['contact']->firstWhere('key','contact_telegram')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500" placeholder="@username">
            </div>
        </div>
    </div>

    {{-- DigiFlazz Seller API --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 text-sm">DigiFlazz Seller API</h3>
            <a href="https://developer.digiflazz.com" target="_blank" class="text-xs text-indigo-600 hover:underline">Dokumentasi</a>
        </div>
        <div class="p-5 space-y-4">
            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700">
                Daftar di <a href="https://member.digiflazz.com" target="_blank" class="underline font-medium">member.digiflazz.com</a>, ambil Username & API Key di <strong>Pengaturan Koneksi API</strong>. Produk bisa di-sync otomatis dari menu Produk.
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Username</label>
                    <input type="text" name="digiflazz_username" value="{{ $settings['digiflazz']->firstWhere('key','digiflazz_username')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="username_anda">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">API Key (Production)</label>
                    <input type="password" name="digiflazz_api_key" value="{{ $settings['digiflazz']->firstWhere('key','digiflazz_api_key')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="xxxxxxxx-xxxx-xxxx">
                </div>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Webhook Secret (opsional, untuk verifikasi signature)</label>
                <input type="text" name="digiflazz_webhook_secret" value="{{ $settings['digiflazz']->firstWhere('key','digiflazz_webhook_secret')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono">
            </div>
            <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-500 space-y-1">
                <div><strong>Webhook Prabayar:</strong> <code class="bg-white px-2 py-0.5 rounded border text-gray-700">{{ url('/api/digiflazz/webhook') }}</code></div>
                <div><strong>IP Whitelist:</strong> <code class="bg-white px-2 py-0.5 rounded border text-gray-700">52.74.250.133</code></div>
            </div>
        </div>
    </div>

    {{-- Profit Margin --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ mode: '{{ $settings['profit']->firstWhere('key','profit_mode')->value ?? 'percent' }}' }">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Margin Keuntungan</h3>
        </div>
        <div class="p-5 space-y-4">
            <p class="text-xs text-gray-500">Digunakan saat sync produk dari DigiFlazz untuk menghitung harga jual otomatis.</p>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Mode Margin</label>
                <select name="profit_mode" x-model="mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="percent">Persentase (%)</option>
                    <option value="flat">Flat (Rp)</option>
                </select>
            </div>
            <div x-show="mode==='percent'">
                <label class="block text-xs font-medium text-gray-600 mb-1">Margin Persen (%)</label>
                <input type="number" name="profit_margin_percent" value="{{ $settings['profit']->firstWhere('key','profit_margin_percent')->value ?? '5' }}" step="0.1" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="5">
                <p class="text-xs text-gray-400 mt-1">Contoh: 5% = harga beli Rp 10.000 → jual Rp 10.500</p>
            </div>
            <div x-show="mode==='flat'">
                <label class="block text-xs font-medium text-gray-600 mb-1">Margin Flat (Rp)</label>
                <input type="number" name="profit_margin_flat" value="{{ $settings['profit']->firstWhere('key','profit_margin_flat')->value ?? '500' }}" step="100" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="500">
                <p class="text-xs text-gray-400 mt-1">Contoh: Rp 500 = harga beli Rp 10.000 → jual Rp 10.500</p>
            </div>
        </div>
    </div>

    {{-- Payment Method --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Metode Pembayaran</h3>
        </div>
        <div class="p-5 space-y-3">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="payment_manual_enabled" value="0">
                <input type="checkbox" name="payment_manual_enabled" value="1" {{ ($settings['payment']->firstWhere('key','payment_manual_enabled')->value ?? '1') === '1' ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600">
                <div>
                    <span class="text-sm font-medium text-gray-800">Transfer Manual</span>
                    <p class="text-xs text-gray-500">Customer transfer ke rekening bank/e-wallet, upload bukti, admin verifikasi manual</p>
                </div>
            </label>
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="hidden" name="payment_gateway_enabled" value="0">
                <input type="checkbox" name="payment_gateway_enabled" value="1" {{ ($settings['payment']->firstWhere('key','payment_gateway_enabled')->value ?? '0') === '1' ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600">
                <div>
                    <span class="text-sm font-medium text-gray-800">Payment Gateway (Otomatis)</span>
                    <p class="text-xs text-gray-500">Pembayaran otomatis via Virtual Account, QRIS, E-Wallet, Minimarket</p>
                </div>
            </label>
        </div>
    </div>

    {{-- Payment Gateway Config --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ driver: '{{ $settings['payment_gateway']->firstWhere('key','pg_active_driver')->value ?? 'tripay' }}' }">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Payment Gateway</h3>
        </div>
        <div class="p-5 space-y-4">
            {{-- Driver selector --}}
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-2">Provider Aktif</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach(['tripay' => 'Tripay', 'xendit' => 'Xendit', 'duitku' => 'Duitku'] as $code => $label)
                    <label @click="driver='{{ $code }}'" :class="driver==='{{ $code }}' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600 hover:border-gray-300'" class="border rounded-lg p-3 text-center cursor-pointer transition text-sm font-medium">
                        {{ $label }}
                        <input type="radio" name="pg_active_driver" value="{{ $code }}" class="hidden" :checked="driver==='{{ $code }}'">
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Tripay --}}
            <div x-show="driver==='tripay'" x-cloak class="space-y-3 pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-800">Tripay</span>
                    <a href="https://tripay.co.id" target="_blank" class="text-xs text-indigo-600 hover:underline">tripay.co.id</a>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Mode</label>
                    <select name="pg_tripay_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="sandbox" {{ ($settings['payment_gateway']->firstWhere('key','pg_tripay_mode')->value ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                        <option value="production" {{ ($settings['payment_gateway']->firstWhere('key','pg_tripay_mode')->value ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Merchant Code</label>
                    <input type="text" name="pg_tripay_merchant_code" value="{{ $settings['payment_gateway']->firstWhere('key','pg_tripay_merchant_code')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="T12345">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">API Key</label>
                    <input type="text" name="pg_tripay_api_key" value="{{ $settings['payment_gateway']->firstWhere('key','pg_tripay_api_key')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="DEV-xxx...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Private Key</label>
                    <input type="password" name="pg_tripay_private_key" value="{{ $settings['payment_gateway']->firstWhere('key','pg_tripay_private_key')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="xxxxx-xxxxx">
                </div>
                <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <strong>Callback URL:</strong> <code class="bg-white px-2 py-0.5 rounded border text-gray-700">{{ url('/api/payment-gateway/callback/tripay') }}</code>
                </div>
            </div>

            {{-- Xendit --}}
            <div x-show="driver==='xendit'" x-cloak class="space-y-3 pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-800">Xendit</span>
                    <a href="https://www.xendit.co" target="_blank" class="text-xs text-indigo-600 hover:underline">xendit.co</a>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Secret API Key</label>
                    <input type="password" name="pg_xendit_secret_key" value="{{ $settings['payment_gateway']->firstWhere('key','pg_xendit_secret_key')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="xnd_development_xxx...">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Callback Verification Token</label>
                    <input type="text" name="pg_xendit_callback_token" value="{{ $settings['payment_gateway']->firstWhere('key','pg_xendit_callback_token')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="Token dari dashboard Xendit">
                </div>
                <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <strong>Callback URL:</strong> <code class="bg-white px-2 py-0.5 rounded border text-gray-700">{{ url('/api/payment-gateway/callback/xendit') }}</code>
                    <br>Masukkan di Xendit Dashboard &rarr; Settings &rarr; Callbacks.
                </div>
            </div>

            {{-- Duitku --}}
            <div x-show="driver==='duitku'" x-cloak class="space-y-3 pt-3 border-t border-gray-100">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-800">Duitku</span>
                    <a href="https://www.duitku.com" target="_blank" class="text-xs text-indigo-600 hover:underline">duitku.com</a>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Mode</label>
                    <select name="pg_duitku_mode" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="sandbox" {{ ($settings['payment_gateway']->firstWhere('key','pg_duitku_mode')->value ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                        <option value="production" {{ ($settings['payment_gateway']->firstWhere('key','pg_duitku_mode')->value ?? '') === 'production' ? 'selected' : '' }}>Production</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Merchant Code</label>
                    <input type="text" name="pg_duitku_merchant_code" value="{{ $settings['payment_gateway']->firstWhere('key','pg_duitku_merchant_code')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="Dxxxx">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">API Key</label>
                    <input type="password" name="pg_duitku_api_key" value="{{ $settings['payment_gateway']->firstWhere('key','pg_duitku_api_key')->value ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono" placeholder="xxxxx">
                </div>
                <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-500">
                    <strong>Callback URL:</strong> <code class="bg-white px-2 py-0.5 rounded border text-gray-700">{{ url('/api/payment-gateway/callback/duitku') }}</code>
                    <br>Masukkan di Duitku Dashboard &rarr; Project &rarr; Callback URL.
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end">
        <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Simpan Settings</button>
    </div>
</form>
@endsection
