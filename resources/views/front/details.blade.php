@extends('front.layouts.app')

@section('content')
<main
    class="bg-[#FAFAFA] max-w-[640px] mx-auto min-h-screen relative flex flex-col has-[#CTA-nav]:pb-[120px] has-[#Bottom-nav]:pb-[120px]">

    {{-- 🔝 Top Navigation --}}
    <div id="Top-nav" class="flex items-center justify-between px-4 pt-5 absolute top-0 z-10 w-full">
        <a href="{{ url()->previous() }}" class="w-10 h-10 flex shrink-0">
            <img src="{{ asset('assets/images/icons/back.svg') }}" alt="icon">
        </a>
    </div>

    {{-- 🖼️ Thumbnail Section --}}
    <section id="Thumbnail" class="relative">
        <div class="swiper h-fit">
            <div class="swiper-wrapper w-full h-fit">
                <div class="swiper-slide !w-[310px] !h-[350px] flex shrink-0 overflow-hidden">
                    <img src="{{ Storage::url($Bengkel->gambar_pratinjau) }}" class="object-cover w-full h-full"
                        alt="thumbnail">
                </div>
                @foreach($Bengkel->photos as $photo)
                <div class="swiper-slide !w-[310px] !h-[350px] flex shrink-0 overflow-hidden">
                    <img src="{{ Storage::url($photo->foto) }}" class="object-cover w-full h-full" alt="thumbnail">
                </div>
                @endforeach
            </div>
        </div>

        <div class="px-4 flex justify-between items-center transform translate-y-1/2 absolute bottom-0 z-10 w-full">
            @if($Bengkel->status_operasional == 1 || strtolower($Bengkel->status_operasional) == 'buka')
            <p class="badge w-fit rounded-full p-[6px_10px] bg-[#41BE64] font-bold text-xs leading-[18px] text-white">
                Buka</p>
            @else
            <p class="badge w-fit rounded-full p-[6px_10px] bg-[#F12B3E] font-bold text-xs leading-[18px] text-white">
                Tutup</p>
            @endif
        </div>
    </section>

    {{-- 📄 Detail Section --}}
    <section id="details" class="flex flex-col gap-5 px-4 mt-[33px]">
        <div id="title" class="flex flex-col gap-[6px]">
            <div class="flex items-center gap-1">
                <h1 class="font-semibold text-xl leading-[30px] w-fit">{{ $Bengkel->nama }}</h1>
                <div class="w-[22px] h-[22px] flex shrink-0">
                    <img src="{{ asset('assets/images/icons/verify.svg') }}" alt="verified">
                </div>
            </div>
            <div class="flex items-center gap-[2px]">
                <div class="w-4 h-4 flex shrink-0">
                    <img src="{{ asset('assets/images/icons/location.svg') }}" alt="icon">
                </div>
                <p class="text-sm leading-[21px] text-[#909DBF]">
                    {!! $Bengkel->alamat !!}, {{ $Bengkel->Kota->nama }}
                </p>
            </div>
        </div>

        {{-- 🧩 Tabs --}}
        <div id="Menus" class="flex flex-col gap-5">
            <div class="tab-link-btns flex items-center gap-2">
                <button
                    class="tablink rounded-full border border-[#E9E8ED] p-[8px_16px] font-semibold text-sm bg-[#5B86EF] text-white hover:bg-[#5B86EF] hover:text-white"
                    onclick="openPage('about-tab', this)" id="defaultOpen">Tentang</button>
                <button
                    class="tablink rounded-full border border-[#E9E8ED] p-[8px_16px] font-semibold text-sm bg-white hover:bg-[#5B86EF] hover:text-white"
                    onclick="openPage('contact-tab', this)">Kontak</button>
            </div>

            <div class="tabs-contents">
                {{-- 🧰 About Tab --}}
                <div id="about-tab" class="tabcontent flex hidden">
                    <div class="flex flex-col gap-5">
                        <p class="leading-[28px]">{!! $ServisMobil->detail ?? 'Detail tidak tersedia.' !!}</p>


                        <div id="Service" class="flex flex-col gap-2">
                            <h2 class="font-semibold">Layanan Anda</h2>

                            <div class="rounded-2xl border border-[#E9E8ED] p-4 bg-white transition-all duration-300
@if(optional($ServisMobil)->hasVariants())
    flex flex-col gap-4
@else
    flex flex-col gap-3
