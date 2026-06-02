<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;

class PendapatanExport implements FromCollection
{

    public function collection()
    {

        return Transaksi::with(['user', 'paket'])
            ->where('status', 'success')
            ->get();
    }
}
