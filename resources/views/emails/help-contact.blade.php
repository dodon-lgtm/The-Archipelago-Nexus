<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Pusat Bantuan - ApexForge Labs</title>
</head>

<body style="margin:0;padding:0;background-color:#eff6ff;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eff6ff;padding:24px 16px;">
        <tr>
            <td align="center">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px;width:100%;background-color:#ffffff;border-radius:24px;overflow:hidden;border:1px solid #dbeafe;box-shadow:0 10px 30px rgba(37,99,235,0.10);">

                    {{-- HEADER --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#2563eb 0%,#4f46e5 100%);padding:36px 32px;text-align:center;">
                            <div style="width:56px;height:56px;margin:0 auto 14px;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.35);border-radius:16px;font-size:28px;font-weight:800;color:#ffffff;line-height:56px;text-align:center;">
                                A
                            </div>
                            <h1 style="margin:0;font-size:22px;font-weight:800;color:#ffffff;letter-spacing:-0.02em;">
                                ApexForge Labs
                            </h1>
                            <p style="margin:6px 0 0;font-size:12px;font-weight:700;color:#bfdbfe;text-transform:uppercase;letter-spacing:0.12em;">
                                Pusat Bantuan
                            </p>
                        </td>
                    </tr>

                    {{-- JUDUL --}}
                    <tr>
                        <td style="padding:32px 32px 4px;">
                            <h2 style="margin:0;font-size:18px;font-weight:800;color:#0f172a;">
                                PESAN BARU DARI PUSAT BANTUAN
                            </h2>
                            <p style="margin:8px 0 0;font-size:14px;line-height:1.6;color:#64748b;">
                                Ada pesan baru masuk melalui formulir kontak ApexForge Labs.
                                Berikut rincian pesan dari pengguna.
                            </p>
                        </td>
                    </tr>
{{-- ISI FORM --}}
                    <tr>
                        <td style="padding:16px 32px 0;">

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:10px 0;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Nama</p>
                                        <p style="margin:0;font-size:15px;font-weight:600;color:#1e293b;">{{ $data['name'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Email</p>
                                        <p style="margin:0;font-size:15px;font-weight:600;color:#2563eb;">{{ $data['email'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Role</p>
                                        <p style="margin:0;font-size:15px;font-weight:600;color:#1e293b;">{{ $data['role_label'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Kategori</p>
                                        <p style="margin:0;font-size:15px;font-weight:600;color:#1e293b;">{{ $data['category_label'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Subjek</p>
                                        <p style="margin:0;font-size:15px;font-weight:600;color:#334155;">{{ $data['subject'] }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;">
                                        <p style="margin:0 0 8px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Pesan</p>
                                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid #2563eb;border-radius:12px;padding:14px 16px;font-size:14px;line-height:1.7;color:#334155;white-space:pre-wrap;">
                                            {{ $data['message'] }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0 0;">
                                        <p style="margin:0 0 4px;font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:0.06em;">Dikirim pada</p>
                                        <p style="margin:0;font-size:13px;font-weight:600;color:#475569;">{{ $data['sent_at'] }}</p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="padding:24px 32px 32px;">
                            <div style="border-top:1px solid #e2e8f0;padding-top:20px;text-align:center;">
                                <p style="margin:0;font-size:12px;color:#64748b;line-height:1.7;">
                                    Email ini dikirim otomatis oleh sistem Pusat Bantuan ApexForge Labs.
                                </p>
                                <p style="margin:4px 0 0;font-size:12px;color:#64748b;line-height:1.7;">
                                    Tekan <strong>Reply</strong> untuk membalas pesan ini — balasan otomatis
                                    tertuju ke alamat email pengirim di atas.
                                </p>
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>