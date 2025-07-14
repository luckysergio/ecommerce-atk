<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Selesai</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        .info p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 10px;
        }

        table,
        th,
        td {
            border: 1px solid #999;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: left;
        }

        th,
        td {
            padding: 8px;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }

        .transaction-separator {
            margin: 20px 0;
            border-top: 2px dashed #ccc;
        }
    </style>
</head>

<body>
    <h2>Laporan Transaksi Selesai</h2>

    <div class="info">
        <p><strong>Bulan:</strong>
            {{ $bulan ? \Carbon\Carbon::create()->month((int) $bulan)->locale('id')->translatedFormat('F') : '-' }}</p>
        <p><strong>Tahun:</strong> {{ $tahun ?? '-' }}</p>
    </div>

    @foreach ($transactions as $trx)
        <div>
            <p><strong>ID Transaksi:</strong> ATK-2025-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</p>
            <p><strong>Tanggal:</strong> {{ $trx->created_at->format('d M Y, H:i') }}</p>
            <p><strong>Pemesan:</strong> {{ $trx->customer->nama ?? '-' }}</p>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trx->detailTransactions as $detail)
                        <tr>
                            <td>{{ $detail->product->nama ?? 'Produk dihapus' }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                Total: Rp{{ number_format($trx->total_harga, 0, ',', '.') }}
            </div>
        </div>

        <div class="transaction-separator"></div>
    @endforeach
</body>

</html>
