<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $payment->invoice_number ?? ('INV-' . $payment->id) }}</title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: sans-serif;
            color: #1e293b;
            font-size: 11px;
            background: #f1f5f9;
        }

        .sheet {
            background: #ffffff;
            max-width: 700px;
            margin: 24px auto;
            padding: 26px 30px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, .06);
        }

        .doc-header {
            width: 100%;
            background-color: #2563eb;
            background-image: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            border-radius: 12px;
            padding: 16px 20px;
            overflow: hidden;
        }
        .doc-header table { width: 100%; border-collapse: collapse; }
        .brand { font-size: 20px; font-weight: 700; letter-spacing: .5px; }
        .brand small { display: block; font-size: 9px; font-weight: 400; opacity: .85; letter-spacing: .5px; margin-top: 2px; }
        .doc-title { text-align: right; font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; }
        .doc-status {
            display: inline-block;
            margin-top: 7px;
            background: #22c55e;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 99px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .sec { margin-top: 20px; }
        .sec-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3a8a;
            border-left: 4px solid #2563eb;
            padding-left: 9px;
            margin-bottom: 10px;
        }

        .meta { width: 100%; border-collapse: collapse; }
        .meta td { border: 1px solid #e2e8f0; padding: 8px 10px; vertical-align: top; }
        .meta .lbl {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
            margin-bottom: 2px;
        }
        .meta .val { font-size: 11px; font-weight: 600; color: #334155; line-height: 1.35; }

        .items { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .items thead th {
            background: #1e40af;
            color: #ffffff;
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 9px 10px;
        }
        .items tbody td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; }
        .items tbody tr:nth-child(even) { background: #f8fafc; }
        .items tfoot td { padding: 10px; border-top: 2px solid #2563eb; font-weight: 700; }
        .items .right { text-align: right; }
        .muted { color: #64748b; font-size: 9.5px; }

        .grand { background: #eff6ff !important; }

        .footer {
            margin-top: 28px;
            border-top: 2px solid #2563eb;
            padding-top: 12px;
            font-size: 9px;
            color: #64748b;
            line-height: 1.6;
        }
        .pill {
            display: inline-block;
            padding: 2px 9px;
            border-radius: 99px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .pill-blue { background: #eff6ff; color: #1d4ed8; }

        @media print {
            .no-print { display: none; }
            body { background: #ffffff; margin: 0; }
            .sheet { margin: 0; border: none; box-shadow: none; max-width: 100%; padding: 22px 26px; }
        }
    </style>
</head>
<body>

    <div class="sheet">

        {{-- HEADER --}}
        <div class="doc-header">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:60%;">
                        <div class="brand">
                            ApexForge Labs
                            <small>Freelance Marketplace Indonesia</small>
                        </div>
                    </td>
                    <td style="width:40%;">
                        <div class="doc-title">
                            Bukti Pembayaran
                            <br>
                            <span class="doc-status">LUNAS</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
{{-- META --}}
        <div class="sec">
            <div class="sec-title">Informasi Transaksi</div>
            <table class="meta" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:50%;">
                        <span class="lbl">Diterbitkan Untuk (Perusahaan)</span>
                        <span class="val">{{ $payment->company->name ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="lbl">No. Invoice</span>
                        <span class="val">{{ $payment->invoice_number ?? ('INV-' . \Illuminate\Support\Carbon::parse($payment->created_at)->format('Ymd') . '-' . $payment->id) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="lbl">Freelancer</span>
                        <span class="val">{{ $payment->freelancer->name ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="lbl">Tanggal Transaksi</span>
                        <span class="val">{{ \Illuminate\Support\Carbon::parse($payment->created_at)->format('d M Y H:i') }} WIB</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="lbl">Proyek</span>
                        <span class="val">{{ $payment->workspace->project->project_name ?? $payment->workspace->project->title ?? '-' }}</span>
                    </td>
                    <td>
                        <span class="lbl">Metode Pembayaran</span>
                        <span class="val">{{ ucfirst(str_replace('_', ' ', $payment->payment_method ?? '-')) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="lbl">Jenis Pembayaran</span>
                        <span class="val">
                            @if($payment->isQuotaPayment())
                                Kuota Proyek Tambahan
                            @else
                                Pembayaran Proyek
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="lbl">Diverifikasi pada</span>
                        <span class="val">{{ \Illuminate\Support\Carbon::parse($payment->verified_at ?? $payment->created_at)->format('d M Y H:i') }} WIB</span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- RINCIAN --}}
        <div class="sec">
            <div class="sec-title">Rincian Pembayaran</div>
            <table class="items" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th style="width:32%; text-align:right;">Jumlah (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nominal Pembayaran Proyek</td>
                        <td class="right">{{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>
                            Biaya Layanan Platform
                            @if($payment->platform_fee > 0)
                                <span class="muted">(dipotong dari nominal)</span>
                            @endif
                        </td>
                        <td class="right">
                            {{ $payment->platform_fee > 0 ? '- ' : '' }}{{ number_format($payment->platform_fee ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td>Diterima Freelancer</td>
                        <td class="right">{{ number_format($payment->freelancer_receive ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="grand">
                        <td>Total Pembayaran (Lunas)</td>
                        <td class="right" style="color:#1d4ed8;">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- CATATAN + TTD --}}
        <div class="sec">
            <table class="meta" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:55%;">
                        <span class="lbl">Status Dana (Escrow)</span>
                        <span class="val">
                            {{ $payment->funds_status ? \Illuminate\Support\Str::title(str_replace('_', ' ', $payment->funds_status)) : 'Belum Ada Dana (Kuota)' }}
                        </span>
                        <br><br>
                        <span class="lbl">Catatan</span>
                        <span class="val muted">{!! nl2br(e($payment->company_note ?? '-')) !!}</span>
                    </td>
                    <td style="text-align:center;">
                        <span class="lbl">Mengetahui, Administrator</span>
                        <div class="pill pill-blue">{{ $payment->verifier->name ?? 'ApexForge Admin' }}</div>
                        <div style="margin-top:52px;"></div>
                        <span class="val" style="font-size:9px; color:#64748b;">Dicetak: {{ now()->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            ApexForge Labs &bull; Freelance Marketplace Indonesia &bull; Pembayaran tervalidasi dan telah dikonfirmasi oleh administrator.
        </div>

    </div>

</body>
</html>