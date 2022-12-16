<?php

namespace App\Exports;

use App\Http\Resources\YudisiumMahasiswaResource;
use App\Models\Permohonan;
use App\Models\YudisiumMahasiswa;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class YudisiumMahasiswaExport implements FromCollection, WithHeadings, WithMapping
{
    use Exportable;
    // protected $data;

    // public function __construct(array $data)
    // {
    //     $this->data = $data;
    // }

    // NO	NAMA	NIM	JURUSAN	PRODI	ANGKATAN	STATUS BERKAS	STATUS PEMINJAMAN	STATUS FINAL	ACTION
    public function collection()
    {
        $query = YudisiumMahasiswa::select('yudisium_mahasiswa.*', 'yudisium.periode','yudisium.tahun', 'users.*')
            ->join('users', 'yudisium_mahasiswa.user_id', 'users.id')
            ->join('yudisium', 'yudisium_mahasiswa.yudisium_id', 'yudisium.id')
            ->get();
        return collect($query);
    }

    public function map($data): array
    {
        return [
            $data->name,
            $data->nim,
            $data->jurusan,
            $data->prodi,
            $data->angkatan,
            ($data->status_berkas == 0) ? 'Belum Diterima' : 'Diterima',
            ($data->status_peminjaman == 0) ? 'Belum Diterima' : 'Diterima',
            ($data->status_final == 0) ? 'Belum Diterima' : 'Diterima',
            $data->periode,
            $data->tahun,
        ];
    }
    public function headings(): array
    {
        return [
            'Nama Mahasiswa',
            'NIM',
            'Jurusan',
            'Prodi',
            'Angkatan',
            'Status Berkas',
            'Status Peminjaman',
            'Status Final',
            'Periode',
            'Tahun'
        ];
    }
}
