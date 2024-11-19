$(document).ready(function () {
  var table = $("#stok_obat").DataTable({
    processing: true,
    serverSide: true,
    ajax: {
      url: "/Gudang/get_data_stok_obat", // URL untuk mengambil data dari server
      type: "POST",
    },
    order: [[1, "desc"]], // Mengurutkan kolom kedua secara "ascend" dengan default
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Semua"],
    ],
    responsive: true,
    columnDefs: [
      { responsivePriority: 1, targets: 2 },
      { responsivePriority: 2, targets: 7, orderable: false },
      { responsivePriority: 3, targets: 4 },
      { responsivePriority: 4, targets: 5 },
      { responsivePriority: 5, targets: 0 },
      { responsivePriority: 6, targets: 1 },
      { responsivePriority: 7, targets: 3 },
      { responsivePriority: 8, targets: 6 },
    ],
    //   initComplete: function () {
    //     // Mendeteksi perubahan ukuran jendela
    //     $(window).on('resize', function () {
    //         // Memeriksa apakah lebar layar kurang dari 420px
    //         if ($(window).width() < 420) {
    //             // Menonaktifkan responsif DataTables
    //             table.responsive.recalc();
    //             table.responsive.details(false);
    //         } else {
    //             // Mengaktifkan kembali responsif DataTables
    //             table.responsive.recalc();
    //             table.responsive.details(true);
    //         }
    //     });

    //     // Memeriksa lebar layar saat inisialisasi
    //     if ($(window).width() < 420) {
    //         // Menonaktifkan responsif DataTables
    //         table.responsive.recalc();
    //         table.responsive.details(false);
    //     }
    // },
    search: {
      return: true, // Memakai ini karena menutupi bug untuk pencarian agustus dan desember jika belum di tulis secara penuh tidak akan muncul
    },
    pagingType: "first_last_numbers",
    language: {
      search: "Cari:",
      zeroRecords: "Tidak ditemukan data yang sesuai",
      emptyTable:
        "Tidak ada data yang tersedia, mohon masukkan data terlebih dahulu",
      info: "Sedang menampilkan _START_ sampai _END_ dari _TOTAL_ Data",
      infoEmpty: "Menampilkan 0 sampai 0 dari 0 Produk",
      infoFiltered: "(disaring dari _MAX_ Data keseluruhan)",
      lengthMenu: "Tampilkan _MENU_ Data",
      loadingRecords: "Sedang memuat...",
      processing: "Sedang memproses...",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya",
      },
      select: {
        rows: {
          _: "%d baris terpilih",
          0: "",
        },
      },
    },
  });

  function convertToYYYYMM(dateStr) {
    const monthNames = [
      "Januari",
      "Februari",
      "Maret",
      "April",
      "Mei",
      "Juni",
      "Juli",
      "Agustus",
      "September",
      "Oktober",
      "November",
      "Desember",
    ];
    let parts = dateStr.split(" "); // Memecah string menjadi [Bulan, Tahun]
    let monthIndex = monthNames.indexOf(parts[0]); // Mendapatkan indeks bulan
    let year = parts[1];

    // Menambahkan 0 jika bulan kurang dari 10 (untuk format MM)
    let month = monthIndex < 9 ? "0" + (monthIndex + 1) : "" + (monthIndex + 1);

    return year + "-" + month; // Menggabungkan kembali dalam format YYYY-MM
  }

  // Handle click on "Edit" button
  $("#stok_obat tbody").on("click", ".btn-edit", function () {
    var data = table.row($(this).parents("tr")).data();
    // var id = data[0];
    var id = $(this).data("id"); // Get the ID from the button
    var actionUrl = "/Gudang/proses_edit_Stok_obat/" + id; // Create the action URL with the ID
    $("#editForm").attr("action", actionUrl);
    $("#edit-id").val(id);
    $("#tanggal").val(convertToYYYYMM(data[1]));
    $("#NAMA_OBAT").val(data[2]);
    $("#SATUAN").val(data[3]);
    $("#MASUK").val(data[4]);
    $("#KELUAR").val(data[5]);

    // $("#editModalStok_obat").modal("show");
  });

  // Handle click on "Delete" button
  $("#stok_obat tbody").on("click", ".btn-delete", function () {
    // var data = table.row($(this).parents("tr")).data();
    var id = $(this).data("id"); // Get the ID from the button
    var actionUrl = "/Gudang/hapus_Stok_obat/" + id; // Create the action URL with the ID
    $(".delete-form").attr("action", actionUrl);
  });
});
