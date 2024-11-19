$(document).ready(function () {
  var table = $("#lplpo").DataTable({
    //Harus pakai var table kalau tidak event handler untuk tombol filter tidak akan berfungsi
    processing: true,
    serverSide: true,
    ajax: {
      url: "/Gudang/get_data_lplpo", // URL untuk mengambil data dari server
      type: "POST",
      data: function (d) {
        d.bulan = $("#bulan").val(); // Ambil nilai dari bulan yang dipilih
        d.tahun = $("#tahun").val(); // Ambil nilai dari tahun yang dipilih
      },
    },
    order: [[0, "asc"]], // Mengurutkan kolom pertama secara "ascend" dengan default
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "Semua"],
    ],

    responsive: true,
    columnDefs: [
      { searchable: false, orderable: true, responsivePriority: 5, targets: 0 },
      { searchable: false, orderable: true, responsivePriority: 2, targets: 1 },
      { searchable: false, orderable: true, responsivePriority: 8, targets: 2 },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 9,
        targets: 3,
      },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 10,
        targets: 4,
        // render: function (data, type, row) {
        //   return type === "display"
        //     ? '<input type="number" class="form-control input-PENERIMAAN" value="' +
        //         data +
        //         '">'
        //     : data;
        // },
      },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 11,
        targets: 5,
      },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 12,
        targets: 6,
      },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 13,
        targets: 7,
      },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 14,
        targets: 8,
      },
      {
        searchable: false,
        orderable: true,
        responsivePriority: 1,
        targets: 9,
        render: function (data, type, row) {
          return type === "display"
            ? '<input type="number" class="form-control input-PERMINTAAN" value="' +
                data +
                '">'
            : data;
        },
      },
      {
        searchable: false,
        orderable: false,
        responsivePriority: 4,
        targets: 10,
      },
      {
        searchable: false,
        orderable: false,
        responsivePriority: 3,
        targets: 11,
      },
      {
        searchable: false,
        orderable: false,
        responsivePriority: 6,
        targets: 12,
      },
      {
        searchable: false,
        orderable: false,
        responsivePriority: 7,
        targets: 13,
      },
    ],
    dom: "lBfrtip",
    buttons: [
      {
        text: "Excel",
        action: function (e, dt, node, config) {
          var bulan = $("#bulan").val(); // Sesuaikan dengan input filter bulan Anda
          var tahun = $("#tahun").val(); // Sesuaikan dengan input filter tahun Anda
          window.location.href =
            "/Gudang/export_excel?bulan=" + bulan + "&tahun=" + tahun;
        },
      },
    ],
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
  // Event handler untuk tombol filter
  $("#filter").on("click", function () {
    table.ajax.reload(null, false); // Reload data tabel tanpa reset pagination
  });

  // Struktur untuk menyimpan perubahan
  var updatedData = [];

  $("#lplpo tbody").on(
    "change",
    "input.input-PENERIMAAN, input.input-PERMINTAAN",
    function () {
      var $input = $(this);
      var $row = $input.closest("tr");
      var rowId = $row.attr("id"); // Assuming each row has an id attribute like 'row_1'

      if (!rowId) {
        console.error("No ID attribute found for this row. Cannot update.");
        return;
      }

      // Mengumpulkan nilai dari semua kolom
      var newData = {
        DT_RowId: rowId,
        STOK_AWAL: $row.find(".stok_awal").text(),
        PENERIMAAN: $row.find(".penerimaan").text(),
        PERSEDIAAN: $row.find(".persediaan").text(),
        PEMAKAIAN: $row.find(".pemakaian").text(),
        SISA_STOK: $row.find(".sisa_stok").text(),
        STOK_OPTIMUM: $row.find(".stok_optimum").text(),
        PERMINTAAN: $row.find("input.input-PERMINTAAN").val(),
      };

      // Update the updatedData array with new entry or modify existing one
      var existingEntry = updatedData.find((entry) => entry.DT_RowId === rowId);
      if (existingEntry) {
        existingEntry.STOK_AWAL = newData.STOK_AWAL;
        existingEntry.PENERIMAAN = newData.PENERIMAAN;
        existingEntry.PERSEDIAAN = newData.PERSEDIAAN;
        existingEntry.PEMAKAIAN = newData.PEMAKAIAN;
        existingEntry.SISA_STOK = newData.SISA_STOK;
        existingEntry.STOK_OPTIMUM = newData.PENERIMAAN;
        existingEntry.PERMINTAAN = newData.PERMINTAAN;
      } else {
        updatedData.push(newData);
      }

      // Log the updated data for debugging purposes
      // console.log("Updated data:", updatedData);
    }
  );

  // JavaScript AJAX call to send updated data to the server
  $("#update-all").on("click", function () {
    var dataToSend = updatedData.map(function (item) {
      return {
        id: item.DT_RowId.replace("row_", ""), // Strip 'row_' to get the actual ID
        PENERIMAAN: item.PENERIMAAN,
        PERMINTAAN: item.PERMINTAAN,
      };
    });

    // console.log("Attempting to send updated data:", dataToSend);

    $.ajax({
      url: "/Gudang/update_data", // Endpoint for updating data
      type: "POST",
      data: { data: dataToSend },
      dataType: "json",
      success: function (response) {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
        });

        Toast.fire({
          icon: "success",
          title: "Data berhasil diubah",
        }).then(function () {
          // Muat ulang halaman setelah menutup SweetAlert2
          $("#lplpo").DataTable().ajax.reload(null, false);
        });
      },
      error: function (xhr, status, error) {
        const Toast = Swal.mixin({
          toast: true,
          position: "top-end",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
        });

        Toast.fire({
          icon: "error",
          title: "Data gagal diubah",
        });
      },
    });
  });
});
