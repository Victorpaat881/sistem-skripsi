<?php

namespace App\Models;

use CodeIgniter\Model;

class Stok_Obat extends Model
{
    protected $table = 'stok_obat';
    protected $primaryKey = 'id';
    protected $allowedFields = ['NAMA_OBAT', 'SATUAN', 'MASUK', 'KELUAR', 'SISA', 'tanggal', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function getUniqueSatuan()
    {
        return $this->distinct()->select('SATUAN')->findAll();
    }
    public function getUniqueNamaObat()
    {
        return $this->distinct()->select('NAMA_OBAT')->findAll();
    }

    // public function saveData($data)
    // {
    //     $data['SISA'] = $data['MASUK'];
    //     return $this->save($data);
    // }
    // public function updateData($id, $data)
    // {
    //     // Menghitung SISA dari MASUK dan KELUAR
    //     $data['SISA'] = $data['MASUK'] - $data['KELUAR'];

    //     // Melakukan update data dengan id yang diberikan
    //     return $this->update($id, $data);
    // }

    // Untuk sementara pakai 'id' untuk data di kolom 'No.'
    var $column_order = array('id', 'tanggal', 'NAMA_OBAT', 'SATUAN', 'MASUK', 'KELUAR', 'SISA'); // Urutan kolom pada tabel
    var $column_search = array('tanggal', 'NAMA_OBAT', 'SATUAN', 'MASUK', 'KELUAR', 'SISA'); // Kolom yang dapat dicari
    var $order = array('tanggal' => 'asc'); // Urutan default

    private function _get_datatables_query()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($_POST['search']) && $_POST['search']['value']) {
                $searchValue = $this->translateIndonesianMonthToEnglish($_POST['search']['value']);

                if ($i === 0) {
                    $this->groupStart();

                    if ($item == 'tanggal') {
                        $this->like("DATE_FORMAT($item, '%M %Y')", $searchValue);
                    } else {
                        $this->like($item, $searchValue);
                    }
                } else {
                    if ($item == 'tanggal') {
                        $this->orLike("DATE_FORMAT($item, '%M %Y')", $searchValue);
                    } else {
                        $this->orLike($item, $searchValue);
                    }
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->groupEnd();
                }
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->orderBy(key($order), $order[key($order)]);
        }
    }
    private function translateIndonesianMonthToEnglish($searchTerm)
    {
        // Define mapping from Indonesian to English months
        $monthMapping = [
            'Januari' => 'January', 'Februari' => 'February', 'Maret' => 'March',
            'April' => 'April', 'Mei' => 'May', 'Juni' => 'June',
            'Juli' => 'July', 'Agustus' => 'August', 'September' => 'September',
            'Oktober' => 'October', 'November' => 'November', 'Desember' => 'December'
        ];

        // Check if search term contains Indonesian month names and translate
        foreach ($monthMapping as $indoMonth => $engMonth) {
            if (strpos(strtolower($searchTerm), strtolower($indoMonth)) !== false) {
                return str_replace(strtolower($indoMonth), $engMonth, strtolower($searchTerm));
            }
        }

        // If no month names are found, return the original search term
        return $searchTerm;
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && isset($_POST['start']) && $_POST['length'] != -1) {
            $this->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->get();
        error_log("Final query: " . $this->getLastQuery());
        return $query->getResult();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->get();
        return count($query->getResult());
    }

    public function count_all()
    {
        return $this->countAllResults();
    }
}
