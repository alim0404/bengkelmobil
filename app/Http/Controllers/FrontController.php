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
    public function details(Bengkel $Bengkel)
    {
        $serviceTypeId = session()->get('serviceTypeId');
        $ServisMobil = ServisMobil::with('variants')->where('id', $serviceTypeId)->first();

        return view('front.details', compact('Bengkel', 'ServisMobil'));
    }

    public function booking(Bengkel $Bengkel)
    {
        session()->put('carStoreId', $Bengkel->id);
        $serviceTypeId = session()->get('serviceTypeId');
        $service = ServisMobil::with('variants')->where('id', $serviceTypeId)->first();

        return view('front.booking', compact('Bengkel', 'service'));
    }

    public function booking_store(StoreBookingRequest $request)
    {
        $customerName = $request->input('name');
        $customerPhoneNumber = $request->input('phone_number');
        $customerTimeAt = $request->input('time_at');
        $customerstarted_at = $request->input('started_at');
        $variantId = $request->input('variant_id'); // Tambahkan ini
        $customerCatatan = $request->input('catatan');

        session()->put('customerName', $customerName);
        session()->put('customerPhoneNumber', $customerPhoneNumber);
        session()->put('customerTimeAt', $customerTimeAt);
        session()->put('customerstarted_at', $customerstarted_at);
        session()->put('variantId', $variantId); // Tambahkan ini
        session()->put('customerCatatan', $customerCatatan);

        $serviceTypeId = session()->get('serviceTypeId');
        $carStoreId = session()->get('carStoreId');

        return redirect()->route('front.booking.payment', [$carStoreId, $serviceTypeId]);
    }

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

        $ppn = 0;
        $totalPpn = $harga * $ppn;
        $bookingFee = 0;
        $totalGrandTotal = $totalPpn + $bookingFee + $harga;

        session()->put('totalAmount', $totalGrandTotal);

        return view('front.payment', compact('ServisMobil', 'Bengkel', 'totalPpn', 'bookingFee', 'totalGrandTotal', 'selectedVariant'));
    }

    public function booking_payment_store(StoreBookingPaymentRequest $request)
    {
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
                $proofPath = $request->file('proof')->store('proofs', 'public');
                $validated['bukti'] = $proofPath;
            }

            $validated['nama'] = $customerName;
            $validated['total_bayar'] = $totalAmount;
            $validated['nomer_telepon'] = $customerPhoneNumber;
            $validated['waktu_mulai'] = $customerstarted_at;
            $validated['jam_mulai'] = $customerTimeAt;
            $validated['catatan'] = $customerCatatan;
            $validated['servis_mobil_id'] = $serviceTypeId;
            $validated['servis_variant_id'] = $variantId; // Tambahkan ini
            $validated['bengkel_id'] = $carStoreId;
            $validated['status_pembayaran'] = false;
            $validated['trx_id'] = KelolaPemesanan::generateUniqueTrxId();

            $newBooking = KelolaPemesanan::create($validated);
            $bookingTransactionId = $newBooking->id;
        });

        return redirect()->route('front.success.booking', $bookingTransactionId);
    }

    public function transaction_details(Request $request)
    {
        $request->validate([
            'trx_id' => ['required', 'string', 'max:255'],
            'nomer_telepon' => ['required', 'string', 'max:255'],
        ]);

        $trx_id = $request->input('trx_id');
        $nomer_telepon = $request->input('nomer_telepon');

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
    public function success_booking(KelolaPemesanan $KelolaPemesanan)
    {
        return view('front.success_booking', compact('KelolaPemesanan'));
    }
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
}