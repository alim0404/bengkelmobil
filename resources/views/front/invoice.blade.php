<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $booking->trx_id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
    /* CSS ini dirancang agar bersih di layar dan saat dicetak */

    @media print {}

    /* ⬇️ INI BAGIAN PENTINGNYA ⬇️
          @page memberitahu browser cara mengatur halaman cetak.
          'margin: 0;' memerintahkan browser untuk menghapus 
          margin default, tempat header/footer itu berada.
        */
    @page {
        size: A4;
        margin: 0;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #F8F8F8;
        color: #333;
        margin: 0;
        padding: 20px;
    }

    .invoice-container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #FFFFFF;
        border: 1px solid #E0E0E0;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .invoice-header {
        padding: 24px;
        background-color: #FAFAFA;
        border-bottom: 1px solid #E0E0E0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .invoice-header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #111;
    }

    .invoice-header .trx-id {
        font-size: 24px;
        color: #111;
        font-weight: 700;
        /* <--- GANTI MENJADI 700 (ATAU 'bold') */
    }

    .invoice-details {
        display: flex;
        justify-content: space-between;
        padding: 24px;
        flex-wrap: wrap;
    }

    .invoice-details .column {
        width: 48%;
        margin-bottom: 16px;
    }

    .invoice-details h3 {
        margin: 0 0 8px 0;
        font-size: 14px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
    }

    .invoice-details p {
        margin: 4px 0;
        font-size: 15px;
        line-height: 1.6;
    }

    .invoice-status {
        font-weight: 700;
        padding: 6px 10px;
        border-radius: 8px;
        display: inline-block;
    }

    .status-lunas {
        color: #2E7D32;
        background-color: #E8F5E9;
    }

    .status-belum-lunas {
        color: #C62828;
        background-color: #FFEBEE;
    }

    .invoice-items {
        padding: 0 24px 24px 24px;
    }

    .invoice-items table {
        width: 100%;
        border-collapse: collapse;
    }

    .invoice-items th,
    .invoice-items td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid #E0E0E0;
    }

    .invoice-items th {
        font-size: 13px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
    }

    .invoice-items .item-name strong {
        display: block;
        font-weight: 600;
        font-size: 16px;
    }

    .invoice-items .item-name span {
        font-size: 14px;
        color: #555;
    }

    .invoice-items .text-right {
        text-align: right;
    }

    .invoice-total {
        padding: 24px;
        text-align: right;
        background-color: #FAFAFA;
        border-top: 1px solid #E0E0E0;
    }

    .invoice-total table {
        width: 50%;
        margin-left: auto;
        border-collapse: collapse;
    }

    .invoice-total td {
        padding: 8px 0;
    }

    .invoice-total .total-label {
        text-align: right;
        color: #555;
    }

    .invoice-total .total-value {
        text-align: right;
        font-weight: 600;
        padding-left: 20px;
    }

    .invoice-total .grand-total .total-label {
        font-size: 18px;
        font-weight: 700;
        color: #111;
    }

    .invoice-total .grand-total .total-value {
        font-size: 20px;
        font-weight: 700;
        color: #FF8E62;
        /* Warna utama Anda */
    }

    .print-button-container {
        text-align: center;
        padding: 24px;
    }

    .print-button {
        font-family: 'Poppins', sans-serif;
        background-color: #FF8E62;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
    }

    .invoice-footer-note {
        padding: 24px;
        text-align: center;
        /* <-- TAMBAHKAN BARIS INI */
        border-top: 1px solid #E0E0E0;
        background-color: #FAFAFA;
    }

    .invoice-footer-note p {
        margin: 0;
        font-size: 14px;
        color: #333;
        line-height: 1.6;
    }

    /* CSS KHUSUS SAAT PRINT */
    @media print {
        body {
            background-color: #FFFFFF;
            padding: 0;
            margin: 0;
        }

        .invoice-container {
            max-width: 100%;
            margin: 0;
            box-shadow: none;
            border: none;
        }

        .print-button-container {
            display: none;
            /* Sembunyikan tombol saat print */
        }
    }

    .invoice-items .invoice-grand-total-row td {
        border-top: 2px solid #E0E0E0;
        /* Memberi garis pemisah tebal */
        padding-top: 14px;
        font-size: 17px;
        /* Sedikit lebih besar */
        color: #111;
    }

    .invoice-items .invoice-grand-total-row .text-right {
        font-size: 18px;
        color: #FF8E62;
        /* Memberi warna pada harga total */
    }
    </style>
</head>

<body>

    <div class="invoice-container">

        <div class="invoice-header">
            <h1>INVOICE</h1>
            <span class="trx-id">ID: {{ $booking->trx_id }}</span>
        </div>

        <div class="invoice-details">
            <div class="column">
                <h3>Dibayar Kepada:</h3>
                <p>
                    <strong>{{ $booking->store_details->nama ?? 'Nama Bengkel' }}</strong><br>
                    {!! $booking->store_details->alamat ?? 'Alamat Bengkel' !!}
                </p>
            </div>
            <div class="column">
                <h3>Pelanggan:</h3>
                <p>
                    <strong>{{ $booking->nama }}</strong><br>
                    {{ $booking->nomer_telepon }}
                </p>
            </div>
            <div class="column">
                <h3>Jadwal Layanan:</h3>
                <p>
                    {{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('d F Y') }}<br>
                    Jam: {{ $booking->jam_mulai }} WITA
                </p>
            </div>
            <div class="column">
                <h3>Status Pembayaran:</h3>
                @if($booking->status_pembayaran)
                <span class="invoice-status status-lunas">LUNAS</span>
                @else
                <span class="invoice-status status-belum-lunas">BELUM LUNAS</span>
                @endif
            </div>
        </div>

        <div class="invoice-items">
            <table>
                <thead>
                    <tr>
                        <th>Deskripsi Layanan</th>
                        <th class="text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="item-name">
                            <strong>{{ $booking->service_details->nama ?? 'Nama Layanan' }}</strong>
                            @if($booking->variant_details)
                            <span>Variant: {{ $booking->variant_details->nama }}</span>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</td>
                    </tr>

                    <tr class="invoice-grand-total-row">
                        <td>
                            <strong>Total Biaya</strong>
                        </td>
                        <td class="text-right">
                            <strong>Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-footer-note">
            <p>
                <strong>PENTING: Harap Bawa dan Tunjukkan Invoice ini Saat Anda Tiba di Bengkel.</strong>
            </p>
        </div>
    </div>
    </div>



    <div class="print-button-container">
        <button class="print-button" onclick="window.print()">
            🖨️ Cetak Invoice
        </button>
    </div>

</body>

</html>