@endif
">

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-[10px]">
                                        <div class="w-[60px] h-[60px] flex shrink-0">
                                            <img src="{{ Storage::url(optional($ServisMobil)->icon) }}" alt="icon">

                                        </div>
                                        <div class="flex flex-col">
                                            <p class="font-semibold">
                                                {{ optional($ServisMobil)->nama ?? 'Tidak ada data' }}
                                            </p>

                                            <p class="text-sm leading-[21px] text-[#909DBF]">Layanan Unggulan</p>
                                        </div>

                                    </div>
                                    <!-- <button class="appearance-none font-semibold text-sm text-[#FF8E62] hover:underline"
                                        data-modal-target="default-modal" data-modal-toggle="default-modal"
                                        type="button">Detail Servis</button> -->
                                </div>

                                {{-- 🔸 Variants --}}
                                @if(optional($ServisMobil)->hasVariants())
                                <div class="flex flex-col gap-2 mt-4">
                                    <h3 class="font-semibold">Pilihan Variant:</h3>
                                    <p class="text-sm text-[#909DBF] mb-2">Servis ini memiliki beberapa pilihan variant.
                                        Harga akan ditampilkan setelah Anda memilih variant saat pemesanan.</p>
                                    <div class="flex flex-col gap-2">
                                        @foreach($ServisMobil->variants as $variant)
                                        <div class="p-3 bg-gray-50 rounded-lg flex justify-between">
                                            <div>
                                                <p class="font-medium">{{ $variant->nama }}</p>
                                                @if($variant->deskripsi)
                                                <!-- <p class="text-sm text-gray-600 mt-1">{{ $variant->deskripsi }}</p> -->
                                                @endif
                                            </div>
                                            <p class="font-semibold text-[#FF8E62]">Rp
                                                {{ number_format($variant->harga, 0, ',', '.') }}
                                            </p>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @else
                                <div class="mt-4 w-full">
                                    <div
                                        class="flex items-center justify-between w-full p-4 bg-[#FFF5F2] rounded-2xl border border-[#FFE1D3] text-[#FF8E62]">
                                        <p class="font-semibold">Harga Layanan</p>
                                        <p class="font-bold text-xl">Rp
                                            {{ number_format($ServisMobil->harga, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ☎️ Contact Tab --}}
                <div id="contact-tab" class="tabcontent flex hidden">
                    <div class="flex flex-col gap-5">
                        <p class="leading-[28px]">Jika Anda memiliki pertanyaan silakan menghubungi customer service
                            kami.</p>
                        <div id="Contact" class="flex flex-col gap-2">
                            <div
                                class="rounded-2xl border border-[#E9E8ED] flex items-center justify-between p-4 bg-white">
                                <div class="flex items-center gap-[10px]">
                                    <div class="w-[60px] h-[60px] flex shrink-0">
                                        <img src="{{ asset('assets/images/icons/contact.svg') }}" alt="icon">
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="font-semibold">{{ $Bengkel->nama_cs }}</p>
                                        <p class="text-sm leading-[21px] text-[#909DBF]">Admin</p>
                                    </div>
                                </div>

                                @if(!empty($Bengkel->nomer_telepon))
                                @php
                                $phone = preg_replace('/[^0-9]/', '', $Bengkel->nomer_telepon);
                                if (str_starts_with($phone, '0')) $phone = '62' . substr($phone, 1);
                                $message = urlencode('Halo, Saya ingin bertanya tentang jenis layanan ini.');
                                @endphp
                                <a href="https://wa.me/{{ $phone }}?text={{ $message }}" target="_blank"
                                    class="font-semibold text-sm text-[#FF8E62] hover:underline">
                                    Hubungi Sekarang
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 🚗 CTA Navbar --}}
    <div id="CTA-nav"
        class="fixed bottom-0 w-full max-w-[640px] mx-auto border-t border-[#E9E8ED] flex items-center justify-between p-[16px_24px] bg-white z-20">
        <div class="flex flex-col gap-[2px]">
            @if($ServisMobil->hasVariants())
            <p class="text-sm text-[#909DBF]">Mulai dari</p>
            <p class="font-bold text-xl leading-[30px]">Rp
                {{ number_format($ServisMobil->variants->min('harga'), 0, ',', '.') }}
            </p>
            @else
            <p class="font-bold text-xl leading-[30px]">Rp {{ number_format($ServisMobil->harga, 0, ',', '.') }}</p>
            @endif
        </div>

        @if($Bengkel->status_operasional)
        @if($Bengkel->status_kapasitas)
        <a href="{{ route('front.booking', $Bengkel->slug) }}"
            class="rounded-full p-[12px_20px] bg-[#FF8E62] font-bold text-white">Pesan Sekarang</a>
        @else
        <span class="rounded-full p-[12px_20px] bg-[#EEEFF4] font-bold text-[#AAADBF]">Penuh</span>
        @endif
        @else
        <span class="rounded-full p-[12px_20px] bg-[#EEEFF4] font-bold text-[#AAADBF]">Tutup</span>
        @endif
    </div>

    {{-- 📦 Modal Detail Servis --}}
    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto fixed inset-0 z-50 justify-center items-center w-full h-full bg-[#01031090]">
        <div class="relative p-4 px-9 w-full max-w-2xl max-h-full">
            <div class="bg-white max-w-[320px] mx-auto flex flex-col h-fit rounded-[20px] pb-4 gap-4 overflow-hidden">
                <div class="w-full h-[150px] flex shrink-0">
                    <img src="{{ Storage::url($ServisMobil->foto) }}" class="w-full h-full object-cover"
                        alt="thumbnail">
                </div>
                <div class="flex flex-col px-4 gap-4">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col gap-[2px]">
                            <p class="font-semibold text-lg leading-[27px]">{{ $ServisMobil->nama }}</p>
                            <p class="text-sm leading-[21px] text-[#909DBF]">Layanan Unggulan</p>
                        </div>
                        <p class="rounded-full p-[6px_10px] bg-[#DFB3E6] w-fit font-bold text-xs leading-[18px]">Populer
                        </p>
                    </div>
                    <hr class="border-[#E9E8ED]">
                    <div class="leading-[28px]">{!! $ServisMobil->detail !!}</div>
                    <button class="rounded-full border border-[#E9E8ED] p-[12px_16px] bg-white w-full font-semibold"
                        data-modal-hide="default-modal">Tutup Detail</button>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('after-styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
@endpush

@push('after-scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="{{ asset('customjs/details.js') }}"></script>
@endpush