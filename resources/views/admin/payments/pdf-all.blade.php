<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Seluruh Pembayaran</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 12px; color: #1e293b; margin: 20px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
        .header h2 { margin: 0; font-size: 20px; color: #0f172a; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #64748b; font-size: 11px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 10px 8px; text-align: left; }
        th { background-color: #f1f5f9; font-weight: 700; color: #334155; font-size: 11px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f8fafc; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase; }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-warning { background-color: #fef9c3; color: #854d0e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        
        .no-print { margin-bottom: 20px; text-align: right; }
        .btn-print { padding: 8px 16px; background-color: #2563eb; color: #ffffff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; }
        
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨 Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h2>LAPORAN REKAPITULASI PEMBAYARAN</h2>
        <p>Dicetak pada tanggal: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="12%">ID Transaksi</th>
                <th width="20%">Perusahaan</th>
                <th width="20%">Freelancer</th>
                <th>Proyek</th>
                <th class="text-right" width="15%">Nominal</th>
                <th class="text-center" width="12%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $index => $payment)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>#{{ $payment->id }}</td>
                    <td>{{ $payment->company->name ?? ($payment->company->companyProfile->company_name ?? '-') }}</td>
                    <td>{{ $payment->freelancer->name ?? '-' }}</td>
                    <td>{{ $payment->workspace->project->project_name ?? '-' }}</td>
                    <td class="text-right">
                        Rp {{ number_format($payment->amount ?? $payment->nominal ?? $payment->freelancer_receive ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @if(in_array(strtolower($payment->status), ['paid', 'dibayar', 'selesai']))
                            <span class="badge badge-success">Lunas</span>
                        @elseif(in_array(strtolower($payment->status), ['waiting_verification', 'menunggu_verifikasi']))
                            <span class="badge badge-warning">Menunggu</span>
                        @else
                            <span class="badge badge-danger">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data transaksi pembayaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>