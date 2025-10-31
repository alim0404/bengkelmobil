<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('output.css')}}" rel="stylesheet">
    <link href="{{asset('main.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>
    <main
        class="bg-[#FAFAFA] max-w-[640px] mx-auto min-h-screen relative flex flex-col has-[#CTA-nav]:pb-[120px] has-[#Bottom-nav]:pb-[120px]">
        <div id="Top-nav" class="flex items-center justify-between px-4 pt-5">
            <a href="{{ url()->previous() }}">
                <div class="w-10 h-10 flex shrink-0">
                    <img src="{{asset('assets/images/icons/back.svg')}}" alt="icon">
                </div>
            </a>
            <div class="flex flex-col w-fit text-center">
                <h1 class="font-semibold text-lg leading-[27px]">Pemesanan</h1>
                <p class="text-sm leading-[21px] text-[#909DBF]">Rawat Mobilmu dengan Baik</p>
            </div>
            <div class="w-10 h-10 flex shrink-0"></div>
        </div>
        <div id="Location" class="flex flex-col gap-2 px-4 mt-[30px]">
            <h2 class="font-semibold">Bengkel</h2>
            <div class="flex items-center w-full rounded-2xl border border-[#E9E8ED] p-4 gap-[10px] bg-white">
                <div class="w-[80px] h-[60px] flex shrink-0 rounded-xl overflow-hidden">
                    <img src="{{Storage::url($Bengkel->gambar_pratinjau)}}" class="w-full h-full object-cover"
                        alt="thumbnail">
                </div>
                <div class="flex flex-col">
                    <div class="flex items-center gap-1">
                        <h1 class="font-semibold w-fit">{{$Bengkel->nama}}</h1>
                        <div class="w-[18px] h-[18px] flex shrink-0">
                            <img src="{{asset('assets/images/icons/verify.svg')}}" alt="verified">
                        </div>
                    </div>
                    <div class="flex items-center gap-[2px]">
                        <p class="text-sm leading-[21px] text-[#909DBF]"> {!! $Bengkel->alamat !!}</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="Service" class="flex flex-col gap-2 px-4 mt-5">
            <h2 class="font-semibold">Layanan Anda</h2>
            <div class="rounded-2xl border border-[#E9E8ED] flex items-center justify-between p-4 bg-white">
                <div class="flex items-center gap-[10px]">
                    <div class="w-[60px] h-[60px] flex shrink-0">
                        <img src="{{Storage::url($service->icon)}}" alt="icon">
                    </div>
                    <div class="flex flex-col h-fit">
                        <p class="font-semibold">{{$service->nama}}</p>
                        <p class="text-sm leading-[21px] text-[#909DBF]">Layanan Terbaik</p>
                    </div>
                </div>
                <p class="rounded-full p-[6px_10px] bg-[#DFB3E6] w-fit font-bold text-xs leading-[18px]">Populer</p>
            </div>
        </div>
        <div class="flex h-full flex-1 mt-5">
            <form method="POST" action="{{ route('front.booking.store', [$Bengkel->slug, $service->slug]) }}"
                class="w-full flex flex-col rounded-t-[30px] p-5 pt-[30px] gap-[26px] bg-white overflow-x-hidden mb-0 mt-auto">
                @csrf

                @if($service->hasVariants())
                <div class="flex flex-col gap-2">
                    <h2 class="font-semibold">Pilih Variant <span class="text-red-500">*</span></h2>
                    <div class="flex flex-col gap-2">
                        @foreach($service->variants as $variant)
                        <label class="group relative cursor-pointer">
                            <div
                                class="rounded-2xl border border-[#E9E8ED] p-4 bg-white transition-all duration-300 group-has-[:checked]:ring-2 group-has-[:checked]:ring-[#5B86EF]">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col flex-1">
                                        <p class="font-semibold">{{ $variant->nama }}</p>
                                        @if($variant->deskripsi)
                                        <p class="text-sm text-[#909DBF] mt-1">{{ $variant->deskripsi }}</p>
                                        @endif
                                    </div>
                                    <p class="font-bold text-[#FF8E62] ml-4">Rp
                                        {{ number_format($variant->harga, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <input type="radio" name="variant_id" value="{{ $variant->id }}"
                                class="absolute top-1/2 left-1/2 -z-10 variant-radio" data-price="{{ $variant->harga }}"
                                required>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tampilkan harga setelah memilih variant -->
                <div id="price-display" class="flex flex-col gap-2 hidden mt-3">
                    <div class="rounded-2xl border border-[#E9E8ED] p-4 bg-[#F8F9FA]">
                        <div class="flex items-center justify-between">
                            <p class="font-bold ">Harga Layanan</p>
                            <p class="font-bold text-xl text-[#FF8E62]" id="selected-price">Rp 0</p>
                        </div>
                    </div>
                </div>
                @else
                <input type="hidden" name="variant_id" value="">
                @endif

                <div class="flex flex-col gap-2 mt-5">
                    <h2 class="font-semibold">Pilih Waktu</h2>
                </div>


                <!-- <label for="Name" class="font-semibold">Your Name</label> -->
                <div
                    class="rounded-full flex items-center ring-1 ring-[#E9E8ED] p-[12px_16px] bg-white w-full transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FF8E62]">
                    <img src="{{ asset('assets/images/icons/calendar.svg') }}" alt="icon" class="w-5 h-5">
                    <div class="flex items-center mr-[10px]">
                    </div>
                    <input type="date" name="started_at" id="date" placeholder="silahkan pilih tanggal" required>
                </div>

                <div class="flex flex-col gap-2">
                    <h2 class="font-semibold">Pilih Jam</h2>
                    <div class="swiper2 h-fit">
                        <div class="swiper-wrapper w-full h-fit">
                            @foreach(["09:00:00", "10:00:00", "11:00:00", "12:00:00", "13:00:00", "14:00:00",
                            "15:00:00", "16:00:00"] as $time)
                            <label class="swiper-slide !w-fit group relative">
                                <div
                                    class="rounded-full !w-fit border border-[#E9E8ED] p-[12px_20px] font-semibold transition-all duration-300 hover:bg-[#5B86EF] hover:text-white bg-white group-has-[:checked]:bg-[#5B86EF] group-has-[:checked]:text-white group-has-[:disabled]:bg-[#EEEFF4] group-has-[:disabled]:text-[#AAADBF]">
                                    {{ substr($time, 0, 5) }}
                                </div>
                                <input type="radio" name="time_at" value="{{ $time }}"
                                    class="absolute top-1/2 left-1/2 -z-10" required>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="Name" class="font-semibold">Nama Anda</label>
                    <div
                        class="rounded-full flex items-center ring-1 ring-[#E9E8ED] p-[12px_16px] bg-white w-full transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FF8E62]">
                        <div class="w-6 h-6 flex shrink-0 mr-[10px]">
                            <img src="{{asset('assets/images/icons/profile-circle.svg')}}" alt="icon">
                        </div>
                        <input type="text" name="name" id="Name"
                            class="appearance-none outline-none w-full font-semibold placeholder:font-normal placeholder:text-[#909DBF]"
                            placeholder="Silahkan Isi Nama" required>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <label for="Phone" class="font-semibold">Nomer Hp</label>
                    <div
                        class="rounded-full flex items-center ring-1 ring-[#E9E8ED] p-[12px_16px] bg-white w-full transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FF8E62]">
                        <div class="w-6 h-6 flex shrink-0 mr-[10px]">
                            <img src="{{asset('assets/images/icons/call.svg')}}" alt="icon">
                        </div>
                        <input type="tel" name="phone_number" id="Phone"
                            class="appearance-none outline-none w-full font-semibold placeholder:font-normal placeholder:text-[#909DBF]"
                            placeholder="Silahkan Isi Nomer Hp" required>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <label for="Catatan" class="font-semibold">Catatan</label>
                    <div
                        class="rounded-full flex items-center ring-1 ring-[#E9E8ED] p-[12px_16px] bg-white w-full transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FF8E62]">
                        <div class="w-6 h-6 flex shrink-0 mr-[10px]">
                            <img src="{{asset('assets/images/icons/note-favorite-normal.svg')}}" alt="icon">
                        </div>
                        <textarea name="catatan" id="Catatan" rows="3"
                            class="appearance-none outline-none w-full font-semibold placeholder:font-normal placeholder:text-[#909DBF] resize-none"
                            placeholder="Contoh: Pengerjaan bagian ini saja, atau informasi tambahan lainnya"></textarea>
                    </div>
                    <p class="text-xs text-[#909DBF] px-2">Tambahkan catatan khusus untuk layanan Anda (maksimal 500
                        karakter)</p>
                </div>
                <hr class="border-[#E9E8ED]">

                <div id="CTA" class="w-full flex items-center justify-between bg-white">
                    @if($service->hasVariants())
                    <div class="flex flex-col gap-[2px]">
                        <p class="font-bold text-xl leading-[30px]" id="cta-price">Pilih variant</p>
                    </div>
                    <button type="submit" id="submit-btn" disabled
                        class="rounded-full p-[12px_20px] font-bold text-white transition-all duration-300 bg-gray-400 cursor-not-allowed">
                        Pesan Sekarang
                    </button>
                    @else
                    <div class="flex flex-col gap-[2px]">
                        <p class="font-bold text-xl leading-[30px]">Rp {{number_format($service->harga,0,',','.')}}
                        </p>
                    </div>
                    <button type="submit" class="rounded-full p-[12px_20px] bg-[#FF8E62] font-bold text-white">
                        Pesan Sekarang
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </main>
    @if($service->hasVariants())
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const variantRadios = document.querySelectorAll('.variant-radio');
        const priceDisplay = document.getElementById('price-display');
        const selectedPrice = document.getElementById('selected-price');
        const ctaPrice = document.getElementById('cta-price');
        const submitBtn = document.getElementById('submit-btn');

        variantRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const price = parseInt(this.dataset.price);
                const formattedPrice = 'Rp ' + price.toLocaleString('id-ID');

                selectedPrice.textContent = formattedPrice;
                ctaPrice.textContent = formattedPrice;
                priceDisplay.classList.remove('hidden');

                // Enable submit button
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.add('bg-[#FF8E62]');
            });
        });
    });
    </script>
    @endif
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{asset('customjs/booking.js') }}"></script>
</body>

</html>