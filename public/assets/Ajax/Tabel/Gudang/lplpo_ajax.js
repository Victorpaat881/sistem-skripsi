// Struktur untuk menyimpan perubahan
var updatedData = [];

// Ubah event handler untuk menyimpan data yang diubah ke dalam array
$("#lplpo tbody").on(
  "change",
  "input.input-PENERIMAAN, input.input-PERMINTAAN",
  function () {
    var $input = $(this);
    var row = table.row($input.closest("tr"));
    var data = row.data();

    // Check if row data is available
    if (!data) {
      console.error("No data available for this row.");
      return; // Exit the function if data is undefined
    }

    // Logging untuk debugging
    console.log("Input changed in row:", row.index());
    console.log(
      "Column name:",
      $input.hasClass("input-PENERIMAAN") ? "PENERIMAAN" : "PERMINTAAN"
    );
    console.log("New value:", $input.val());

    // Mencari apakah sudah ada data yang diperbarui untuk baris ini
    var existingEntry = updatedData.find((entry) => entry.id === data[0]);

    // Menambahkan atau memperbarui entry dalam array updatedData
    if (existingEntry) {
      // Update nilai yang ada
      if ($input.hasClass("input-PENERIMAAN")) {
        existingEntry.PENERIMAAN = $input.val();
      } else if ($input.hasClass("input-PERMINTAAN")) {
        existingEntry.PERMINTAAN = $input.val();
      }
    } else {
      // Tambahkan entry baru jika belum ada
      var newData = {
        id: data[0], // Anda mungkin perlu menyesuaikan ini tergantung pada struktur data Anda
        PENERIMAAN: $input.hasClass("input-PENERIMAAN")
          ? $input.val()
          : data[4], // Atau kolom yang benar untuk 'penerimaan'
        PERMINTAAN: $input.hasClass("input-PERMINTAAN")
          ? $input.val()
          : data[9], // Atau kolom yang benar untuk 'permintaan'
      };
      updatedData.push(newData);
    }

    // Log array updatedData untuk debugging
    console.log("Updated data:", updatedData);
  }
);

$("#update-all").on("click", function () {
  console.log("Attempting to send updated data:", updatedData);
  if (updatedData.length === 0) {
    console.error("No data to send!");
    return;
  }
  $.ajax({
    url: "/Gudang/update_data", // Sesuaikan dengan URL controller Anda
    type: "POST",
    data: { updatedData: updatedData },
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
        updatedData = [];
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
