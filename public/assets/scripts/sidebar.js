$.each([".maincat", ".tier1"], function (e, t) {
  $(t + ">a").click(function () {
    $(this).parent().hasClass("active")
      ? ($(t).removeClass("active"), $(this).parent().removeClass("active"))
      : ($(t).removeClass("active"), $(this).parent().addClass("active"));
  });
}),
  $(".toggle-sidebar-btn").click(function () {
    $(".grid-wrapper").hasClass("collapsed")
      ? $(".grid-wrapper").removeClass("collapsed")
      : //   $("#sidebar").unbind("hover"))
        $(".grid-wrapper").addClass("collapsed"),
      //   $("#sidebar").hover(
      //     function () {
      //       $(".grid-wrapper").addClass("sidebar-hovered");
      //     },
      //     function () {
      //       $(".grid-wrapper").removeClass("sidebar-hovered");
      //     }
      //   )
      $("i", this).toggleClass("ti-arrow-circle-left ti-arrow-circle-right");
  }),
  $(".slide-sidebar-btn").click(function (e) {
    e.preventDefault(), $(".slide-sidebar-btn, .sidebar").toggleClass("open");
  }),
  $(".slide-sidebar-btn.open").click(function (e) {
    e.preventDefault(), $(".sidebar").width(0);
  });

// Menghapus collapse saat list dengan kelas maincat di click
document.addEventListener("DOMContentLoaded", function () {
  const mainCatElement = document.querySelectorAll(".maincat");
  const contentElement = document.querySelector(".bg1");

  mainCatElement.forEach((list) => {
    list.addEventListener("click", () => {
      if (contentElement.classList.contains("collapsed")) {
        contentElement.classList.remove("collapsed");
      }
    });
  });
});

function RemoveClass() {
  const mainCats = document.querySelectorAll(".maincat");

  mainCats.forEach((list) => {
    if (list.classList.contains("active")) {
      list.classList.remove("active");
    }
  });
}
