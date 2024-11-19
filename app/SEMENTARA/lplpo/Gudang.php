<?php

namespace App\Controllers;

use App\Models\lplpo;

class Gudang extends BaseController
{
    public function lplpo()
    {
        $gudang = new lplpo();
        $dang = $gudang->findAll();
        $years = $gudang->distinctYears(); // Buat method distinctYears() di model untuk mengambil tahun-tahun unik

        $latestDate = $gudang->getLatestDate();

        $data = [
            'title' => 'Laporan LPLPO',
            'gudang' => $dang,
            'years' => $years,
            'latestYear' => date('Y', strtotime($latestDate)),
            'latestMonth' => date('m', strtotime($latestDate)),
            #'validation' => \Config\Services::validation()
        ];
        return view('Gudang/LPLPO', $data);
    }
    public function update_data()
    {
        $data = $this->request->getPost('data');

        if (!$data) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No data received.']);
        }

        foreach ($data as $item) {
            if (!isset($item['id'])) {
                continue; // Skip entries without an ID
            }
            // Call the model method to update the row
            $lplpoModel = new lplpo();
            $lplpoModel->update_data($item['id'], $item['PENERIMAAN'], $item['PERMINTAAN']);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function get_data_lplpo()
    {
        // Menambahkan logika untuk menerima filter bulan dan tahun dari request POST
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        $gudang = new lplpo();

        // Jika bulan dan tahun di set, gunakan logika filter
        if (isset($bulan) && isset($tahun) && $bulan != '' && $tahun != '') {
            $list = $gudang->getFilteredData($bulan, $tahun);
        } else {
            $list = $gudang->get_datatables();
        }
        $data = array();
        $no = $this->request->getPost('start') ? $this->request->getPost('start') : 0;
        // Looping melalui data untuk mempersiapkan array output
        foreach ($list as $lplpo) {
            $no++;

            $row = array(
                $no,
                $lplpo->NAMA_OBAT,
                $lplpo->SATUAN,
                $lplpo->STOK_AWAL,
                $lplpo->PENERIMAAN,
                $lplpo->PERSEDIAAN,
                $lplpo->PEMAKAIAN,
                $lplpo->SISA_STOK,
                $lplpo->STOK_OPTIMUM,
                $lplpo->PERMINTAAN,
                '0', // Data dummy untuk kolom APBD
                '0', // Data dummy untuk kolom Program
                '0', // Data dummy untuk kolom Lain-Lain
                '0', // Data dummy untuk kolom Jumlah
                'DT_RowId' => 'row_' . $lplpo->id // Assuming 'ID' is the primary key column in your database
            );
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->request->getPost('draw'),
            "recordsTotal" => count($list),
            "recordsFiltered" => $gudang->count_filtered($bulan, $tahun),
            "data" => $data,
        );

        echo json_encode($output);
    }
}
