<?= $this->extend('Layout/Template'); ?>

<?= $this->section('isi'); ?>
<div class="main">
    <!-- BOF MAIN-BODY -->
    <div class="row">
        <div class="col">
            <div class="card mb-3">
                <div class="card-header">
                    <div class="caption uppercase">
                        <i class="ti-support"></i> LPLPO (Laporan Pemakaian dan Lembar Permintaan Obat)
                    </div>
                </div>
                <div class="card-header">
                    <!-- Other header elements -->

                    <!-- Form untuk memilih bulan dan tahun -->

                    <div class="row">
                        <div class="col">
                            <select class="form-control" name="bulan" id="bulan">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
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
                            <button id="filter" class="btn btn-primary" onclick="getDataforlplpo()">Tampilkan</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-between m-1">
                        <button id="update-all" class="btn btn-success btn-tess mb-3 col-3">Simpan Kolom Permintaan</button>
                        <button class="btn btn-success btn-tess mb-3 col-2" data-bs-toggle="modal" data-bs-target="#predictionModal" onclick="getPrediction()">Prediksi</button>
                    </div>
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

<!-- Modal untuk menampilkan hasil prediksi -->
<div class="modal fade" id="predictionModal" tabindex="-1" role="dialog" aria-labelledby="predictionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="predictionModalLabel">Hasil Prediksi</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="predictionTableBody">
                <div id="predictionTable"></div>
                <!-- Loading screen -->
                <div id="loadingSpinner" class="dot-spinner">
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                    <div class="dot-spinner__dot"></div>
                </div>
                <!-- <div style="margin-top: 140px;"></div> -->
                <p class="text-center" id="loading-text" style="margin-top: 200px; margin-bottom: 20px; padding-bottom: 50px;">Mohon Tunggu Sebentar...</p>
                <!-- Tempat untuk menampilkan hasil prediksi -->
            </div>
        </div>
    </div>
</div>

<style>
    #bulan option:disabled {
        /* Mengatur opasitas menjadi 50% saat opsi dinonaktifkan */
        color: rgb(220, 220, 220) !important;
    }

    .dot-spinner {
        --uib-size: 2.8rem;
        --uib-speed: .9s;
        --uib-color: #183153;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        height: var(--uib-size);
        width: var(--uib-size);
    }

    .dot-spinner__dot {
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        height: 100%;
        width: 100%;
    }

    .dot-spinner__dot::before {
        content: '';
        height: 20%;
        width: 20%;
        border-radius: 50%;
        background-color: var(--uib-color);
        transform: scale(0);
        opacity: 0.5;
        animation: pulse0112 calc(var(--uib-speed) * 1.111) ease-in-out infinite;
        box-shadow: 0 0 20px rgba(18, 31, 53, 0.3);
    }

    .dot-spinner__dot:nth-child(2) {
        transform: rotate(45deg);
    }

    .dot-spinner__dot:nth-child(2)::before {
        animation-delay: calc(var(--uib-speed) * -0.875);
    }

    .dot-spinner__dot:nth-child(3) {
        transform: rotate(90deg);
    }

    .dot-spinner__dot:nth-child(3)::before {
        animation-delay: calc(var(--uib-speed) * -0.75);
    }

    .dot-spinner__dot:nth-child(4) {
        transform: rotate(135deg);
    }

    .dot-spinner__dot:nth-child(4)::before {
        animation-delay: calc(var(--uib-speed) * -0.625);
    }

    .dot-spinner__dot:nth-child(5) {
        transform: rotate(180deg);
    }

    .dot-spinner__dot:nth-child(5)::before {
        animation-delay: calc(var(--uib-speed) * -0.5);
    }

    .dot-spinner__dot:nth-child(6) {
        transform: rotate(225deg);
    }

    .dot-spinner__dot:nth-child(6)::before {
        animation-delay: calc(var(--uib-speed) * -0.375);
    }

    .dot-spinner__dot:nth-child(7) {
        transform: rotate(270deg);
    }

    .dot-spinner__dot:nth-child(7)::before {
        animation-delay: calc(var(--uib-speed) * -0.25);
    }

    .dot-spinner__dot:nth-child(8) {
        transform: rotate(315deg);
    }

    .dot-spinner__dot:nth-child(8)::before {
        animation-delay: calc(var(--uib-speed) * -0.125);
    }

    @keyframes pulse0112 {

        0%,
        100% {
            transform: scale(0);
            opacity: 0.5;
        }

        50% {
            transform: scale(1);
            opacity: 1;
        }
    }
