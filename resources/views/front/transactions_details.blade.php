<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('output.css') }}" rel="stylesheet">
    <link href="{{ asset('main.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
</head>

<body class="bg-[#FAFAFA] font-[Poppins]">
    <main
        class="max-w-[640px] mx-auto min-h-screen relative flex flex-col has-[#CTA-nav]:pb-[120px] has-[#Bottom-nav]:pb-[120px]">
        <!-- Background Header -->
        <div class="bg-[#270738] absolute top-0 max-w-[640px] w-full mx-auto rounded-b-[50px] h-[472px]"></div>

        <!-- Top Navigation -->
        <div id="Top-nav" class="flex items-center justify-between px-8 pt-5 relative z-10">
            <a href="{{ route('front.transactions') }}" class="text-white">
                <div class="w-10 h-10 flex shrink-0">
                    <img src="{{ asset('assets/images/icons/back.svg') }}" alt="icon">
                </div>
            </a>
            <div class="flex flex-col w-fit text-center text-black">
                <h1 class="font-semibold text-lg leading-[27px]">DETAIL PEMESANAN</h1>
                <p class="text-sm leading-[21px]">Rawat Mobil Anda dengan Baik</p>
            </div>
            <div class="w-10 h-10 flex shrink-0"></div>
        </div>


        <!-- Status Card -->
        <section id="Status-details" class="flex flex-col gap-2 px-8 mt-[30px] relative z-10">
            <div class="flex flex-col w-full rounded-2xl border border-[#E9E8ED] p-4 gap-4 bg-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-[10px]">
                        <div class="w-[60px] h-[60px] flex shrink-0">
                            <img src="{{ asset('assets/images/icons/illustration6.svg') }}" alt="icon">
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <p class="font-semibold">{{ $details->trx_id }}</p>
                                <div class="w-[18px] h-[18px] flex shrink-0">
                                    <img src="{{ asset('assets/images/icons/verify.svg') }}" alt="verified">
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($details->status_pembayaran)
                    <p class="rounded-full p-[6px_10px] bg-[#41BE64] w-fit font-bold text-xs leading-[18px] text-white">
                        LUNAS
                    </p>
                </div>
                @else
                <p class="rounded-full p-[6px_10px] bg-[#FFCE51] w-fit font-bold text-xs leading-[18px]">BELUM LUNAS
                </p>
                @endif
            </div>
            </div>
        </section>

        <!-- Order Details -->
        <section id="Order-details" class="flex flex-col gap-4 px-8 mt-[18px] relative z-10">
            <div class="flex flex-col w-full rounded-2xl border border-[#E9E8ED] p-4 gap-4 bg-white">
                <!-- Bengkel -->
                <div id="Location" class="flex flex-col gap-2">
                    <h2 class="font-semibold">Bengkel</h2>
                    <div class="flex items-center gap-[10px]">
                        <div class="w-[80px] h-[60px] flex shrink-0 rounded-xl overflow-hidden">
                            <img src="{{ Storage::url($details->store_details->gambar_pratinjau) }}"
                                class="w-full h-full object-cover" alt="thumbnail">
                        </div>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-1">
                                <p class="font-semibold">{{ $details->store_details->nama }}</p>
                                <div class="w-[18px] h-[18px] flex shrink-0">
                                    <img src="{{ asset('assets/images/icons/verify.svg') }}" alt="verified">
                                </div>
                            </div>
                            <p class="text-sm text-[#909DBF]">{{ strip_tags($details->store_details->alamat) }}</p>
                        </div>
                    </div>
                </div>

                <hr class="border-[#E9E8ED]">

                <!-- Layanan -->
                <div id="Service" class="flex flex-col gap-2">
                    <h2 class="font-semibold">Layanan Anda</h2>
                    <div class="flex items-center justify-between gap-[10px]">
                        <div class="flex items-center gap-[10px]">
                            <div class="w-[60px] h-[60px] flex shrink-0">
                                <img src="{{ Storage::url($details->service_details->icon) }}" alt="icon">
                            </div>
                            <div class="flex flex-col">
                                <p class="font-semibold">{{ $details->service_details->nama }}</p>
                                @if ($details->variant_details)
                                <p class="text-xs text-[#5B86EF] font-medium">
                                    Variant: {{ $details->variant_details->nama }}
                                </p>
                                @endif
                                <p class="text-sm text-[#909DBF]">Layanan Unggulan</p>
                            </div>
                        </div>
                        <p class="rounded-full p-[6px_10px] bg-[#DFB3E6] w-fit font-bold text-xs leading-[18px]">
                            Populer
                        </p>
                    </div>
                </div>

                <hr class="border-[#E9E8ED]">

                <!-- Waktu -->
                <div id="Time-details" class="flex flex-col gap-[10px]">
                    <div class="flex justify-between">
                        <p class="text-sm text-[#270738]">Jam Pemesanan</p>
                        <p class="font-semibold">{{ $details->jam_mulai }} WITA</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-[#270738]">Tanggal Pemesanan</p>
                        <p class="font-semibold text-right">{{ $details->waktu_mulai->format('d F Y') }}</p>
                    </div>
                </div>
                @if($details->catatan)
                <hr class="border-[#E9E8ED]">
                <div id="Notes" class="flex flex-col gap-2">
                    <h2 class="font-semibold">Catatan Pemesanan</h2>
                    <div class="bg-[#F8F9FA] rounded-xl p-4">
                        <p class="text-sm leading-[24px] text-[#270738]">{{ $details->catatan }}</p>
                    </div>
                </div>
                @endif
                <hr class="border-[#E9E8ED]">

                <!-- Harga -->
                <div id="Price-details" class="flex flex-col gap-[10px]">
                    <div class="flex justify-between">
                        <p class="text-sm text-[#270738]">Total Harga</p>
                        <p class="font-bold text-xl text-[#FF8E62]">
                            Rp {{ number_format($details->total_bayar, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Tambahkan setelah section waktu dan sebelum tombol WhatsApp -->
        @if($details->status_pembayaran)
        <section id="Rating-section" class="flex flex-col gap-2 px-8 mt-[18px] relative z-10">
            @if($details->rating)
            <!-- Tampilkan rating yang ada -->
            <div class="w-full rounded-2xl border border-[#E9E8ED] p-4 bg-white">
                <h3 class="font-semibold mb-3">Rating Anda</h3>
                <div class="flex items-center gap-2 mb-2">
                    @for($i = 1; $i <= 5; $i++) <span
                        class="text-2xl {{ $i <= $details->rating ? 'text-[#FFD700]' : 'text-gray-300' }}">⭐</span>
                        @endfor
                        <span class="font-semibold">{{ $details->rating }}/5</span>
                </div>
                @if($details->komentar)
                <div class="bg-gray-50 rounded-xl p-3 mt-3">
                    <p class="text-sm text-gray-700">{{ $details->komentar }}</p>
                </div>
                @endif
            </div>
            {{-- KODE BARU YANG SUDAH DIPERBAIKI --}}
            @else
            <div class="rounded-2xl border border-[#E9E8ED] p-4 bg-white flex flex-col items-center gap-3">

                <p class="text-sm text-center">Berikan rating untuk layanan kami!</p>

                <a href="{{ route('front.rating', $details->trx_id) }}"
                    class="text-center rounded-full py-3 px-4 bg-[#5B86EF] font-bold text-white">
                    Beri Rating ⭐
                </a>
            </div>
            @endif
        </section>
        @endif
        {{-- (Ini adalah akhir dari section "Rating-section" Anda) --}}



        <div class="px-8 mt-[30px] pb-6 flex flex-col gap-3">

            {{-- KODE LOGIKA UNTUK TOMBOL CETAK --}}
            @if($details->status_pembayaran)
            <p class="text-xs text-center text-gray-700 mb-1">
                <strong>PENTING: Invoice ini Wajib Dicetak dan Dibawa.</strong>
                Harap tunjukkan invoice ini untuk verifikasi identitas saat Anda <strong>Mengantar</strong> dan
                <strong>Mengambil</strong> kendaraan di bengkel.
            </p>
            <a href="{{ route('front.invoice', $details->trx_id) }}" target="_blank"
                {{-- target="_blank" penting agar membuka di tab baru --}}
                class="w-full rounded-full p-[12px_20px] bg-[#5B86EF] font-bold text-white text-center">
                Cetak Invoice
            </a>
            {{--
                Catatan: Saya mengganti warna tombol ini menjadi biru (bg-[#5B86EF]) 
                agar berbeda dari tombol "Hubungi CS" (yang oranye).
                Jika ingin tetap oranye, ganti saja kembali ke bg-[#FF8E62].
            --}}
            @endif

            {{-- Tombol WhatsApp (Kode Anda yang sudah ada) --}}
            @if (!empty($details->store_details) && !empty($details->store_details->nomer_telepon))
            @php
            $phone = preg_replace('/[^0-9]/', '', $details->store_details->nomer_telepon);
            if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
            }
            $message = urlencode('Halo, saya ingin menanyakan tentang booking saya.');
            @endphp
            <a href="https://wa.me/{{ $phone }}?text={{ $message }}" target="_blank"
                class="w-full rounded-full p-[12px_20px] bg-[#FF8E62] font-bold text-white text-center">
                Hubungi Costumer Servis
            </a>
            @endif

            {{-- Tombol Kembali (Sangat disarankan) --}}
            <!-- <a href="{{ route('front.transactions') }}"
                class="w-full rounded-full border border-[#E9E8ED] p-[12px_20px] bg-white text-center font-bold">
                Kembali
            </a> -->

        </div>
    </main>
</body>

</html>