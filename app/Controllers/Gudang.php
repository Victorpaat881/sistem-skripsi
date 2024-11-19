<?php

namespace App\Controllers;

set_time_limit(180); // Set batas waktu menjadi 3 menit

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\lplpo;
use App\Models\Stok_Obat;

class Gudang extends BaseController
{
    ##########################################################
    ###                                                    ###
    ###                    Stok Obat                       ###
    ###                                                    ###
    ##########################################################

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
            "January" => "Januari",
            "February" => "Februari",
            "March" => "Maret",
            "April" => "April",
            "May" => "Mei",
            "June" => "Juni",
            "July" => "Juli",
            "August" => "Agustus",
            "September" => "September",
            "October" => "Oktober",
            "November" => "November",
            "December" => "Desember"
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
        $KELUAR = $this->request->getVar('KELUAR');

        $data = [
            "tanggal" => $formattedDate,
            "NAMA_OBAT" => $NAMA_OBAT,
            "SATUAN" => $SATUAN,
            "MASUK" => $MASUK,
            "KELUAR" => $KELUAR,
        ];

        if (!$validation->run($data, 'stok_obat_insert')) {
            // Validasi gagal, tanggapi dengan pesan kesalahan
            $errors = $validation->getErrors();
            return $this->response->setJSON(['errors' => $errors]);
        } else {

            $gudang = new Stok_Obat();
            // $lplpo = new lplpo();

            $gudang->save($data);

            // // Data untuk LPLPO
            // $dataLplpo = [
            //     'tanggal' => $data['tanggal'],
            //     'NAMA_OBAT' => $data['NAMA_OBAT'],
            //     'SATUAN' => $data['SATUAN'],
            //     'PENERIMAAN' => $data['MASUK'],
            // ];

            // // Menyimpan data ke LPLPO
            // $lplpo->save($dataLplpo);

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


            // Debugging: Cetak tanggal yang diformat
            // log_message('debug', 'id sebelum update: ' . $id);
            // log_message('debug', 'Tanggal sebelum update: ' . $tanggal_lama);
            // log_message('debug', 'nama obat sebelum update: ' . $NAMA_OBAT_Lama);

            $gudang->update($id, $data);


            // Menyiapkan data untuk update
            $dataLplpo = [
                'tanggal' => $data['tanggal'],
                'NAMA_OBAT' => $data['NAMA_OBAT'],
                'SATUAN' => $data['SATUAN'],
                'PENERIMAAN' => $data['MASUK'],
                'PEMAKAIAN' => $data['KELUAR'],
            ];

            // Contoh: Tambahkan logging sebelum melakukan update
            // log_message('debug', 'Data yang dikirim untuk update: ' . print_r($dataLplpo, TRUE));

            // Mencari dan update record berdasarkan "NAMA_OBAT" dan bulan dari tanggal
            $lplpo->where('NAMA_OBAT', $NAMA_OBAT_Lama)
                ->where('tanggal', $tanggal_lama)
                ->set($dataLplpo)
                ->update();



            // Mengembalikan respon sukses
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

        $lplpoData = $lplpo->where('NAMA_OBAT', $dataStokObat['NAMA_OBAT'])
            ->where("YEAR(tanggal) = YEAR('{$dataStokObat['tanggal']}')")
            ->where("MONTH(tanggal) = MONTH('{$dataStokObat['tanggal']}')")
            ->first();

        return $lplpoData ? $lplpoData['id'] : null;
    }

    ##########################################################
    ###                                                    ###
    ###               Perbadingan_prediksi                 ###
    ###                                                    ###
    ##########################################################

    public function Perbadingan_prediksi()
    {
        return view('Gudang/Perbandingan', ['title' => 'Perbandingan Hasil Prediksi',]);
    }