</style>

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

    function rotateLoaderText() {
        // Tambahkan variabel global untuk menyimpan teks loader
        let loaderTexts = ["Mohon Tunggu Sebentar...", "Sedang Mengambil Prediksi..."];
        let currentLoaderTextIndex = 0;
        // Ganti teks loader secara bergantian
        $("#loading-text").fadeOut(500, function() {
            $(this).text(loaderTexts[currentLoaderTextIndex]);
        }).fadeIn(500);

        // Ganti indeks teks loader
        currentLoaderTextIndex = (currentLoaderTextIndex + 1) % loaderTexts.length;
    }
    // Memanggil rotateLoaderText setiap detik untuk mengganti teks loader
    var intervalId = setInterval(rotateLoaderText, 12000);

    function getPrediction(bulan, tahun) {
        // Mendapatkan nilai bulan dan tahun dari input
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        // Bersihkan isi tabel sebelum menambahkan hasil prediksi baru
        $('#predictionTable').empty();
        // Tampilkan loading screen
        $('#loadingSpinner').show();
        $('#loading-text').show();

        // Panggil fungsi prediksi dan tampilkan hasilnya di modal
        $.ajax({
            url: '/Gudang/prediction/',
            type: 'GET',
            data: {
                bulan: bulan,
                tahun: tahun
            }, // Mengirim data bulan dan tahun
            success: function(response) {
                // Setelah mendapatkan hasil prediksi, sembunyikan layar loading
                $('#loadingSpinner').hide();
                $('#loading-text').hide();
                clearInterval(intervalId);
                // Setelah mendapatkan hasil prediksi, tampilkan di tabel modal
                displayPredictionResults(response);
            },
            error: function(error) {
                console.log('Gagal mendapatkan prediksi:', error);
                // Sembunyikan layar loading dalam kasus kesalahan
                $('#loadingSpinner').hide();
                $('#loading-text').hide();
                clearInterval(intervalId);
            }
        });
    }

    var phpData = <?php echo json_encode($Date); ?>;

    function getDataforlplpo(bulan, tahun) {
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();

        // Memanggil fungsi lplpo dengan tahun dan bulan yang dipilih
        $.ajax({
            url: '/Gudang/getLPLPOData', // URL endpoint dengan tahun dan bulan sebagai bagian dari URL
            type: 'GET',
            data: {
                bulan: bulan,
                tahun: tahun
            }, // Mengirim data bulan dan tahun
            success: function(response) {
                phpData = response.Date;
            },
            error: function(error) {
                console.log('Gagal mendapatkan data:', error);
            }
        });
    }

    let predictionData = [];

    function displayPredictionResults(results) {
        // Split string hasil prediksi berdasarkan baris baru
        let predictionValues = results.split('\n').filter(value => value.trim() !== '');

        // Buat tabel dan tambahkan satu baris untuk setiap nilai prediksi
        let table = '<p>Berikut adalah hasil prediksinya:</p>';
        table += '<button class="btn btn-outline-secondary" onclick="scrollToBottom()"><i class="ti-angle-double-down"></i></button>';
        table += '<table class="table table-striped table-bordered">';
        table += '<thead><tr><th style="text-align: center;">Nama Obat</th><th style="text-align: center;">Hasil Prediksi</th></tr></thead>';
        table += '<tbody>';

        // Iterasi melalui nilai prediksi dan tambahkan baris baru untuk setiap nilai
        for (let i = 0; i < predictionValues.length; i++) {
            // Tambahkan satu baris ke dalam tabel
            table += '<tr>';
            table += '<td>' + phpData[i]['NAMA_OBAT'] + '</td>';
            table += '<td>' + predictionValues[i] + '</td>';
            table += '</tr>';

            // Menyimpan data hasil prediksi untuk pengiriman ke server
            predictionData.push({
                'id': phpData[i]['id'],
                'PERMINTAAN': predictionValues[i]
            });
        }

        table += '</tbody></table>';
        table += '<h5>Apa anda yakin ingin mengimplementasi hasil tersebut ?</h5>';
        table += '<div class="d-flex justify-content-center flex-md-row">';
        table += '<button class="btn btn-primary" type="button" onclick="implementResults()">Simpan Permintaan Obat</button>';
        table += '</div>';

        // Tambahkan tabel ke dalam modal
        $('#predictionTable').append(table);

        // Tampilkan modal setelah menambahkan hasil prediksi ke dalam tabel
        $('#predictionModal').modal('show');

        // console.log('Data yang akan dikirim ke server:', predictionData);
    }

    function implementResults() {
        // Kirim data hasil prediksi ke server untuk disimpan ke database
        // console.log('Data yang akan dikirim sebelum AJAX:', predictionData);

        // Pastikan format JSON yang dikirimkan sesuai
        let jsonData = JSON.stringify(predictionData);
        // console.log(jsonData); // Cek format JSON yang dikirim
        $.ajax({
            url: '/Gudang/savePredictions', // Ganti dengan URL dan metode yang sesuai di controller Anda
            type: 'POST',
            contentType: 'application/json',
            data: jsonData,
            success: function(response) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                });

                Toast.fire({
                    icon: "success",
                    title: "Data berhasil disimpan",
                }).then(function() {
                    $("#predictionModal").modal("hide");
                    // Muat ulang halaman setelah menutup SweetAlert2
                    location.reload();
                });
            },
            error: function(error) {
                console.log('Gagal menyimpan hasil prediksi ke database:', error);
            }
        });
    }

    function scrollToBottom() {
        // Mendapatkan elemen modal
        var modal = $('#predictionModal');

        // Menggulir ke paling bawah dengan efek animasi
        modal.animate({
            scrollTop: modal.prop('scrollHeight')
        }, 2000);
    }

    $(document).ready(function() {
        // Ambil tahun dan bulan yang sudah terpilih saat pertama kali memuat halaman
        var tahun = $('#tahun').val();
        var bulan = $('#bulan').val();

        // Panggil fungsi untuk memperbarui opsi bulan sesuai dengan tahun yang dipilih
        updateMonthOptions(tahun, bulan);

        // Atur event handler untuk perubahan tahun
        $('#tahun').change(function() {
            var tahun = $(this).val();
            var bulan = $('#bulan').val();

            // Panggil fungsi untuk memperbarui opsi bulan sesuai dengan tahun yang dipilih
            updateMonthOptions(tahun, bulan);
        });

        $('#bulan').change(function() {
            if ($(this).find(':selected').is(':disabled')) {
                var firstEnabledMonth = $('#bulan option:not(:disabled):last').val();
                $('#bulan').val(firstEnabledMonth);
            }
        });
    });

    function updateMonthOptions(tahun) {
        $.ajax({
            url: '/Gudang/checkData',
            method: 'POST',
            data: {
                tahun: tahun
            },
            dataType: 'json',
            success: function(response) {
                if (!response.dataAvailable) {
                    // Nonaktifkan semua opsi bulan jika tidak ada data
                    $('#bulan option').prop('disabled', true);
                } else {
                    // Aktifkan opsi bulan yang memiliki data
                    var monthsWithData = response.monthsWithData;
                    $('#bulan option').prop('disabled', true);
                    monthsWithData.forEach(function(monthData) {
                        var month = monthData.month;
                        $('#bulan option[value="' + month + '"]').prop('disabled', false);
                    });

                    // Jika bulan yang dipilih tidak memiliki data, pindahkan ke bulan pertama yang memiliki data
                    var selectedMonth = $('#bulan').val();
                    if (!$('#bulan option[value="' + selectedMonth + '"]').prop('disabled')) {
                        var firstEnabledMonth = $('#bulan option:not(:disabled):last').val();
                        $('#bulan').val(firstEnabledMonth);
                    }
                }
            }
        });
    };
</script>

<!-- AJAX -->
<!-- <script src="/assets/Ajax/Tabel/Gudang/lplpo_ajax.js"></script> -->

<!-- javascript untuk tabel -->
<script src="/assets/ForTables/Gudang/lplpo.js"></script>
<?= $this->endSection(); ?>