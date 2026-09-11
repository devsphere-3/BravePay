{{-- Floating Help Center Widget --}}
<div
    class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3"
    x-data="helpCenter()"
>
    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="w-80 bg-white rounded-2xl shadow-2xl border border-[#E2E8F7] overflow-hidden"
        x-cloak
    >
        {{-- Header --}}
        <div class="bg-[#0B1040] px-5 py-4 relative overflow-hidden">
            <div class="absolute inset-0 batik-kawung opacity-20 pointer-events-none"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <h3 class="text-white font-bold text-base">Pusat Bantuan</h3>
                    <p class="text-blue-200/70 text-xs mt-0.5">Kami siap membantu Anda</p>
                </div>
                <button @click="toggle()" class="text-white/60 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Tab Nav --}}
        <div class="flex border-b border-[#E2E8F7]">
            <button
                @click="activeTab = 'resend'"
                class="flex-1 px-3 py-3 text-xs font-semibold transition-colors"
                :class="activeTab === 'resend' ? 'text-[#2563EB] border-b-2 border-[#2563EB]' : 'text-[#64748B] hover:text-[#2563EB]'"
            >Kirim Ulang E-Ticket</button>
            <button
                @click="activeTab = 'check'"
                class="flex-1 px-3 py-3 text-xs font-semibold transition-colors"
                :class="activeTab === 'check' ? 'text-[#2563EB] border-b-2 border-[#2563EB]' : 'text-[#64748B] hover:text-[#2563EB]'"
            >Cek Pembayaran</button>
            <button
                @click="activeTab = 'other'"
                class="flex-1 px-3 py-3 text-xs font-semibold transition-colors"
                :class="activeTab === 'other' ? 'text-[#2563EB] border-b-2 border-[#2563EB]' : 'text-[#64748B] hover:text-[#2563EB]'"
            >Lainnya</button>
        </div>

        {{-- Resend Tab --}}
        <div x-show="activeTab === 'resend'" class="p-5">
            <p class="text-[#64748B] text-xs leading-relaxed mb-4">
                Masukkan nomor WhatsApp dan kode order Anda untuk menerima ulang E-Ticket.
            </p>
            <div class="space-y-3">
                <div>
                    <label class="form-label text-xs">Nomor WhatsApp</label>
                    <input
                        x-model="phone"
                        type="tel"
                        placeholder="08xxxxxxxxxx"
                        class="form-input text-sm"
                    >
                </div>
                <div>
                    <label class="form-label text-xs">Kode Order <span class="text-[#94A3B8] font-normal">(opsional)</span></label>
                    <input
                        x-model="orderCode"
                        type="text"
                        placeholder="BRV-20260911-0001"
                        class="form-input text-sm"
                    >
                </div>

                {{-- Alert --}}
                <div x-show="message" x-cloak
                     class="flex items-start gap-2 px-3 py-2.5 rounded-lg text-xs font-medium"
                     :class="messageType === 'success' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200'">
                    <svg x-show="messageType === 'success'" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg x-show="messageType === 'error'" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/>
                    </svg>
                    <span x-text="message"></span>
                </div>

                <button
                    @click="resendTicket()"
                    :disabled="!phone || loading"
                    class="btn-primary w-full justify-center text-sm py-2.5 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <span x-show="!loading">Kirim E-Ticket</span>
                    <span x-show="loading">Mengirim...</span>
                </button>
            </div>
        </div>

        {{-- Check Payment Tab --}}
        <div x-show="activeTab === 'check'" class="p-5">
            <p class="text-[#64748B] text-xs leading-relaxed mb-4">
                Masukkan kode order untuk mengecek status pembayaran Anda.
            </p>
            <div class="space-y-3">
                <div>
                    <label class="form-label text-xs">Kode Order</label>
                    <input type="text" placeholder="BRV-20260911-0001" class="form-input text-sm">
                </div>
                <a href="{{ route('payment.check') }}" class="btn-secondary w-full justify-center text-sm py-2.5 block text-center">
                    Cek Status
                </a>
            </div>
        </div>

        {{-- Other Tab --}}
        <div x-show="activeTab === 'other'" class="p-5">
            <div class="space-y-2">
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F1F5FE] transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4 text-blue-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1E3A8A]">Bantuan Pendaftaran</p>
                        <p class="text-xs text-[#64748B]">Masalah saat mendaftar?</p>
                    </div>
                </a>
                <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-[#F1F5FE] transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4 text-blue-600 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1E3A8A]">Bantuan Pembayaran</p>
                        <p class="text-xs text-[#64748B]">QRIS atau metode lainnya</p>
                    </div>
                </a>
                <a href="https://wa.me/6281234567890" target="_blank"
                   class="flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0 group-hover:bg-green-500 transition-colors">
                        <svg class="w-4 h-4 text-green-600 group-hover:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#1E3A8A]">Chat via WhatsApp</p>
                        <p class="text-xs text-[#64748B]">Respon cepat</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Floating button --}}
    <button
        @click="toggle()"
        class="w-14 h-14 bg-[#2563EB] hover:bg-[#1D4ED8] text-white rounded-2xl shadow-lg
               flex items-center justify-center transition-all duration-200 hover:scale-105 active:scale-95"
        :class="open ? 'rotate-0' : ''"
        aria-label="Bantuan"
    >
        <svg x-show="!open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <svg x-show="open" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" x-cloak>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