    ##########################################################
    ###                                                    ###
    ###                    Gudang LPLPO                    ###
    ###                                                    ###
    ##########################################################
    public function prediction()
    {

        // Terima data bulan dan tahun dari permintaan AJAX
        $bulan = $this->request->getVar('bulan');
        $tahun = $this->request->getVar('tahun');

        // Panggil model untuk mengambil data
        $gudang = new lplpo();

        // Pastikan ada data terbaru
        if ($bulan && $tahun) {
            // Filter data untuk hanya mengambil data pada tahun dan bulan tertentu
            $data = $gudang->where("YEAR(tanggal)", $tahun)->where("MONTH(tanggal)", $bulan)->findAll();
            // Bagi data ke dalam batch
            $batchedData = array_chunk($data, 50);

            // Inisialisasi array untuk menampung hasil prediksi
            $predictions = [];

            // Inisialisasi cURL multi handler untuk pengiriman paralel
            $multiHandler = curl_multi_init();

            // Loop melalui setiap batch data
            foreach ($batchedData as $batch) {
                // Kirim permintaan prediksi secara paralel
                $predictions = array_merge($predictions, $this->predictBatch($batch, $multiHandler));
            }

            // Tampilkan output prediksi di console log
            foreach ($predictions as $prediction) {
                if ($prediction && isset($prediction['status_code']) && $prediction['status_code'] == 200) {
                    $result = json_decode($prediction['body'], true);
                    $predictionValue = $result['prediction'];

                    // Cek apakah $predictionValue adalah array
                    if (is_array($predictionValue)) {
                        // Ambil nilai prediksi (asumsi hasil prediksi selalu berupa array dengan satu elemen)
                        $predictedValue = $predictionValue[0][0];

                        $displayValue = max(0, $predictedValue);

                        echo $displayValue . "\n";
                    } else {
                        echo "Hasil Prediksi: " . $predictionValue . "\n";
                    }
                } else {
                    echo "Gagal mendapatkan prediksi. Kode status: " . ($prediction['status_code'] ?? 'Undefined') . "\n";
                }
            }

            // Tutup cURL multi handler
            curl_multi_close($multiHandler);
        } else {
            echo "Tidak ada data yang ditemukan.";
        }
    }

    private function predictBatch($batch, $multiHandler)
    {
        $url = 'http://127.0.0.1:5000/predict'; // Sesuaikan dengan URL servis prediksi Anda
        $predictions = [];

        foreach ($batch as $row) {
            // Sesuaikan ini dengan struktur data yang sesuai dengan model Anda
            $features = [
                'NAMA OBAT' => $row["NAMA_OBAT"],
                'SATUAN' => $row['SATUAN'],
                'STOK AWAL' => $row['STOK_AWAL'],
                'PENERIMAAN' => $row['PENERIMAAN'],
                'PERSEDIAAN' => $row['PERSEDIAAN'],
                'PEMAKAIAN' => $row['PEMAKAIAN'],
                'SISA STOK' => $row['SISA_STOK'],
                'STOK OPTIMUM' => $row['STOK_OPTIMUM'],
            ];

            // Kirim request ke servis prediksi
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($features));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            // Tambahkan handle cURL ke cURL multi handler
            curl_multi_add_handle($multiHandler, $ch);

            // Simpan handle cURL dalam array
            $predictions[] = $ch;
        }

        // Eksekusi semua handle cURL secara paralel
        $running = null;
        do {
            curl_multi_exec($multiHandler, $running);
        } while ($running > 0);

        // Ambil hasil dari setiap handle cURL
        $results = [];
        foreach ($predictions as $ch) {
            $results[] = [
                'status_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
                'body' => curl_multi_getcontent($ch),
            ];

            // Hapus handle cURL dari cURL multi handler
            curl_multi_remove_handle($multiHandler, $ch);
        }

