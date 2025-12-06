<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingPaymentRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\KelolaPemesanan;
use App\Models\ServisMobil;
use App\Models\Bengkel;
use App\Models\Kota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use App\Models\Store;

use function Livewire\store;

class FrontController extends Controller
{
    // ==============================
    // 1️⃣ Halaman utama (Home Page)
    // ==============================
    /**
     * FUNGSI: Menampilkan halaman home dengan daftar kota dan jenis servis
     * AKSI: GET /
     * RESPONSIF: Menampilkan view 'front.index' dengan data kota, jenis servis, dan bengkel pertama
     */
    public function index()
    {
        // Ambil semua kota dari database
        $cities = Kota::all();

        // Ambil semua jenis servis mobil beserta jumlah relasi 'servis' yang dimiliki
        $services = ServisMobil::withCount(['servis'])->get();

        // Ambil satu bengkel pertama sebagai default (jika belum memilih)
        $Bengkel = Bengkel::first();

        // Kirim data ke view front.index
        return view('front.index', compact('cities', 'services', 'Bengkel'));
    }

    /**
     * FUNGSI: Redirect ke halaman detail bengkel dan simpan service type ke session
     * AKSI: POST /redirect-to-store
     * RESPONSIF: Redirect ke halaman detail bengkel dengan slug
     */
    public function redirectToStore(Request $request)
    {
        $bengkel = Bengkel::first();

        if (!$bengkel) {
            return redirect()->route('front.index')->with('error', 'Bengkel tidak ditemukan');
        }

        session()->put('serviceTypeId', $request->service_type);

        return redirect()->route('front.details', ['Bengkel' => $bengkel->slug]);
    }

    // ===========================================
    // 2️⃣ Pencarian bengkel berdasarkan kota & jenis servis
    // ===========================================
    /**
     * FUNGSI: Mencari bengkel berdasarkan kota dan jenis servis yang dipilih
     * AKSI: POST /search
     * RESPONSIF: Menampilkan view 'front.stores' dengan daftar bengkel yang sesuai
     * FITUR: Menyimpan service type ke session untuk digunakan di halaman berikutnya
     */
    public function search(Request $request)
    {
        // Ambil inputan kota dan jenis servis dari form
        $cityId = $request->input('city_id');
        $serviceTypeId = $request->input('service_type');

        // Cari jenis servis berdasarkan ID
        $ServisMobil = ServisMobil::where('id', $serviceTypeId)->first();
        if (!$ServisMobil) {
            return redirect()->back()->with('error', 'Jenis servis tidak ditemukan.');
        }

        // Cari bengkel yang menyediakan servis tersebut dan berada di kota yang dipilih
        $bengkel = Bengkel::whereHas('servis', function ($query) use ($ServisMobil) {
            $query->where('servis_mobil_id', $ServisMobil->id);
        })->where('kota_id', $cityId)->get();

        // Ambil nama kota untuk ditampilkan di halaman hasil
        $Kota = Kota::find($cityId);

        // Simpan ID jenis servis yang dipilih ke session agar tetap terbawa antar halaman
        session()->put('serviceTypeId', $request->input('service_type'));

        // Tampilkan halaman daftar bengkel
        return view('front.stores', [
            'bengkel' => $bengkel,
            'ServisMobil' => $ServisMobil,
            'cityName' => $Kota ? $Kota->nama : 'Kota tidak diketahui',
        ]);
    }

    // =================================================
    // 3️⃣ Menampilkan halaman detail dari bengkel tertentu
    // =================================================
    /**
     * FUNGSI: Menampilkan detail bengkel beserta review dan rating
     * AKSI: GET /bengkel/{slug}
     * RESPONSIF: Menampilkan view 'front.details' dengan data bengkel, servis, dan review
     * FITUR: Menghitung rata-rata rating dan total jumlah ulasan
     */
    public function details(Bengkel $Bengkel)
    {
        $serviceTypeId = session()->get('serviceTypeId');
        $ServisMobil = ServisMobil::with('variants')->where('id', $serviceTypeId)->first();
        
        // Ambil review/rating dari pemesanan yang sudah selesai dan memiliki rating
        $reviews = KelolaPemesanan::with('service_details', 'variant_details')
            ->where('bengkel_id', $Bengkel->id)    // Hanya untuk bengkel ini
            ->whereNotNull('rating')              // Yang sudah ada ratingnya
            ->orderBy('created_at', 'desc')       // Urutkan dari yang terbaru
            ->get();

        // Hitung rata-rata rating dan total ulasan
        $averageRating = $reviews->avg('rating');
        $totalReviews = $reviews->count();

        return view('front.details', compact(
            'Bengkel',
            'ServisMobil',
            'reviews',          // Daftar review/ulasan
            'averageRating',    // Rata-rata rating
            'totalReviews'      // Total jumlah ulasan
        ));
    }

