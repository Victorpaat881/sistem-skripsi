$(document).ready(function () {
  // AJAX Edit
  $("#editForm").on("submit", function (e) {
    e.preventDefault();

    var formId = $(this).attr("id");

    // Validasi berhasil, hapus kelas "is-invalid" dari input form
    $("#" + formId + " input.form-control").removeClass("is-invalid");
    // Reset pesan kesalahan sebelum mengirim AJAX
    $("#" + formId + " .invalid-feedback")
      .empty()
      .hide();

    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        if (response.errors) {
          // Validasi gagal, tampilkan pesan kesalahan
          $.each(response.errors, function (key) {
            // Tambahkan kelas "is-invalid" pada input form yang bermasalah
            $("#" + formId + ' input[name="' + key + '"]').addClass(
              "is-invalid"
            );
          });

          // Tampilkan pesan kesalahan di bawah input form yang bermasalah
          $("#" + formId + " #tanggalError" + formId.substring(8))
            .text(response.errors.tanggal)
            .show();
          $("#" + formId + " #obatError" + formId.substring(8))
            .text(response.errors.NAMA_OBAT)
            .show();
          $("#" + formId + " #satuanError" + formId.substring(8))
            .text(response.errors.SATUAN)
            .show();
          $("#" + formId + " #masukError" + formId.substring(8))
            .text(response.errors.MASUK)
            .show();
          $("#" + formId + " #keluarError" + formId.substring(8))
            .text(response.errors.KELUAR)
            .show();
          // $("#" + formId + " #sisaError" + formId.substring(8))
          //   .text(response.errors.SISA)
          //   .show();
        } else {
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
          });

          Toast.fire({
            icon: "success",
            title: "Data berhasil Diubah",
          }).then(function () {
            $("#editModalStok_obat").modal("hide");
            // Muat ulang DataTables setelah menutup modal
            $("#stok_obat").DataTable().ajax.reload(null, false);
          });
        }
      },
    });
  });

  // AJAX Delete
  $(".delete-form").submit(function (e) {
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
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
          title: "Data berhasil Dihapus",
        }).then(function () {
          // Muat ulang halaman setelah menutup SweetAlert2
          $("#stok_obat").DataTable().ajax.reload(null, false);
        });
      },
    });
  });
});
