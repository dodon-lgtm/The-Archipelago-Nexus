<?php

namespace App\Http\Requests\Freelancer;

use App\Models\Payment;
use App\Models\Withdrawal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WithdrawalStoreRequest extends FormRequest
{
    public const MIN_WITHDRAW = 50000;

    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'freelancer';
    }

    /**
     * Normalisasi nominal sebelum validasi.
     *
     * Input nominal dari browser dikirim dalam format Rupiah (contoh: "50.000"
     * atau "1.001.000"). Tanpa normalisasi, "50.000" akan diparsing PHP
     * sebagai 50 (titik dianggap desimal) sehingga gagal validasi min:50000.
     * Backend selalu mengubahnya menjadi angka bulat murni (contoh: "50000").
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('amount')) {
            $this->merge([
                'amount' => preg_replace('/[^\d]/', '', (string) $this->input('amount')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'method' => [
                'required',
                Rule::in([Withdrawal::METHOD_BANK, Withdrawal::METHOD_EWALLET]),
            ],
            'account_name' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => [
                'required',
                'string',
                $this->input('method') === Withdrawal::METHOD_EWALLET
                    ? 'digits_between:8,20'
                    : 'digits_between:6,20',
            ],
            'amount' => [
                'required',
                'numeric',
                'min:' . self::MIN_WITHDRAW,
                'max:' . $this->availableBalance(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'Pilih metode pencairan terlebih dahulu.',
            'method.in' => 'Metode pencairan tidak valid.',
            'account_name.required' => 'Nama pemilik rekening wajib diisi.',
            'bank_name.required' => 'Nama bank/e-wallet wajib diisi.',
            'account_number.required' => 'Nomor rekening/e-wallet wajib diisi.',
            'account_number.digits_between' => 'Nomor rekening/e-wallet tidak valid.',
            'amount.required' => 'Nominal penarikan wajib diisi.',
            'amount.numeric' => 'Nominal penarikan harus berupa angka.',
            'amount.min' => 'Nominal penarikan minimal Rp ' . number_format(self::MIN_WITHDRAW, 0, ',', '.') . '.',
            'amount.max' => 'Nominal penarikan tidak boleh melebihi saldo tersedia.',
        ];
    }

    /**
     * Saldo tersedia saat ini: total pendapatan yang sudah dibayar
     * dikurangi seluruh nominal penarikan yang aktif (menunggu/diproses)
     * dan yang sudah berhasil ditarik.
     */
    public function availableBalance(): float
    {
        $totalEarned = (float) Payment::where('freelancer_id', Auth::id())
            ->where('status', 'paid')
            ->sum('freelancer_receive');

        $reserved = (float) Withdrawal::forUser(Auth::id())
            ->active()
            ->sum('amount');

        $withdrawn = (float) Withdrawal::forUser(Auth::id())
            ->where('status', Withdrawal::STATUS_BERHASIL)
            ->sum('amount');

        return max(0.0, $totalEarned - $reserved - $withdrawn);
    }
}