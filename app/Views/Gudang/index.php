<?= $this->extend('Layout/Template'); ?>

<?= $this->section('isi'); ?>
<div class="main">
    <!-- BOF MAIN-BODY -->
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="caption uppercase">
                        <i class="ti-support"></i> Stok Obat
                    </div>
                    <!-- BOF insert modal -->
                    <!-- Insert tidak perlu ada id -->
                    <div class="tools">
                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#insertModalStok_obat"><i class="ti-plus"></i></button>
                        <div class="modal fade" id="insertModalStok_obat" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="insertModalLabel">Tambah Data</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="InsertFormStok_obat" action="/Gudang/proses_tambah_Stok_obat" method="POST" name="InsertFormStok_obat">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="col-form-label">Tanggal</label>
                                                <input type="month" class="form-control" name="tanggal">
                                                <div id="tanggalError" class="invalid-feedback"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="col-form-label">Nama Obat</label>
                                                <input list="obat" class="form-control" name="NAMA_OBAT" autocomplete="off">
                                                <datalist id="obat">
                                                    <?php foreach ($NamaObatUnik as $d) : ?>
                                                        <option value="<?= esc($d['NAMA_OBAT']) ?>">
                                                        <?php endforeach; ?>
                                                </datalist>
                                                <div id="obatError" class="invalid-feedback"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="col-form-label">Satuan</label>
                                                <input list="satua" class="form-control" name="SATUAN" autocomplete="off">
                                                <datalist id="satua">
                                                    <?php foreach ($SatuanUnik as $d) : ?>
                                                        <option value="<?= esc($d['SATUAN']) ?>">
                                                        <?php endforeach; ?>
                                                </datalist>
                                                <div id="satuanError" class="invalid-feedback"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="col-form-label">Masuk</label>
                                                <input type="number" class="form-control" name="MASUK">
                                                <div id="masukError" class="invalid-feedback"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="col-form-label">Keluar</label>
                                                <input type="number" class="form-control" name="KELUAR">
                                                <div id="keluarError" class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                                            <button type="submit" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- EOF insert modal -->
                </div>

                <div class="card-body">
                    <i class="col-form-label">Catatan: Sisa obat otomatis di hitung</i>
                    <div class="table-responsive">
                        <table id="stok_obat" class="table table-striped table-bordered table-hover" style="width: 100%;">
                            <thead class="thead-light">
                                <tr>
                                    <th rowspan="2" style="text-align: center;">No.</th>
                                    <th rowspan="2" style="text-align: center;">Tanggal</th>
                                    <th rowspan="2" style="text-align: center;">Nama Obat</th>
                                    <th rowspan="2" style="text-align: center;">Satuan</th>
                                    <th colspan="2" style="text-align: center;">Banyaknya</th>
                                    <th rowspan="2" style="text-align: center;">Sisa</th>
                                    <th rowspan="2" style="text-align: center;">Aksi</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">Masuk</th>
                                    <th style="text-align: center;">Keluar</th>
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


<!-- BOF edit modal -->
<!-- Edit perlu ada id -->
<div class="modal fade" id="editModalStok_obat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="/Gudang/proses_edit_Stok_obat" method="POST" name="editForm">
                <input type="hidden" name="id" value="" id="edit-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="col-form-label">Tanggal</label>
                        <input type="month" class="form-control" name="tanggal" id="tanggal">
                        <div id="tanggalError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Nama Obat</label>
                        <input list="obat" class="form-control" name="NAMA_OBAT" id="NAMA_OBAT" autocomplete="off">
                        <datalist id="obat">
                            <?php foreach ($NamaObatUnik as $d) : ?>
                                <option value="<?= esc($d['NAMA_OBAT']) ?>">
                                <?php endforeach; ?>
                        </datalist>
                        <div id="obatError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Satuan</label>
                        <input list="satua" class="form-control" name="SATUAN" id="SATUAN" autocomplete="off">
                        <datalist id="satua">
                            <?php foreach ($SatuanUnik as $d) : ?>
                                <option value="<?= esc($d['SATUAN']) ?>">
                                <?php endforeach; ?>
                        </datalist>
                        <div id="satuanError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Obat Masuk</label>
                        <input type="number" class="form-control" name="MASUK" id="MASUK">
                        <div id="masukError" class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Obat Keluar</label>
                        <input type="number" class="form-control" name="KELUAR" id="KELUAR">
                        <div id="keluarError" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- EOF edit modal -->

<!-- BOF delete modal -->
<div class="modal fade delete-modal" id="deleteModalStok_obat" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="delete-form" action="/Gudang/hapus_Stok_obat" method="POST">
            <?= csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apa anda yakin ingin menghapus data tersebut?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- EOF delete modal -->

<!-- AJAX User -->
<script src="/assets/Ajax/Tabel/Gudang/stok_obat.js"></script>

<!-- javascript untuk tabel -->
<script src="/assets/ForTables/Gudang/Stok_obat.js"></script>
<?= $this->endSection(); ?>