        return $results;
    }
    public function savePredictions()
    {
        // Ambil data JSON dari permintaan
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        // Check if $data is not null and is an array
        if (is_array($data) || is_object($data)) {
            // Simpan data hasil prediksi ke dalam database
            $gudang = new lplpo();
            foreach ($data as $prediction) {
                $id = $prediction->id;
                $PERMINTAAN = $prediction->PERMINTAAN;

                $gudang->where('id', $id)
                    ->set('PERMINTAAN', $PERMINTAAN)
                    ->update();
            }

            echo json_encode(['status' => 'success', 'message' => 'Data berhasil diupdate di database']);
        } else {
            // Handle the case where $data is null or not an array
            echo json_encode(['status' => 'error', 'message' => 'Data tidak valid']);
        }
    }
    public function getLPLPOData($tahun = null, $bulan = null)
    {
        // Terima data bulan dan tahun dari permintaan AJAX
        $bulan = $this->request->getVar('bulan');
        $tahun = $this->request->getVar('tahun');

        // log_message('debug', 'Data bulan lplpo: ' . print_r($bulan, TRUE));
        // log_message('debug', 'Data tahun lplpo: ' . print_r($tahun, TRUE));

        // // Cek tipe data bulan dan tahun
        // log_message('debug', 'Tipe data bulan lplpo: ' . gettype($bulan));
        // log_message('debug', 'Tipe data tahun lplpo: ' . gettype($tahun));
        $gudang = new lplpo();
        // Filter data untuk hanya mengambil data pada tahun dan bulan tertentu
        $Date = $gudang->where("YEAR(Tanggal)", $tahun)->where("MONTH(Tanggal)", $bulan)->findAll();

        // log_message('debug', 'Data bulan: ' . print_r($Date, TRUE));

        // // Cek tipe data bulan dan tahun
        // log_message('debug', 'Tipe data bulan: ' . gettype($Date));

        return $this->response->setJSON(['Date' => $Date]); // Mengembalikan data dalam format JSON
    }
    public function lplpo()
    {
        $gudang = new lplpo();
        $dang = $gudang->findAll();
        $years = $gudang->distinctYears(); // Buat method distinctYears() di model untuk mengambil tahun-tahun unik

        $latestDate = $gudang->getLatestDate();

        $tahun = date('Y', strtotime($latestDate));
        $bulan = date('m', strtotime($latestDate));

        // Filter data untuk hanya mengambil data pada tahun dan bulan tertentu
        $Date = $gudang->where("YEAR(Tanggal)", $tahun)->where("MONTH(Tanggal)", $bulan)->findAll();

        $data = [
            'title' => 'Laporan LPLPO',
            'gudang' => $dang,
            'years' => $years,
            'Date' => $Date,
            'latestYear' => date('Y', strtotime($latestDate)),
            'latestMonth' => date('m', strtotime($latestDate)),
        ];
        return view('Gudang/LPLPO', $data);
    }
    public function checkData()
    {
        $tahun = $this->request->getPost('tahun');

        $gudang = new lplpo();
        $monthsWithData = $gudang->getMonthsWithData($tahun); // Buat method checkDataAvailability() di model
        $dataAvailable = count($monthsWithData) > 0;

        return $this->response->setJSON(['dataAvailable' => $dataAvailable, 'monthsWithData' => $monthsWithData]);
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
            $lplpoModel->update_data(
                $item['id'],
                $item['PENERIMAAN'],
                $item['PERMINTAAN']
            );
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
                "<p class='stok-awal'>" . $lplpo->STOK_AWAL . "</p>",
                "<p class='penerimaan'>" . $lplpo->PENERIMAAN . "</p>",
                "<p class='persediaan'>" . $lplpo->PERSEDIAAN . "</p>",
                "<p class='pemakaian'>" . $lplpo->PEMAKAIAN . "</p>",
                "<p class='sisa-stok'>" . $lplpo->SISA_STOK . "</p>",
                "<p class='stok-optimum'>" . $lplpo->STOK_OPTIMUM . "</p>",
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
    public function export_excel()
    {
        $bulan = $this->request->getVar('bulan'); // Default ke bulan saat ini jika tidak diset
        $tahun = $this->request->getVar('tahun'); // Default ke tahun saat ini jika tidak diset

        $gudang = new lplpo();
        // Get data for the selected month and year
        $dang = $gudang->getFilteredData($bulan, $tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Array pemetaan bulan Indonesia ke bulan Inggris
        $bulanIndonesia = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];


        // Mengonversi nomor bulan menjadi nama bulan dalam Bahasa Indonesia
        $namaBulan = $bulanIndonesia[$bulan];

        // Mengonversi ke bahasa Indonesia
        $bulanTahun = 'PELAPORAN BULAN / PERIODE  : ' . $namaBulan . '  ' . $tahun;


        //BULAN BERIKUT
        // Menentukan bulan berikutnya
        $nomorBulanBerikutnya = $bulan + 1;


        // Jika bulan berikutnya melewati Desember, atur kembali ke Januari dan tambah tahun
        if ($nomorBulanBerikutnya > 12) {
            $nomorBulanBerikutnya = 1;
            $tahun += 1;
        }

        // Mengambil nama bulan berikutnya dalam Bahasa Indonesia
        $namaBulanBerikutnya = $bulanIndonesia[$nomorBulanBerikutnya];

        // Mengonversi ke bahasa Indonesia
        $bulanTahunBerikutnya = 'PERMINTAAN BULAN/PERIODE : ' . $namaBulanBerikutnya . '  ' . $tahun;

        //Atas Tabel
        $sheet->setCellValue('A1', 'LAPORAN PEMAKAIAN DAN LEMBAR PERMINTAAN OBAT');
        $sheet->mergeCells('A1:N1');
        $sheet->setCellValue('A2', '(LPLPO)');
        $sheet->mergeCells('A2:N2');
        $sheet->setCellValue('B3', 'PUSKESMAS                  :  LANSOT');
        $sheet->setCellValue('B4', 'DAERAH KAB/KOTA     : TOMOHON');
        $sheet->setCellValue('A5', $bulanTahun);
        $sheet->mergeCells('A5:N5');
        $sheet->setCellValue('A6', $bulanTahunBerikutnya);
        $sheet->mergeCells('A6:N6');

        //Header Tabel
        $sheet->setCellValue('A7', 'No');
        $sheet->mergeCells('A7:A8');
        $sheet->setCellValue('B7', 'NAMA OBAT');
        $sheet->mergeCells('B7:B8');
        $sheet->setCellValue('C7', 'SATUAN');
        $sheet->mergeCells('C7:C8');
        $sheet->setCellValue('D7', 'STOK AWAL');
        $sheet->mergeCells('D7:D8');
        $sheet->setCellValue('E7', 'PENERIMAAN');
        $sheet->mergeCells('E7:E8');
        $sheet->setCellValue('F7', 'PERSEDIAAN');
        $sheet->mergeCells('F7:F8');
        $sheet->setCellValue('G7', 'PEMAKAIAN');
        $sheet->mergeCells('G7:G8');
        $sheet->setCellValue('H7', 'SISA STOK');
        $sheet->mergeCells('H7:H8');
        $sheet->setCellValue('I7', 'STOK OPTIMUM');
        $sheet->mergeCells('I7:I8');
        $sheet->setCellValue('J7', 'PERMINTAAN');
        $sheet->mergeCells('J7:J8');
        $sheet->setCellValue('K7', 'PEMBERIAN BULAN');
        $sheet->mergeCells('K7:N7');
        $sheet->setCellValue('K8', 'APBD');
        $sheet->setCellValue('L8', 'PROGRAM');
        $sheet->setCellValue('M8', 'LAIN-LAIN');
        $sheet->setCellValue('N8', 'JUMLAH');

        //Bawah header
        $sheet->setCellValue('A9', '1');
        $sheet->setCellValue('B9', '2');
        $sheet->setCellValue('C9', '3');
        $sheet->setCellValue('D9', '4');
        $sheet->setCellValue('E9', '5');
        $sheet->setCellValue('F9', '6');
        $sheet->setCellValue('G9', '7');
        $sheet->setCellValue('H9', '8');
        $sheet->setCellValue('I9', '9');
        $sheet->setCellValue('J9', '10');
        $sheet->setCellValue('K9', '11');
        $sheet->setCellValue('L9', '12');
        $sheet->setCellValue('M9', '13');
        $sheet->setCellValue('N9', '14');

        //Data Tabel
        $rowNumber = 10;
        foreach ($dang as $dataRow) {
            $namaObat = str_replace(' atau ', '/', $dataRow->NAMA_OBAT); // Mengganti 'atau' dengan '/'
            $satuan = str_replace(' atau ', '/', $dataRow->SATUAN);

            $sheet->setCellValue('A' . $rowNumber, ($rowNumber - 9));
            $sheet->setCellValue('B' . $rowNumber, $namaObat);
            $sheet->setCellValue('C' . $rowNumber, $satuan);
            $sheet->setCellValue('D' . $rowNumber, $dataRow->STOK_AWAL);
            $sheet->setCellValue('E' . $rowNumber, $dataRow->PENERIMAAN);
            $sheet->setCellValue('F' . $rowNumber, $dataRow->PERSEDIAAN);
            $sheet->setCellValue('G' . $rowNumber, $dataRow->PEMAKAIAN);
            $sheet->setCellValue('H' . $rowNumber, $dataRow->SISA_STOK);
            $sheet->setCellValue('I' . $rowNumber, $dataRow->STOK_OPTIMUM);
            $sheet->setCellValue('J' . $rowNumber, $dataRow->PERMINTAAN);
            $sheet->setCellValue('K' . $rowNumber, "");
            $sheet->setCellValue('L' . $rowNumber, "");
            $sheet->setCellValue('M' . $rowNumber, "");
            $sheet->setCellValue('N' . $rowNumber, "");
            $rowNumber++;
        }

        //Bawah Tabel
        // Dapatkan baris tertinggi
        $highestRow = $sheet->getHighestRow();
        $startNewTableAt = $highestRow + 2; // Baris awal untuk tabel baru

        // Menggabungkan sel
        $sheet->mergeCells('B' . $startNewTableAt . ':B' . ($startNewTableAt + 2));
        $sheet->mergeCells('C' . $startNewTableAt . ':D' . $startNewTableAt);
        $sheet->mergeCells('E' . $startNewTableAt . ':E' . ($startNewTableAt + 1));
        $sheet->mergeCells('F' . $startNewTableAt . ':F' . ($startNewTableAt + 1));

        // Menambahkan header untuk tabel baru
        $sheet->setCellValue('B' . $startNewTableAt, 'JUMLAH KUNJUNGAN RESEP');
        $sheet->setCellValue('C' . $startNewTableAt, 'UMUM');
        $sheet->setCellValue('C' . ($startNewTableAt + 1), 'BAYAR');
        $sheet->setCellValue('D' . ($startNewTableAt + 1), 'TIDAK BAYAR');
        $sheet->setCellValue('E' . $startNewTableAt, 'BPJS');
        $sheet->setCellValue('F' . $startNewTableAt, 'JUMLAH');

        //Style
        $headerStyleBottom = [
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $underlineStyleArray = [
            'font' => [
                'underline' => \PhpOffice\PhpSpreadsheet\Style\Font::UNDERLINE_SINGLE
            ],
        ];

        //Terapkan style pada tabel yang di bawah tabel data
        $sheet->getStyle('B' . $startNewTableAt . ':F' . ($startNewTableAt + 2))->applyFromArray($headerStyleBottom);

        //Tandatangan
        $sheet->setCellValue('B' . ($startNewTableAt + 5), 'MENGETAHUI / MENYETUJUI :');
        $sheet->setCellValue('B' . ($startNewTableAt + 6), 'Plt. KEPALA DINAS KESEHATAN DAERAH');
        $sheet->setCellValue('B' . ($startNewTableAt + 7), 'KOTA TOMOHON');
        $sheet->getRowDimension($startNewTableAt + 8)->setRowHeight(60);
        $sheet->setCellValue('B' . ($startNewTableAt + 8), 'dr. Jhon J.D .Lumopa,M.Kes');
        $sheet->setCellValue('B' . ($startNewTableAt + 9), 'NIP. 19690630 200212 1 002');

        $sheet->setCellValue('C' . ($startNewTableAt + 5), 'YANG MENYERAHKAN');
        $sheet->setCellValue('C' . ($startNewTableAt + 6), 'KEPALA INSTALASI FARMASI,');
        $sheet->setCellValue('C' . ($startNewTableAt + 8), 'JUNITHA L. KEREH, S.Si,Apt');
        $sheet->setCellValue('C' . ($startNewTableAt + 9), 'NIP. 19770607 199603 2 001');
        $sheet->mergeCells('C' . ($startNewTableAt + 5) . ':D' . ($startNewTableAt + 5));
        $sheet->mergeCells('C' . ($startNewTableAt + 6) . ':D' . ($startNewTableAt + 6));
        $sheet->mergeCells('C' . ($startNewTableAt + 8) . ':D' . ($startNewTableAt + 8));
        $sheet->mergeCells('C' . ($startNewTableAt + 9) . ':D' . ($startNewTableAt + 9));

        $sheet->setCellValue('F' . ($startNewTableAt + 5), 'YANG MEMINTA / MELAPORKAN :');
        $sheet->setCellValue('F' . ($startNewTableAt + 6), 'KEPALA PUSKESMAS');
        $sheet->setCellValue('F' . ($startNewTableAt + 8), 'dr. Agustin Yuliana Mantow');
        $sheet->setCellValue('F' . ($startNewTableAt + 9), 'NIP. 19740808 200212 2 003');
        $sheet->mergeCells('F' . ($startNewTableAt + 5) . ':G' . ($startNewTableAt + 5));
        $sheet->mergeCells('F' . ($startNewTableAt + 6) . ':G' . ($startNewTableAt + 6));
        $sheet->mergeCells('F' . ($startNewTableAt + 8) . ':G' . ($startNewTableAt + 8));
        $sheet->mergeCells('F' . ($startNewTableAt + 9) . ':G' . ($startNewTableAt + 9));

        $sheet->setCellValue('J' . ($startNewTableAt + 5), 'YANG MENERIMA :');
        $sheet->setCellValue('J' . ($startNewTableAt + 6), 'PETUGAS PUSKESMAS,');
        $sheet->setCellValue('J' . ($startNewTableAt + 8), 'Restuyani Paranoan,S.Si,M.Si,Apt');
        $sheet->setCellValue('J' . ($startNewTableAt + 9), 'NIP. 198607082011022001');
        $sheet->mergeCells('J' . ($startNewTableAt + 5) . ':K' . ($startNewTableAt + 5));
        $sheet->mergeCells('J' . ($startNewTableAt + 6) . ':K' . ($startNewTableAt + 6));
        $sheet->mergeCells('J' . ($startNewTableAt + 8) . ':K' . ($startNewTableAt + 8));
        $sheet->mergeCells('J' . ($startNewTableAt + 9) . ':K' . ($startNewTableAt + 9));

        //Terapkan style pada tandatangan
        $sheet->getStyle('B' . ($startNewTableAt + 8))->applyFromArray($underlineStyleArray);
        $sheet->getStyle('C' . ($startNewTableAt + 8))->applyFromArray($underlineStyleArray);
        $sheet->getStyle('F' . ($startNewTableAt + 8))->applyFromArray($underlineStyleArray);
        $sheet->getStyle('J' . ($startNewTableAt + 8))->applyFromArray($underlineStyleArray);

        // Set header style
        $bawahheader = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFA5A5A5'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFC0C0C0'],
            ],
            'font' => [
                'bold' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        // Menentukan style alignment
        $centertabel = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $wraptext = [
            'alignment' => [
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $bold = [
            'font' => [
                'bold' => true,
            ],
        ];
        $centertextatas = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        //Style untuk di atas tabel
        $sheet->getStyle('A1:N6')->applyFromArray($bold);
        $sheet->getStyle('A1')->applyFromArray($centertextatas);
        $sheet->getStyle('A2')->applyFromArray($centertextatas);
        $sheet->getStyle('A5')->applyFromArray($centertextatas);
        $sheet->getStyle('A6')->applyFromArray($centertextatas);


        // Masukkan style ke header
        $sheet->getStyle('A7:N8')->applyFromArray($headerStyle);

        // Masukkan style ke bawah header
        $sheet->getStyle('A9:N9')->applyFromArray($bawahheader);

        // Terapkan style alignment pada seluruh kolom yang di perlukan
        $sheet->getStyle('A7:A' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('B7:B' . $highestRow)->applyFromArray($wraptext);
        $sheet->getStyle('C7:C' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('D7:D' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('E7:E' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('F7:F' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('G7:G' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('H7:H' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('I7:I' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('J7:J' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('K7:K' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('L7:L' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('M7:M' . $highestRow)->applyFromArray($centertabel);
        $sheet->getStyle('N7:N' . $highestRow)->applyFromArray($centertabel);


        //Menyesuaikan lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);


        // agar saat desember tahunnya tetap tahun saat itu dan bukan tahun berikutnya
        if ($nomorBulanBerikutnya == 1) {
            $tahun -= 1;
        }

        // Tulis file Excel
        $writer = new Xlsx($spreadsheet);
        $filename = "Laporan LPLPO {$namaBulan} {$tahun}.xlsx";

        //Nama Sheet
        $sheet->setTitle($namaBulan . " " . $tahun);

        // Kirim header untuk trigger download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Tulis file ke output php://output
        $writer->save('php://output');
        exit;
    }
}
