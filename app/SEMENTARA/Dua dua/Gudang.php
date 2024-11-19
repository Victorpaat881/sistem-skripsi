<?php

namespace App\Controllers;

use App\Models\lplpo;
use App\Models\Stok_Obat;

class Gudang extends BaseController
{
    public function index()
    {
        $gudang = new Stok_Obat();
        $dang = $gudang->findAll();
        $SatuanUnik = $gudang->getUniqueSatuan();
        $NamaObatUnik = $gudang->getUniqueNamaObat();

        $data = [
            'title' => 'Beranda',
            'gudang' => $dang,
            'SatuanUnik' => $SatuanUnik,
            'NamaObatUnik' => $NamaObatUnik,
            'validation' => \Config\Services::validation()
        ];
        return view('Gudang/index', $data);
    }
    public function get_data_stok_obat()
    {
        $gudang = new Stok_Obat();

        $months = [
            "January" => "Januari", "February" => "Februari", "March" => "Maret",
            "April" => "April", "May" => "Mei", "June" => "Juni",
            "July" => "Juli", "August" => "Agustus", "September" => "September",
            "October" => "Oktober", "November" => "November", "December" => "Desember"
        ];

        $list = $gudang->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        // Looping melalui data untuk mempersiapkan array output
        foreach ($list as $stok_obat) {
            $no++;

            // Format tanggal untuk ditampilkan
            $monthYear = date("F Y", strtotime($stok_obat->tanggal));
            $formatted_date = str_replace(array_keys($months), array_values($months), $monthYear); // Mengubah format tanggal


            // Create the edit and delete button HTML
            $edit_button_html = "<button class='btn btn-outline-secondary btn-edit' data-id='" . $stok_obat->id . "' data-bs-toggle='modal' data-bs-target='#editModalStok_obat'><i class='fa-regular fa-pen-to-square'></i></button>";
            $delete_button_html = "<button class='btn btn-outline-secondary delete-button btn-delete' data-id='" . $stok_obat->id . "' data-bs-toggle='modal' data-bs-target='#deleteModalStok_obat'><i class='ti-trash'></i></button>";

            $row = array(
                $no,
                $formatted_date,
                $stok_obat->NAMA_OBAT,
                $stok_obat->SATUAN,
                $stok_obat->MASUK,
                $stok_obat->KELUAR,
                $stok_obat->SISA,
                $edit_button_html . ' ' . $delete_button_html // Data dummy untuk kolom Jumlah
            );

            $data[] = $row;
        }
        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : 0,
            "recordsTotal" => $gudang->count_all(),
            "recordsFiltered" => $gudang->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function proses_tambah_Stok_obat()
    {
        $validation = \Config\Services::validation();

        $tanggal = $this->request->getVar('tanggal');
        $NAMA_OBAT = $this->request->getVar('NAMA_OBAT');
        $SATUAN = $this->request->getVar('SATUAN');
        $MASUK = $this->request->getVar('MASUK');

        $data = [
            "tanggal" => $tanggal,
            "NAMA_OBAT" => $NAMA_OBAT,
            "SATUAN" => $SATUAN,
            "MASUK" => $MASUK,
        ];

        if (!$validation->run($data, 'stok_obat_insert')) {
            // Validasi gagal, tanggapi dengan pesan kesalahan
            $errors = $validation->getErrors();
            return $this->response->setJSON(['errors' => $errors]);
        } else {
            // $pengeluaran = $MASUK + $penerimaan - $SISA; // Hitung nilai pengeluaran
            $data = [
                "tanggal" => $tanggal,
                "NAMA_OBAT" => $NAMA_OBAT,
                "SATUAN" => $SATUAN,
                "MASUK" => $MASUK,
            ];

            $gudang = new Stok_Obat();

            $gudang->saveData($data);

            return $this->response->setJSON(['url' => base_url('Gudang')]);
        }
    }
    public function proses_edit_Stok_obat($id)
    {
        $validation = \Config\Services::validation();

        $tanggal = $this->request->getVar('tanggal');
        $NAMA_OBAT = $this->request->getVar('NAMA_OBAT');
        $SATUAN = $this->request->getVar('SATUAN');
        $MASUK = $this->request->getVar('MASUK');
        $KELUAR = $this->request->getVar('KELUAR');

        $data = [
            "tanggal" => $tanggal,
            "NAMA_OBAT" => $NAMA_OBAT,
            "SATUAN" => $SATUAN,
            "MASUK" => $MASUK,
            "KELUAR" => $KELUAR,

        ];

        if (!$validation->run($data, 'stok_obat_update')) {
            // Validasi gagal, tanggapi dengan pesan kesalahan
            $errors = $validation->getErrors();
            return $this->response->setJSON(['errors' => $errors]);
        } else {
            // $pengeluaran = $persediaan + $penerimaan - $sisa; // Hitung nilai pengeluaran
            $data = [
                "tanggal" => $tanggal,
                "NAMA_OBAT" => $NAMA_OBAT,
                "SATUAN" => $SATUAN,
                "MASUK" => $MASUK,
                "KELUAR" => $KELUAR,

            ];

            $gudang = new Stok_Obat();

            $gudang->updateData($id, $data);

            // Mengembalikan respon sukses
            return $this->response->setJSON(['success' => 'Data berhasil diperbarui.']);
        }
    }

    public function hapus_Stok_obat($id)
    {
        $gudang = new Stok_Obat();
        $gudang->delete($id);
        // Mengembalikan respon sukses
        return $this->response->setJSON(['success' => 'Data berhasil diperbarui.']);
    }
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
}
