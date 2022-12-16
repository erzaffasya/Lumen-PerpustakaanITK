<?php

namespace App\Exports;

use App\Models\Permohonan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PermohonanExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    // protected $data;

    // public function __construct(array $data)
    // {
    //     $this->data = $data;
    // }

    public function collection()
    {
        $query = Permohonan::select('perizinan_id',DB::raw("(SUM(jumlah)) as jumlah"),DB::raw("MONTHNAME(tanggal) as bulan"))
        ->whereYear('tanggal', date('Y'))
        ->groupBy('bulan')->groupBy('perizinan_id')
        ->with('Perizinan')->get();

        foreach ($query as $item) {           
            $data[$item->perizinan_id]['nama_izin'] = $item->Perizinan->nama_izin;
            if ($item->bulan == 'January') {
                $data[$item->perizinan_id]['January'] = $item->jumlah;
            }

            if ($item->bulan == 'February') {
                $data[$item->perizinan_id]['February'] = $item->jumlah;
            }
            if ($item->bulan == 'March') {
                $data[$item->perizinan_id]['March'] = $item->jumlah;
            }
            if ($item->bulan == 'April') {
                $data[$item->perizinan_id]['April'] = $item->jumlah;
            }
            if ($item->bulan == 'May') {
                $data[$item->perizinan_id]['May'] = $item->jumlah;
            }
            if ($item->bulan == 'June') {
                $data[$item->perizinan_id]['June'] = $item->jumlah;
            }
            if ($item->bulan == 'July') {
                $data[$item->perizinan_id]['July'] = $item->jumlah;
            }
            if ($item->bulan == 'August') {
                $data[$item->perizinan_id]['August'] = $item->jumlah;
            }
            if ($item->bulan == 'September') {
                $data[$item->perizinan_id]['September'] = $item->jumlah;
            }
            if ($item->bulan == 'October') {
                $data[$item->perizinan_id]['October'] = $item->jumlah;
            }
            if ($item->bulan == 'November') {
                $data[$item->perizinan_id]['November'] = $item->jumlah;
            }
            if ($item->bulan == 'December') {
                $data[$item->perizinan_id]['December'] = $item->jumlah;
            }                    
        }
       
        return collect($data);
    }

    public function map($data): array
    {
        // dd($data);
        return [
            $data['nama_izin'],
            $data['January']??0,
            $data['February']??0,
            $data['March']??0,
            $data['April']??0,
            $data['May']??0,
            $data['June']??0,
            $data['July']??0,
            $data['August']??0,
            $data['September']??0,
            $data['October']??0,
            $data['November']??0,
            $data['December']??0,
        ];
    }
    public function headings(): array
    {
        return [
            // 'No',
            'Nama Izin',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
    }
}
