<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Pembayaran</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: sans-serif;
            color: #1e293b;
            font-size: 10px;
            background: #f1f5f9;
        }

        .sheet {
            background: #ffffff;
            max-width: 1080px;
            margin: 20px auto;
            padding: 24px 26px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
        }

        /* ── Header ── */
        .doc-header {
            width: 100%;
            background-color: #2563eb;
            background-image: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            border-radius: 10px;
            padding: 14px 18px;
            overflow: hidden;
        }
        .doc-header table { width: 100%; border-collapse: collapse; }
        .brand { font-size: 17px; font-weight: 700; letter-spacing: .5px; }
        .brand small { display: block; font-size: 8.5px; font-weight: 400; opacity: .85; margin-top: 2px; }
        .doc-title { text-align: right; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .doc-sub { text-align: right; font-size: 9px; opacity: .9; margin-top: 3px; }

        /* ── Filter bar ── */
        .filterbar {
            margin-top: 14px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 10px;
            color: #3730a3;
        }
        .filterbar b { color: #1e3a8a; }

        /* ── Stat cards ── */
        .stats { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .stats td { border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; width: 25%; }
        .stats .num { display: block; font-size: 15px; font-weight: 700; color: #1d4ed8; margin-top: 2px; }
        .stats .lbl { font-size: 8.5px; text-transform: uppercase; letter-spacing: .6px; color: #64748b; }

        /* ── Table ── */
        .tbl { width: 100%; border-collapse: collapse; margin-top: 14px; }
        .tbl thead th {
            background: #1e40af;
            color: #ffffff;
            text-align: left;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 8px 7px;
            border: 1px solid #1e40af;
        }
        .tbl tbody td { padding: 7px; border: 1px solid #e2e8f0; vertical-align: top; }
        .tbl tbody tr:nth-child(even) { background: #f8fafc; }
        .tbl tfoot td { padding: 9px 7px; border: none; font-weight: 700; border-top: 2px solid #2563eb; }
        .right { text-align: right; }
        .center { text-align: center; }
        .muted { color: #64748b; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 99px; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: .4px; }
        .b-success { background: #dcfce7; color: #166534; }
        .b-warning { background: #fef9c3; color: #854d0e; }
        .b-danger { background: #fee2e2; color: #991b1b; }
        .b-info { background: #e0e7ff; color: #3730a3; }

        .footer { margin-top: 18px; border-top: 2px solid #2563eb; padding-top: 9px; font-size: 8.5px; color: #64748b; }
        .footer table { width: 100%; border-collapse: collapse; }
        .footer .ttd { text-align: center; }

        @media print {
            body { background: #ffffff; margin: 0; }
            .sheet { margin: 0; border: none; box-shadow: none; max-width: 100%; padding: 14px 18px; }
        }
    </style>
</head>
<body>

    @php
        $paidCount  = $payments->filter(fn($p) => in_array(strtolower($p->status), ['paid','dibayar','selesai']))->count();
        $totalNominal   = $payments->sum(fn($p) => (float) ($p->amount ?? 0));
        $totalFee       = $payments->sum(fn($p) => (float) ($p->platform_fee ?? 0));
        $totalNeto      = $payments->sum(fn($p) => (float) ($p->freelancer_receive ?? 0));

        $filterLabel = match ($filterStatus ?? 'all') {
            'paid'    => 'Status: Dibayar / Lunas',
            'pending' => 'Status: Pending / Menunggu Verifikasi',
            'rejected'=> 'Status: Ditolak',
            default   => 'Status: Semua',
        };
        $companyLabel = $filterCompany ? (isset($filterCompany->name) && $filterCompany->name !== '' ? $filterCompany->name : $filterCompany->email) : null;
    @endphp

    <div class="sheet">

        {{-- HEADER --}}
        <div class="doc-header">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:55%;">
                        <div class="brand">
                            ApexForge Labs
                            <small>Freelance Marketplace Indonesia</small>
                        </div>
                    </td>
                    <td style="width:45%;">
                        <div class="doc-title">Laporan Rekapitulasi Pembayaran</div>
                        <div class="doc-sub">Dicetak: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</div>
                    </td>
                </tr>
            </table>
        </div>
{{-- FILTER --}}
        <div class="filterbar">
            <b>Filter Laporan:</b> {{ $companyLabel ? 'Perusahaan — ' . $companyLabel . ' &bull; ' : '' }}{{ $filterLabel }} &bull; {{ $payments->count() }} transaksi
        </div>

        {{-- STAT RINGKASAN --}}
        <table class="stats" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <span class="lbl">Total Transaksi</span>
                    <span class="num">{{ $payments->count() }}</span>
                </td>
                <td>
                    <span class="lbl">Total Nominal (Rp)</span>
                    <span class="num">Rp {{ number_format($totalNominal, 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="lbl">Total Biaya Layanan (Rp)</span>
                    <span class="num">Rp {{ number_format($totalFee, 0, ',', '.') }}</span>
                </td>
                <td>
                    <span class="lbl">Total Diterima Freelancer (Rp)</span>
                    <span class="num">Rp {{ number_format($totalNeto, 0, ',', '.') }}</span>
                </td>
            </tr>
        </table>

        {{-- TABEL --}}
        <table class="tbl" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="center" width="4%">No</th>
                    <th width="11%">No. Invoice</th>
                    <th width="15%">Perusahaan</th>
                    <th width="14%">Freelancer</th>
                    <th width="17%">Proyek</th>
                    <th width="9%">Metode</th>
                    <th width="9%">Tanggal</th>
                    <th class="right" width="10%">Nominal</th>
                    <th class="center" width="9%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $index => $payment)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>
                            <b>{{ $payment->invoice_number ?? ('INV-' . \Illuminate\Support\Carbon::parse($payment->created_at)->format('Ymd') . '-' . $payment->id) }}</b>
                            <span class="muted"> (#{{ $payment->id }})</span>
                        </td>
                        <td>{{ $payment->company->name ?? '-' }}</td>
                        <td>{{ $payment->freelancer->name ?? '-' }}</td>
                        <td>{{ $payment->workspace->project->project_name ?? $payment->workspace->project->title ?? '-' }}</td>
                        <td>{{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : '-' }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($payment->created_at)->format('d M Y') }}</td>
                        <td class="right">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                        <td class="center">
                            @if(in_array(strtolower($payment->status), ['paid', 'dibayar', 'selesai']))
                                <span class="badge b-success">Lunas</span>
                            @elseif(in_array(strtolower($payment->status), ['waiting_verification', 'pending', 'menunggu_verifikasi', 'pending_status']))
                                <span class="badge b-warning">Menunggu</span>
                            @elseif(in_array(strtolower($payment->status), ['rejected', 'ditolak', 'gagal', 'expire']))
                                <span class="badge b-danger">Tolak</span>
                            @else
                                <span class="badge b-info">{{ strtoupper($payment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="center muted" style="padding:28px;">
                            Tidak ada data transaksi pembayaran untuk filter ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="7">Total</td>
                    <td class="right">Rp {{ number_format($totalNominal, 0, ',', '.') }}</td>
                    <td class="center">{{ $paidCount }} lunas</td>
                </tr>
            </tfoot>
        </table>

        {{-- FOOTER --}}
        <div class="footer">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:45%; vertical-align:bottom;">
                        Dokumen ini dihasilkan otomatis oleh sistem ApexForge Labs.<br>
                        Berlaku sebagai laporan administratif pembayaran platform.
                    </td>
                    <td style="width:20%;"></td>
                    <td class="ttd" style="width:35%;">
                        Mengetahui,<br>
                        ApexForge Administrator<br>
                        <div style="margin-top:34px; border-top:1px solid #94a3b8; padding-top:5px;">{{ $filterCompany && isset($filterCompany->name) ? $filterCompany->name : 'ApexForge Admin' }}</div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</body>
</html>