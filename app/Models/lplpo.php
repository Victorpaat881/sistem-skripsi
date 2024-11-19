<?php

namespace App\Models;

use CodeIgniter\Model;

class lplpo extends Model
{
    protected $table = 'lplpo';
    protected $primaryKey = 'id';
    protected $allowedFields = ['NAMA_OBAT', 'SATUAN', 'STOK_AWAL', 'PENERIMAAN', 'PERSEDIAAN', 'PEMAKAIAN', 'SISA_STOK', 'STOK_OPTIMUM', 'PERMINTAAN', 'tanggal', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function update_data($id, $penerimaan, $permintaan)
    {
        // Perform the update operation
        // Make sure to validate and sanitize input data for security
        $dataToUpdate = [
            'PENERIMAAN' => $penerimaan,
            'PERMINTAAN' => $permintaan
        ];
        $this->update(['id' => $id], $dataToUpdate); // Assuming 'update' is a method provided by the framework or your base model class
    }

    public function distinctYears()
    {
        return $this->select('YEAR(tanggal) as year')->distinct()->orderBy('year', 'desc')->findAll();
    }

    // Untuk sementara pakai 'id' untuk data di kolom 'No.'
    var $column_order = array('id', 'NAMA_OBAT', 'SATUAN', 'STOK_AWAL', 'PENERIMAAN', 'PERSEDIAAN', 'PEMAKAIAN', 'SISA_STOK', 'STOK_OPTIMUM', 'PERMINTAAN'); // Urutan kolom pada tabel
    var $column_search = array('NAMA_OBAT'); // Kolom yang dapat dicari
    var $order = array('NAMA_OBAT' => 'asc'); // Urutan default

    private function _get_datatables_query()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if (isset($_POST['search']) && $_POST['search']['value']) {
                if ($i === 0) {
                    $this->groupStart();
                    $this->like($item, $_POST['search']['value']);
                } else {
                    $this->orLike($item, $_POST['search']['value']);
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

    function get_datatables()
    {
        $this->_get_datatables_query();
        if (isset($_POST['length']) && isset($_POST['start']) && $_POST['length'] != -1) {
            $this->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->get();
        return $query->getResult();
    }

    public function count_filtered($bulan, $tahun)
    {
        $this->_get_datatables_query(); // Memanggil query dasar yang juga digunakan di getFilteredData

        // Menerapkan filter bulan dan tahun
        $this->where('MONTH(tanggal)', $bulan);
        $this->where('YEAR(tanggal)', $tahun);

        // Mengembalikan jumlah hasil yang difilter
        return $this->countAllResults();
    }

    public function count_all()
    {
        return $this->countAllResults();
    }
    // Method baru untuk mendapatkan data dengan filter bulan dan tahun
    public function getFilteredData($bulan, $tahun)
    {
        $this->_get_datatables_query();
        // Tambahkan logika filter berdasarkan bulan dan tahun
        $this->where('MONTH(tanggal)', $bulan);
        $this->where('YEAR(tanggal)', $tahun);

        if (isset($_POST['length']) && isset($_POST['start']) && $_POST['length'] != -1) {
            $this->limit($_POST['length'], $_POST['start']);
        }

        $query = $this->get();
        return $query->getResult();
    }
    public function getLatestDate()
    {
        $latestEntry = $this->select('tanggal')
            ->orderBy('tanggal', 'DESC')
            ->first();
        if ($latestEntry) {
            return $latestEntry['tanggal'];
        }
        return false;
    }
    public function getMonthsWithData($tahun)
    {
        return $this->distinct()->select('MONTH(tanggal) as month')
            ->where('YEAR(tanggal)', $tahun)
            ->findAll();
    }
}
