<div class="pay-option rounded-2xl p-5 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-blue-50 text-brand">
                                    <i class="fa-solid fa-bolt text-lg"></i>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Otomatis</span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-sm mt-4">Bayar dengan Midtrans</h3>
                            <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                Pembayaran otomatis & instan melalui QRIS, Virtual Account, E-Wallet, dan Kartu Kredit.
                            </p>
                           <button type="button" id="payMidtransBtn"
        data-workspace-id="{{ $workspace->id }}"
        data-amount="{{ $payment->amount }}"
        class="w-full flex items-center justify-center gap-2 mt-4 px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-indigo-700 transition shadow-lg shadow-brand/25 disabled:opacity-60 disabled:cursor-not-allowed">
    <i class="fa-solid fa-bolt text-xs"></i>
    <span>Bayar dengan Midtrans</span>
</button>
                            <p id="midtransError" class="text-[11px] text-red-500 text-center mt-2 hidden"></p>
                        </div>

                        <div class="pay-option rounded-2xl p-5 bg-white">
                            <div class="flex items-center justify-between">
                                <div class="inline-flex items-center justify-center w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-money-bill-transfer text-lg"></i>
                                </div>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Manual</span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-sm mt-4">Bayar Manual</h3>
                            <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                Transfer Bank, QRIS, atau E-Wallet, lalu upload bukti pembayaran untuk diverifikasi Admin.
                            </p>
                            <a href="{{ route('company.payments.upload-form', $workspace) }}"
                               class="w-full flex items-center justify-center gap-2 mt-4 px-4 py-3 bg-white border border-blue-200 text-slate-700 rounded-xl text-sm font-semibold hover:border-brand hover:text-brand transition">
                                <i class="fa-solid fa-upload text-xs"></i>
                                <span>Bayar Manual</span>
                            </a>
                        </div>