    // ===================================================
    // 4️⃣ Rating & Review (Lihat, Tambah, Edit, Update)
    // ===================================================
    /**
     * FUNGSI: Menampilkan halaman form rating/review untuk transaksi tertentu
     * AKSI: GET /rating/{trx_id}
     * RESPONSIF: Menampilkan view 'front.rating' dengan form untuk memberikan rating
     * VALIDASI: Hanya bisa rating jika pembayaran sudah dikonfirmasi
     */
    public function rating($trx_id)
    {
        $booking = KelolaPemesanan::with(['service_details', 'store_details'])
            ->where('trx_id', $trx_id)
            ->firstOrFail();

        // Hanya bisa rating jika sudah dibayar
        if (!$booking->status_pembayaran) {
            return redirect()->route('front.transactions')
                ->withErrors(['error' => 'Booking harus sudah dibayar untuk memberikan rating.']);
        }

        return view('front.rating', compact('booking'));
    }

    /**
     * FUNGSI: Menyimpan rating dan komentar untuk transaksi tertentu
     * AKSI: POST /rating/store
     * RESPONSIF: Menyimpan ke database dan redirect ke halaman rating dengan pesan sukses
     * VALIDASI: Rating harus 1-5, komentar maksimal 1000 karakter
     */
    public function rating_store(Request $request)
    {
        $request->validate([
            'trx_id' => 'required|exists:kelola_pemesanan,trx_id',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        $booking = KelolaPemesanan::where('trx_id', $request->trx_id)->first();

        if (!$booking->status_pembayaran) {
            return redirect()->back()
                ->withErrors(['error' => 'Booking harus sudah dibayar untuk memberikan rating.']);
        }

        $booking->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('front.rating', $request->trx_id)
            ->with('success', 'Terima kasih atas rating dan ulasan Anda!');
    }

    /**
     * FUNGSI: Menampilkan halaman edit rating/review yang sudah ada
     * AKSI: GET /rating/{trx_id}/edit
     * RESPONSIF: Menampilkan view 'front.rating_edit' dengan form yang sudah diisi data lama
     */
    public function rating_edit($trx_id)
    {
        $booking = KelolaPemesanan::with(['service_details', 'store_details'])
            ->where('trx_id', $trx_id)
            ->firstOrFail();

        return view('front.rating_edit', compact('booking'));
    }

    /**
     * FUNGSI: Mengupdate rating dan komentar yang sudah pernah diberikan
     * AKSI: PATCH/PUT /rating/{trx_id}/update
     * RESPONSIF: Update data ke database dan redirect ke halaman rating dengan pesan sukses
     * VALIDASI: Rating harus 1-5, komentar maksimal 1000 karakter
     */
    public function rating_update(Request $request, $trx_id)
    {
        // 1. Validasi data
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        // 2. Cari booking yang ingin di-update
        $booking = KelolaPemesanan::where('trx_id', $trx_id)->firstOrFail();

        // 3. Update data booking dengan data baru
        $booking->update([
            'rating' => $request->rating,
            'komentar' => $request->komentar,
        ]);

        // 4. Redirect kembali ke halaman rating dengan pesan sukses
        return redirect()->route('front.rating', $booking->trx_id)
            ->with('success', 'Rating Anda berhasil diperbarui.');
    }

    // ===================================================
    // 5️⃣ Pemesanan/Booking (Form & Pembayaran)
    // ===================================================
    /**
     * FUNGSI: Menampilkan halaman form pemesanan untuk bengkel tertentu
     * AKSI: GET /booking/{bengkel_slug}
     * RESPONSIF: Menampilkan view 'front.booking' dengan form pemesanan
     * FITUR: Menyimpan bengkel_id ke session untuk digunakan di halaman berikutnya
     */
    public function booking(Bengkel $Bengkel)
    {
        session()->put('carStoreId', $Bengkel->id);
        $serviceTypeId = session()->get('serviceTypeId');
        $service = ServisMobil::with('variants')->where('id', $serviceTypeId)->first();

        return view('front.booking', compact('Bengkel', 'service'));
    }

    /**
     * FUNGSI: Memproses data form pemesanan dan menyimpannya ke session
     * AKSI: POST /booking/store
     * RESPONSIF: Menyimpan data ke session dan redirect ke halaman pembayaran
     * DATA YANG DISIMPAN: Nama, nomor HP, waktu, tanggal, variant, catatan, bengkel_id, service_type
     */
    public function booking_store(StoreBookingRequest $request)
    {
        $customerName = $request->input('name');
        $customerPhoneNumber = $request->input('phone_number');
        $customerTimeAt = $request->input('time_at');
        $customerstarted_at = $request->input('started_at');
        $variantId = $request->input('variant_id');
        $customerCatatan = $request->input('catatan');

        // Simpan semua data ke session untuk diambil di halaman berikutnya
        session()->put('customerName', $customerName);
        session()->put('customerPhoneNumber', $customerPhoneNumber);
        session()->put('customerTimeAt', $customerTimeAt);
        session()->put('customerstarted_at', $customerstarted_at);
        session()->put('variantId', $variantId);
        session()->put('customerCatatan', $customerCatatan);

        $serviceTypeId = session()->get('serviceTypeId');
        $carStoreId = session()->get('carStoreId');

        return redirect()->route('front.booking.payment', [$carStoreId, $serviceTypeId]);
    }

    /**
     * FUNGSI: Menampilkan halaman pembayaran dengan rincian harga
     * AKSI: GET /booking/payment/{bengkel_id}/{service_type_id}
     * RESPONSIF: Menampilkan view 'front.payment' dengan detail harga dan variant
     * FITUR: Menghitung total harga (harga service + PPN + booking fee)
     */
    public function booking_payment(Bengkel $Bengkel, ServisMobil $ServisMobil)
    {
        $variantId = session()->get('variantId');
        $selectedVariant = null;

        // Jika ada variant yang dipilih, gunakan harga variant
        if ($variantId) {
            $variant = \App\Models\ServisVariant::find($variantId);
            $harga = $variant ? $variant->harga : $ServisMobil->harga;
            $selectedVariant = $variant;
        } else {
            $harga = $ServisMobil->harga;
        }

        // Hitung PPN dan biaya booking
        $ppn = 0;
        $totalPpn = $harga * $ppn;
        $bookingFee = 0;
        $totalGrandTotal = $totalPpn + $bookingFee + $harga;

        // Simpan total amount ke session
        session()->put('totalAmount', $totalGrandTotal);

        return view('front.payment', compact('ServisMobil', 'Bengkel', 'totalPpn', 'bookingFee', 'totalGrandTotal', 'selectedVariant'));
    }

    /**
     * FUNGSI: Memproses pembayaran dan membuat record booking di database
     * AKSI: POST /booking/payment/store
     * RESPONSIF: Membuat record pemesanan baru dengan status pembayaran = false, dan redirect ke halaman sukses
     * FITUR: 
     *   - Validasi file bukti pembayaran
     *   - Menyimpan bukti pembayaran ke storage/public/proofs
     *   - Menggunakan database transaction untuk memastikan data tersimpan dengan aman
     *   - Generate unique transaction ID
     */
    public function booking_payment_store(StoreBookingPaymentRequest $request)
    {
        // Ambil semua data dari session yang disimpan sebelumnya
        $customerName = session()->get('customerName');
        $customerPhoneNumber = session()->get('customerPhoneNumber');
        $totalAmount = session()->get('totalAmount');
        $customerTimeAt = session()->get('customerTimeAt');
        $customerstarted_at = session()->get('customerstarted_at');
        $customerCatatan = session()->get('customerCatatan');
        $serviceTypeId = session()->get('serviceTypeId');
        $carStoreId = session()->get('carStoreId');
        $variantId = session()->get('variantId');

        $bookingTransactionId = null;

        // Gunakan database transaction agar data tersimpan dengan aman
        FacadesDB::transaction(function () use (
            $request,
            $totalAmount,
            $customerName,
            $customerPhoneNumber,
            $customerTimeAt,
            $customerstarted_at,
            $customerCatatan,
            $serviceTypeId,
            $carStoreId,
            $variantId,
            &$bookingTransactionId
        ) {
            $validated = $request->validated();

        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Simpan dengan compressed (gunakan GD/ImageMagick default Laravel)
            $file->storeAs('proofs', $filename, 'public');
            
            $validated['bukti'] = 'proofs/' . $filename;
        }


            // Assign data customer dan pemesanan ke array validated
            $validated['nama'] = $customerName;
            $validated['total_bayar'] = $totalAmount;
            $validated['nomer_telepon'] = $customerPhoneNumber;
            $validated['waktu_mulai'] = $customerstarted_at;
            $validated['jam_mulai'] = $customerTimeAt;
            $validated['catatan'] = $customerCatatan;
            $validated['servis_mobil_id'] = $serviceTypeId;
            $validated['servis_variant_id'] = $variantId;
            $validated['bengkel_id'] = $carStoreId;
            $validated['status_pembayaran'] = false; // Status awal: belum dibayar
            $validated['trx_id'] = KelolaPemesanan::generateUniqueTrxId(); // Generate unique ID

            // Buat record pemesanan baru di database
            $newBooking = KelolaPemesanan::create($validated);
            $bookingTransactionId = $newBooking->id;
        });

        return redirect()->route('front.success.booking', $bookingTransactionId);
    }

    // ===================================================
    // 6️⃣ Riwayat & Detail Transaksi
    // ===================================================
    /**
     * FUNGSI: Mencari dan menampilkan detail transaksi berdasarkan nomor transaksi dan nomor HP
     * AKSI: POST /transaction/details
     * RESPONSIF: Menampilkan view 'front.transactions_details' dengan detail transaksi lengkap
     * FITUR: Validasi 2 field (trx_id dan nomer_telepon) untuk keamanan
     */
    public function transaction_details(Request $request)
    {
        $request->validate([
            'trx_id' => ['required', 'string', 'max:255'],
            'nomer_telepon' => ['required', 'string', 'max:255'],
        ]);

        $trx_id = $request->input('trx_id');
        $nomer_telepon = $request->input('nomer_telepon');

        // Cari transaksi berdasarkan trx_id dan nomor telepon untuk keamanan
        $details = KelolaPemesanan::with(['service_details', 'store_details', 'variant_details'])
            ->where('trx_id', $trx_id)
            ->where('nomer_telepon', $nomer_telepon)
            ->first();

        if (!$details) {
            return redirect()->back()->withErrors(['error' => 'Transaksi tidak ditemukan.']);
        }

        $ppn = 0;
        $totalPpn = $details->service_details->harga * $ppn;
        $bookingFee = 0;

        return view("front.transactions_details", compact('details', 'totalPpn', 'bookingFee'));
    }

    /**
     * FUNGSI: Menampilkan halaman sukses setelah pemesanan berhasil dibuat
     * AKSI: GET /booking/success/{booking_id}
     * RESPONSIF: Menampilkan view 'front.success_booking' dengan detail pemesanan
     */
    public function success_booking(KelolaPemesanan $KelolaPemesanan)
    {
        return view('front.success_booking', compact('KelolaPemesanan'));
    }

    /**
     * FUNGSI: Menampilkan halaman pencarian riwayat transaksi
     * AKSI: GET /transactions
     * RESPONSIF: Menampilkan view 'front.transactions' dengan form pencarian
     * FITUR: Mengambil bengkel dari session atau ambil bengkel pertama dari database
     */
    public function transactions()
    {
        // Ambil data bengkel dari session
        $Bengkel = null;
        if (session()->has('carStoreId')) {
            $Bengkel = Bengkel::find(session('carStoreId'));
        }

        // Jika belum ada bengkel di session, ambil yang pertama dari database
        if (!$Bengkel) {
            $Bengkel = Bengkel::first();
        }

        // Tampilkan halaman pencarian transaksi
        return view('front.transactions', compact('Bengkel'));
    }

    /**
     * FUNGSI: Menampilkan halaman invoice/kwitansi untuk transaksi tertentu
     * AKSI: GET /invoice/{trx_id}
     * RESPONSIF: Menampilkan view 'front.invoice' dengan detail transaksi untuk dicetak
     * FITUR: Mengambil semua relasi yang diperlukan (bengkel, servis, variant)
     */
    public function invoice($trx_id)
    {
        // Ambil data booking/transaksi dari database beserta relasi
        $booking = KelolaPemesanan::with(['store_details', 'service_details', 'variant_details'])
            ->where('trx_id', $trx_id)
            ->firstOrFail(); // Otomatis error 404 jika data tidak ditemukan

        // Tampilkan view invoice
        return view('front.invoice', compact('booking'));
    }
}