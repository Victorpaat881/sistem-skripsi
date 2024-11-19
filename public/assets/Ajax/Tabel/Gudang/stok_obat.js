$(document).ready(function () {
  //AJAX Insert
  $("#InsertFormStok_obat").submit(function (e) {
    e.preventDefault();
    // Reset pesan kesalahan sebelum mengirim AJAX
    $(".invalid-feedback").empty().hide();
    // Validasi berhasil, hapus kelas "is-invalid" dari input form
    $("#InsertFormStok_obat input.form-control").removeClass("is-invalid");

    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        if (response.errors) {
          // Validasi gagal, tampilkan pesan kesalahan
          $("#tanggalError").text(response.errors.tanggal).show();
          $("#obatError").text(response.errors.NAMA_OBAT).show();
          $("#satuanError").text(response.errors.SATUAN).show();
          $("#masukError").text(response.errors.MASUK).show();
          $("#keluarError").text(response.errors.KELUAR).show();

          $.each(response.errors, function (key) {
            // Tambahkan kelas "is-invalid" pada input form yang bermasalah
            $('#InsertFormStok_obat input[name="' + key + '"]').addClass(
              "is-invalid"
            );
          });
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
            title: "Data berhasil Ditambahkan",
          }).then(function () {
            // Muat ulang halaman setelah menutup SweetAlert2
            window.location.href = response.url;
          });
        }
        //if success tampilkan data telah ditambahkan dengan notifikasi kalau bisa kalau tidak pakai yang biasanya saja
        //else tampilkan error di dalam form modal
      },
    });
  });

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
          $("#deleteModalStok_obat").modal("hide");
          // Muat ulang halaman setelah menutup SweetAlert2
          $("#stok_obat").DataTable().ajax.reload(null, false);
        });
      },
    });
  });
});
