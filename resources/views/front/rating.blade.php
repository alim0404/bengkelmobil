<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{asset('output.css')}}" rel="stylesheet">
    <link href="{{asset('main.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <style>
    .star-rating {
        direction: rtl;
        display: inline-flex;
        gap: 0.5rem;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        font-size: 2.5rem;

        /* 1. Kita set warna dasarnya KUNING */
        color: #FFD700;

        /* 2. Tapi kita buat transparan (pudar) */
        opacity: 0.3;

        /* 3. Efek transisi agar mulus */
        transition: opacity 0.2s ease-in-out;
    }

    /* 4. Saat di-hover atau dipilih */
    .star-rating input:checked~label,
    .star-rating label:hover,
    .star-rating label:hover~label {

        /* 5. Opacity-nya kita kembalikan ke 100% (terlihat penuh) */
        opacity: 1;
    }
    </style>
</head>

<body>
    <main class="bg-[#FAFAFA] max-w-[640px] mx-auto min-h-screen relative flex flex-col">
        <div id="Top-nav" class="flex items-center justify-between px-4 pt-5">
            <a href="{{ route('front.transactions') }}">
                <div class="w-10 h-10 flex shrink-0">
                    <img src="{{asset('assets/images/icons/back.svg')}}" alt="icon">
                </div>
            </a>
            <div class="flex flex-col w-fit text-center">
                <h1 class="font-semibold text-lg leading-[27px]">Rating & Ulasan</h1>
                <p class="text-sm leading-[21px] text-[#909DBF]">Berikan penilaian Anda</p>
            </div>
            <div class="w-10 h-10 flex shrink-0"></div>
        </div>

        <div class="flex flex-col items-center gap-6 max-w-[330px] m-auto h-fit w-full py-6 px-4">
            <div class="w-[120px] h-[120px] flex shrink-0">
                <img src="{{ Storage::url($booking->service_details->icon) }}" class="w-full h-full object-contain"
                    alt="icon">
            </div>

            <div class="w-full rounded-2xl border border-[#E9E8ED] p-4 flex flex-col gap-2 bg-white">
                <p class="font-semibold text-center">{{ $booking->service_details->nama }}</p>
                <p class="text-sm text-[#909DBF] text-center">{{ $booking->store_details->nama }}</p>
                <div class="flex items-center justify-center gap-2 mt-2">
                    <p class="text-xs text-[#909DBF]">ID: {{ $booking->trx_id }}</p>
                </div>
            </div>

            @if(session('success'))
            <div class="w-full rounded-2xl border border-green-200 p-4 bg-green-50">
                <p class="text-green-700 text-center">{{ session('success') }}</p>
            </div>
            @endif

            @if($booking->rating)
            <div class="w-full rounded-2xl border border-[#E9E8ED] p-4 bg-white flex flex-col items-center gap-4">

                <p class="font-semibold text-center">Rating Anda</p>

                <div class="flex justify-center">
                    <div class="text-2xl">
                        @for($i = 1; $i <= 5; $i++) <span
                            class="{{ $i <= $booking->rating ? 'text-[#FFD700]' : 'text-gray-300' }}">⭐</span>
                            @endfor
                    </div>
                </div>

                @if($booking->komentar)
                <p class="text-sm text-gray-700 text-center">
                    {{ $booking->komentar }}
                </p>
                @endif

                <a href="{{ route('front.rating.edit', $booking->trx_id) }}"
                    class="block text-center w-full rounded-full p-[12px_20px] border border-[#FF8E62] text-[#FF8E62] font-bold">
                    Ubah Rating
                </a>
            </div>

            @else
            <form method="POST" action="{{ route('front.rating.store') }}" class="w-full flex flex-col gap-6">
                @csrf
                <input type="hidden" name="trx_id" value="{{ $booking->trx_id }}">

                <div class="w-full rounded-2xl border border-[#E9E8ED] p-6 bg-white">
                    <p class="font-semibold text-center mb-4">Berikan Rating Anda</p>
                    <div class="flex justify-center">
                        <div class="star-rating">
                            <input type="radio" name="rating" value="5" id="star5" required>
                            <label for="star5">⭐</label>
                            <input type="radio" name="rating" value="4" id="star4">
                            <label for="star4">⭐</label>
                            <input type="radio" name="rating" value="3" id="star3">
                            <label for="star3">⭐</label>
                            <input type="radio" name="rating" value="2" id="star2">
                            <label for="star2">⭐</label>
                            <input type="radio" name="rating" value="1" id="star1">
                            <label for="star1">⭐</label>
                        </div>
                    </div>
                    @error('rating')
                    <p class="text-red-500 text-sm text-center mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full rounded-2xl border border-[#E9E8ED] p-6 bg-white">
                    <p class="font-semibold block mb-3 text-center">
                        <label for="komentar">Tulis Ulasan Anda</label>
                    </p>
                    <textarea name="komentar" id="komentar" rows="5"
                        class="w-full rounded-xl border border-[#E9E8ED] p-4 outline-none focus:ring-2 focus:ring-[#FF8E62] resize-none"
                        placeholder="Ceritakan pengalaman Anda menggunakan layanan kami..."></textarea>
                    @error('komentar')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full rounded-full p-[12px_20px] bg-[#FF8E62] font-bold text-white">
                    Kirim Rating
                </button>
            </form>
            @endif

            <a href="{{ route('front.transactions') }}"
                class="w-full rounded-full border border-[#E9E8ED] p-[12px_20px] bg-white text-center font-bold">
                Kembali
            </a>
        </div>
    </main>
</body>

</html>