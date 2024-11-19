<div class="main">
    <!-- BOF MAIN-BODY -->
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="caption uppercase">
                        <i class="ti-briefcase"></i> LPLPO Narkotika dan Obat Keras
                    </div>
                </div>
                <div class="card-header">
                    <!-- Other header elements -->

                    <!-- Form untuk memilih bulan dan tahun -->

                    <div class="row">
                        <div class="col">
                            <select class="form-control" name="bulan" id="bulan">
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="col">
                            <select class="form-control" name="tahun" id="tahun">
                                <?php foreach ($years as $year) : ?>
                                    <option value="<?= esc($year['year']) ?>"><?= esc($year['year']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col">
                            <button id="filter" class="btn btn-primary">Tampilkan</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <button id="update-all" class="btn btn-success mb-3">Save All Changes</button>
                    <div class="table-responsive">
                        <table id="lplpo" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th rowspan="2" style="text-align: center;">No.</th>
                                    <th rowspan="2" style="text-align: center;">Nama Obat</th>
                                    <th rowspan="2" style="text-align: center;">Satuan</th>
                                    <th rowspan="2" style="text-align: center;">Stok Awal</th>
                                    <th rowspan="2" style="text-align: center;">Penerimaan</th>
                                    <th rowspan="2" style="text-align: center;">Persediaan</th>
                                    <th rowspan="2" style="text-align: center;">Pemakaian</th>
                                    <th rowspan="2" style="text-align: center;">Sisa Stok</th>
                                    <th rowspan="2" style="text-align: center;">Stok Optimum</th>
                                    <th rowspan="2" style="text-align: center;">Permintaan</th>
                                    <th colspan="4" style="text-align: center;">Pemberian bulan</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">APBD</th>
                                    <th style="text-align: center;">Program</th>
                                    <th style="text-align: center;">Lain-Lain</th>
                                    <th style="text-align: center;">Jumlah</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- EOF MAIN-BODY -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Misalkan Anda telah mengirim 'latestYear' dan 'latestMonth' ke view Anda sebagai variable JavaScript.
        const latestYear = <?= $latestYear ?>;
        const latestMonth = <?= $latestMonth ?>;

        // Set value dropdown untuk tahun dan bulan.
        document.getElementById('tahun').value = latestYear;
        document.getElementById('bulan').value = latestMonth;

        // Anda mungkin perlu memicu event onchange untuk dropdown jika Anda menggunakan AJAX untuk memuat data tabel.
        $('#tahun').change();
        $('#bulan').change();
    });
</script>

<!-- javascript untuk tabel dan AJAX -->
<script src="/assets/ForTables/Gudang/lplpo.js"></script>