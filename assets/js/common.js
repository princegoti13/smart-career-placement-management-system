/*
==========================================================
Smart Career & Placement Management System
Common JavaScript
==========================================================
*/

document.addEventListener("DOMContentLoaded", function () {
  /* ==========================================
       Mobile Sidebar Toggle
    ========================================== */

  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebar = document.querySelector(".sidebar");

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("active");
    });
  }

  /* ==========================================
       Active Sidebar Menu
    ========================================== */

  let currentPage = window.location.pathname.split("/").pop();

  document.querySelectorAll(".sidebar-menu a").forEach(function (link) {
    let href = link.getAttribute("href");

    if (href === currentPage) {
      link.parentElement.classList.add("active");
    }
  });

  /* ==========================================
       Auto Hide Alerts
    ========================================== */

  setTimeout(function () {
    let alerts = document.querySelectorAll(".alert");

    alerts.forEach(function (alert) {
      alert.style.transition = "0.5s";

      alert.style.opacity = "0";

      setTimeout(function () {
        alert.remove();
      }, 500);
    });
  }, 3000);
});

/* ==========================================
   Delete Confirmation
========================================== */

function confirmDelete(url) {
  Swal.fire({
    title: "Are You Sure?",

    text: "You Want To Delete This Record.",

    icon: "warning",

    showCancelButton: true,

    confirmButtonColor: "#dc3545",

    cancelButtonColor: "#6c757d",

    confirmButtonText: "Yes, Delete",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
}

/* ==========================================
   Image Preview
========================================== */

function previewImage(input, imageId) {
  if (input.files && input.files[0]) {
    let reader = new FileReader();

    reader.onload = function (e) {
      document.getElementById(imageId).src = e.target.result;
    };

    reader.readAsDataURL(input.files[0]);
  }
}

/* ==========================================
   Number Only
========================================== */

function onlyNumber(event) {
  let charCode = event.which ? event.which : event.keyCode;

  if (charCode > 31 && (charCode < 48 || charCode > 57)) {
    event.preventDefault();
  }
}

/* ==========================================
   Loader
========================================== */

function showLoader() {
  let loader = document.getElementById("loader");

  if (loader) {
    loader.style.display = "block";
  }
}

function hideLoader() {
  let loader = document.getElementById("loader");

  if (loader) {
    loader.style.display = "none";
  }
}
