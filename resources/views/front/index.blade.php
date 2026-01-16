@extends('front.layouts.app')
@section('title',' Auto Berkah')
@section('content')
<main
    class="bg-[#FAFAFA] max-w-[640px] mx-auto min-h-screen relative flex flex-col has-[#CTA-nav]:pb-[120px] has-[#Bottom-nav]:pb-[120px]">
    <!-- <div class="bg-red-500 absolute top-0 max-w-[640px] w-full mx-auto rounded-b-[50px] h-[370px]"> -->
    <div class="bg-[#da3f3f] absolute top-0 max-w-[640px] w-full mx-auto rounded-b-[50px] h-[370px]"></div>
    </div>


    <header class="flex flex-col gap-3 items-center text-center pt-10 relative z-10">
        <div class="flex shrink-0">
            <img src="{{asset('assets/images/logos/lgo.png')}}" alt="logo" class="w-16 h-16">
        </div>
        <h1 class=" text-white">Auto Berkah
            <h1 />
    </header>
    <form action="" class="flex flex-col gap-6 mt-6 relative z-10">
        <div class="flex flex-col gap-2 px-4">
            <label for="Location" class="font-semibold text-white">Lokasi</label>
            <div
                class="rounded-full flex items-center p-[12px_16px] bg-white w-full transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FF8E62]">
                <div class="w-6 h-6 flex shrink-0 mr-[6px]">
                    <img src="{{asset('assets/images/icons/location-normal.svg')}}" alt="icon">
                </div>
                <select name="city_id" id="city_id" class=" bg-white font-semibold w-full outline-none">
                    @foreach($cities as $Kota)
                    <option value="{{ $Kota->id }}" selected>{{ $Kota->nama }}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <section id="Services" class="flex flex-col gap-3 px-4">
            <h1 class="font-semibold text-white">Layanan Perawatan Mobil</h1>
            <div class="grid grid-cols-3 gap-4">

                @forelse ($services as $service)
                <a href="#" class="service-link card-services" data-service="{{ $service->id }}">
                    <div
                        class="rounded-[20px] border border-[#E9E8ED] py-4 flex flex-col items-center text-center gap-4 bg-white transition-all duration-300 hover:ring-2 hover:ring-[#FF8E62]">
                        <div class="w-[50px] h-[50px] flex shrink-0">
                            <img src="{{Storage::url($service->icon)}}" alt="icon">
                        </div>
                        <div class="flex flex-col">
                            <p class="font-semibold text-sm leading-[21px]">
                                {{$service->nama}}
                            </p>
                            <p class="text-xs leading-[18px] text-[#909DBF]">
                                <!-- {{ $service->servis_count }} Stores -->
                            </p>
                        </div>
                    </div>
                </a>
                @empty
                <p>belum ada tersedia...</p>
                @endforelse
            </div>
        </section>
    </form>

    <nav id="Bottom-nav"
        class="fixed bottom-0 w-full max-w-[640px] mx-auto border-t border-[#E9E8ED] p-[20px_24px] bg-white z-20">
        <ul class="flex items-center justify-evenly">
            <li>
                <a href="{{ route('front.index') }}" class="flex flex-col items-center text-center gap-1">
                    <div class="w-6 h-6 flex shrink-0 ">
                        <img src="{{asset('assets/images/icons/element-equal.svg')}}" alt="icon">
                    </div>
                    <p class="font-semibold text-xs leading-[18px] text-[#FF8969]">Beranda</p>
                </a>

            </li>
            <li>
                <a href="{{ route('front.transactions') }}" class="flex flex-col items-center text-center gap-1">
                    <div class="w-6 h-6 flex shrink-0 ">
                        <img src="{{asset('assets/images/icons/note-favorite-grey.svg')}}" alt="icon">
                    </div>
                    <p class="font-semibold text-xs leading-[18px] text-[#BABEC7]">Order</p>
                </a>
            </li>

            <li>
                @if(!empty($Bengkel) && !empty($Bengkel->nomer_telepon))
                @php
                $phone = preg_replace('/[^0-9]/', '', $Bengkel->nomer_telepon);

                // Kalau nomor mulai dari 0, ubah jadi format internasional (62)
                if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
                }

                $message = urlencode('Halo, saya ingin menanyakan tentang bengkel anda.');
                @endphp

                <a href="https://wa.me/{{ $phone }}?text={{ $message }}"
                    class="flex flex-col items-center text-center gap-1" target="_blank">
                    <div class="w-6 h-6 flex shrink-0">
                        <img src="{{ asset('assets/images/icons/message-question-grey.svg') }}" alt="icon">
                    </div>
                    <p class="font-semibold text-xs leading-[18px] text-[#BABEC7]">Bantuan</p>
                </a>
                @endif


            </li>
        </ul>
    </nav>
</main>
@endsection
@push('before-scripts')
<script>
    document.querySelectorAll('.service-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const cityId = document.getElementById('city_id').value;
            const serviceTypeId = this.getAttribute('data-service');

            // Redirect to a route with parameters
            window.location.href = `/go-to-store?city_id=${cityId}&service_type=${serviceTypeId}`;
        });
    });
</script>
@endpush