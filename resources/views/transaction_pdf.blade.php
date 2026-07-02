<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        h2, h3 {
            text-align: center;
            margin: 5px 0;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-green {
            color: #10B981;
        }
        .text-red {
            color: #EF4444;
        }
        .summary {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        .summary table {
            border: none;
        }
        .summary th, .summary td {
            border: none;
            padding: 5px;
        }
        .summary th {
            text-align: left;
            background: none;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>MINI ERP - MONEY TRACK</h2>
        <h3>Laporan Transaksi Keuangan</h3>
        <p class="text-center">Periode: {{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="20%">Kategori</th>
                <th width="35%">Deskripsi</th>
                <th width="25%" class="text-right">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalIncome = 0;
                $totalExpense = 0;
            @endphp

            @forelse($transactions as $index => $trx)
                @php
                    $isIncome = optional($trx->category)->type === 'income';
                    if ($isIncome) {
                        $totalIncome += $trx->amount;
                    } else {
                        $totalExpense += $trx->amount;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->trans_date)->format('d/m/Y') }}</td>
                    <td>{{ optional($trx->category)->cat_name ?? '-' }} ({{ $isIncome ? 'Pemasukan' : 'Pengeluaran' }})</td>
                    <td>{{ $trx->desc }}</td>
                    <td class="text-right {{ $isIncome ? 'text-green' : 'text-red' }}">
                        {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada transaksi pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <th>Total Pemasukan:</th>
                <td class="text-right text-green">Rp {{ number_format($totalIncome, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Pengeluaran:</th>
                <td class="text-right text-red">Rp {{ number_format($totalExpense, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th style="border-top: 1px solid #333;">Saldo Bersih:</th>
                <td class="text-right" style="border-top: 1px solid #333; font-weight: bold;">
                    Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
