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
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <button type="submit" class="btn btn-primary">Tambah</button>
    </div>
</form>





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


<!-- AJAX User -->
<script src="/assets/Ajax/Tabel/Gudang/stok_obat.js"></script>

<!-- javascript untuk tabel -->
<script src="/assets/ForTables/Gudang/Stok_obat.js"></script>