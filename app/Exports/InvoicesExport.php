<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use App\Models\FacturaCompra;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoicesExport implements FromView
{
    // use Exportable;
    public function empresa($year)
    {
        $this->year = $year;
        
        return $this;
    }
    // public function query()
    // {
    //     return FacturaCompra::where('id_empresa','=',$this->year);
    // }
    public function view(): View
    {
        return view('exports.invoices', [
            'invoices' => Invoice::all()
        ]);
    }
}
