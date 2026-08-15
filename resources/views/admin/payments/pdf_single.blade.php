<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran #{{ $payment->id }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; color: #333; }
        .receipt-box { max-width: 500px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 8px; }
        .header { text-align: center; border-bottom: 2px dashed #bbb; padding-bottom: 10px; margin-bottom: 15px; }
        .row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
        .total { border-top: 2px dashed #bbb; padding-top: 10px; margin-top: 15px; font-weight: bold; font-size: 16px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .receipt-box { border: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨 Cetak Struk
        </button>
    </div>

    <div class="receipt-box">
        <div class="header">
            <h2>BUKTI PEMBAYARAN</h2>
            <p style="margin: 0; font-size: 12px; color: #666;">ApexForge Labs</p>
        </div>

        <div class="row">
            <span>No. Invoice:</span>
            <span>INV-{{ $payment->created_at->format('Ymd') }}-{{ $payment->id }}</span>
        </div>
        <div class="row">
            <span>Tanggal:</span>
            <span>{{ $payment->created_at->format('d M Y') }}</span>
        </div>
        <div class="row">
            <span>Perusahaan:</span>
            <span>{{ $payment->company->name ?? '-' }}</span>
        </div>
        <div class="row">
            <span>Freelancer:</span>
            <span>{{ $payment->freelancer->name ?? '-' }}</span>
        </div>
        <div class="row">
            <span>Proyek:</span>
            <span>{{ $payment->project->title ?? '-' }}</span>
        </div>
        <div class="row">
            <span>Status:</span>
            <span><strong>{{ strtoupper($payment->status) }}</strong></span>
        </div>

        <div class="row total">
            <span>TOTAL NOMINAL:</span>
            <span>Rp {{ number_format($payment->nominal ?? $payment->amount, 0, ',', '.') }}</span>
        </div>
    </div>

</body>
</html>