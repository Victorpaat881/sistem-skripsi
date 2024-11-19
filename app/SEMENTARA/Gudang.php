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
        $data = [
            'title' => 'Beranda',
            'gudang' => $dang,
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

        // Menerima input tanggal dalam format YYYY-MM
        $inputMonth = $this->request->getVar('tanggal');

        // Menetapkan tanggal default, misalnya tanggal 1
        $defaultDay = '01';

        // Menggabungkan untuk mendapatkan format YYYY-MM-DD
        $formattedDate = $inputMonth . '-' . $defaultDay;

        $NAMA_OBAT = $this->request->getVar('NAMA_OBAT');
        $SATUAN = $this->request->getVar('SATUAN');
        $MASUK = $this->request->getVar('MASUK');

        $data = [
            "tanggal" => $formattedDate,
            "NAMA_OBAT" => $NAMA_OBAT,
            "SATUAN" => $SATUAN,
            "MASUK" => $MASUK,
        ];

        if (!$validation->run($data, 'stok_obat_insert')) {
            // Validasi gagal, tanggapi dengan pesan kesalahan
            $errors = $validation->getErrors();
            return $this->response->setJSON(['errors' => $errors]);
        } else {

            $gudang = new Stok_Obat();

            $gudang->save($data);

            return $this->response->setJSON(['url' => base_url('Gudang')]);
        }
    }
    public function proses_edit_Stok_obat($id)
    {
        $validation = \Config\Services::validation();

        // Menerima input tanggal dalam format YYYY-MM
        $inputMonth = $this->request->getVar('tanggal');

        // Menggabungkan untuk mendapatkan format YYYY-MM-DD
        $formattedDate = $inputMonth . '-01';

        $id = $this->request->getVar('id'); // Misalkan kita menggunakan ID sebagai pengenal unik
        $NAMA_OBAT = $this->request->getVar('NAMA_OBAT');
        $SATUAN = $this->request->getVar('SATUAN');
        $MASUK = $this->request->getVar('MASUK');
        $KELUAR = $this->request->getVar('KELUAR');

        $data = [
            "tanggal" => $formattedDate,
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

            $gudang = new Stok_Obat();
            $lplpo = new lplpo();

            // Langkah 1: Cari record berdasarkan ID
            $existingRecord = $gudang->where('id', $id)->first();

            $NAMA_OBAT_Lama = $existingRecord['NAMA_OBAT'];
            $tanggal_lama = $existingRecord['tanggal'];

            $gudang->update($id, $data);


            // Menyiapkan data untuk update
            $dataLplpo = [
                'tanggal' => $data['tanggal'],
                'NAMA_OBAT' => $data['NAMA_OBAT'],
                'SATUAN' => $data['SATUAN'],
                'PENERIMAAN' => $data['MASUK'],
                'PEMAKAIAN' => $data['KELUAR'],
            ];

            $lplpo->where('NAMA_OBAT', $NAMA_OBAT_Lama)
                ->where('tanggal', $tanggal_lama)
                ->set($dataLplpo)
                ->update();

            return $this->response->setJSON(['success' => 'Data berhasil diperbarui.']);
        }
    }

    public function hapus_Stok_obat($id)
    {
        $gudang = new Stok_Obat();
        $lplpo = new lplpo();

        // Dapatkan data stok obat yang akan dihapus untuk referensi
        $dataStokObat = $gudang->find($id);

        if ($dataStokObat) {
            // Menghapus data dari stok obat
            $gudang->delete($id);

            // Cari dan hapus data terkait dari LPLPO
            $lplpoId = $this->cariIdLplpoBerdasarkanStokObat($dataStokObat);
            if ($lplpoId !== null) {
                $lplpo->delete($lplpoId);
            }
        }
        // Mengembalikan respon sukses
        return $this->response->setJSON(['success' => 'Data berhasil dihapus.']);
    }
    private function cariIdLplpoBerdasarkanStokObat($dataStokObat)
    {
        $lplpo = new \App\Models\lplpo();

        // Asumsikan kita menggunakan NAMA_OBAT dan SATUAN untuk mencari data terkait di LPLPO
        $lplpoData = $lplpo->where('NAMA_OBAT', $dataStokObat['NAMA_OBAT'])
            ->where('tanggal', $dataStokObat['tanggal'])
            ->first();

        return $lplpoData ? $lplpoData['id'] : null;
    }
}
