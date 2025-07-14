<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice #ATK-2025-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info table {
            width: 100%;
        }

        .info td {
            padding: 4px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #999;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #eee;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 15px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        .highlight {
            color: #007bff;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Invoice Pembelian</h1>
        <p><strong>Kode Transaksi:</strong> ATK-2025-{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td><strong>Nama Customer:</strong></td>
                <td>{{ $transaksi->customer->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Pemesanan:</strong></td>
                <td>{{ $transaksi->created_at->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>{{ ucfirst($transaksi->status) }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga Satuan</th>
                <th>Subtotal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransactions as $item)
                <tr>
                    <td>{{ $item->product->nama ?? 'Produk dihapus' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>
                        Rp{{ number_format(
                            isset($item->harga_satuan) ? $item->harga_satuan : $item->total_harga / max($item->qty, 1),
                            0,
                            ',',
                            '.',
                        ) }}
                    </td>
                    <td>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Pembayaran: <span class="highlight">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
    </div>

    <div class="footer">
        Tunjukkan invoice ini saat pengambilan barang. <br>
        Terima kasih telah berbelanja di <strong>ATK Store</strong>. <br>
        {{ now()->format('d M Y H:i') }}
    </div>

</body>

</html>
