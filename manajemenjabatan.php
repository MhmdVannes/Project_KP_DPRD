<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Sekretariat Dewan Perwakilan Rakyat Daerah Provinsi Sulawesi Tenggara</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="img/logosultra.png"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
    <style>
      /* Inactive Tab Color */
  .nav-tabs .nav-link {
    color: #6c757d !important; /* Dark Gray for inactive tabs */
  }

  /* Active Tab Color */
  .nav-tabs .nav-link.active {
    color: #343a40 !important; /* Dark color for the active tab */
    font-weight: bold; /* Bold the active tab text */
    background-color: #f8f9fa; /* Optional: Light background for active tab */
  }

  /* Hover Effect */
  .nav-tabs .nav-link:hover {
    color: #0056b3 !important; /* Dark blue for hover */
  }
    </style>





    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/plugins.min.css" />
    <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="assets/css/demo.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.php" class="logo">
              <img
                src="img/logosultra.png"
                alt="navbar brand"
                class="navbar-brand"
                height="50"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
            <li class="nav-item">
              <a href="index.php">
            <i class="fas fa-home"></i>
            <p>Dashboard</p>
            </a>
              </li>
              <li class="nav-item active">
              <a href="manajemenjabatan.php">
                  <i class="fas fa-user-tie"></i>
                  <p>Manajemen Jabatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="manajemenpengguna.php">
                  <i class="fas fa-user"></i>
                  <p>Manajemen Pengguna</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="analisisjabatan.php">
                  <i class="fas fa-tasks"></i>
                  <p>Analisis Jabatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="strukturorganisasi.php">
                  <i class="fas fa-users-cog"></i>
                  <p>Struktur Organisasi</p>
                </a>
              </li>
              
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.php" class="logo">
                <img
                  src="img/logosultra.png"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
           
          <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
  <div class="container-fluid">
    <!-- Logo dan Teks -->
    <a class="navbar-brand d-flex align-items-center" href="#">
      <img src="img/logosultra.png" alt="SIASN Logo" class="d-inline-block me-2" style="height: 50px; max-height: 50px;">
      <span style="font-weight: 1000; color:rgb(3, 36, 71);">SEKRETARIAT DEWAN PERWAKILAN RAKYAT DAERAH PROVINSI SULAWESI TENGGARA</span>
    </a>

    <!-- Navbar Item -->
    <ul class="navbar-nav ms-md-auto align-items-center">
      <!-- Tombol Pencarian (untuk tampilan mobile) -->
      <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
          <i class="fa fa-search"></i>
        </a>
        <ul class="dropdown-menu dropdown-search animated fadeIn">
          <form class="navbar-left navbar-form nav-search">
            <div class="input-group">
              <input type="text" placeholder="Search ..." class="form-control" />
            </div>
          </form>
        </ul>
      </li>
      
      <!-- Profile User Dropdown -->
      <li class="nav-item topbar-user dropdown hidden-caret">
        <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
          <!-- Flexbox untuk menempatkan ikon avatar dan nama pengguna -->
          <div class="d-flex align-items-center">
            <div class="avatar-sm me-2">
              <i class="fas fa-user-circle fa-3x"></i> <!-- Ikon pengguna -->
            </div>
          </div>
        </a>
        <!-- Dropdown menu untuk profile -->
<ul class="dropdown-menu dropdown-user animated fadeIn">
  <div class="dropdown-user-scroll scrollbar-outer">
    <!-- Informasi Akun: Nama dan NIK -->
    <li class="dropdown-item">
      <div class="account-info">
        <p class="account-name fw-bold">ANDRIANSYAH WICAKSONO</p>
      </div>
    </li>
    <!-- Tombol Logout -->
    <li><a class="dropdown-item" href="#">Logout</a></li>
  </div>
</ul>

      </li>
    </ul>
  </div>
</nav>
<br>

          <h1 class="page-title text-center mb-1" style="font-weight: 1000; color:rgb(3, 36, 71);">MANAJEMEN JABATAN</h1>
          <div class="d-flex align-items-center mt-3">
  <!-- Pencarian -->
  <div class="input-group w-25 me-2">
    <span class="input-group-text">
      <i class="fas fa-search"></i> <!-- Ikon pencarian -->
    </span>
    <input type="search" class="form-control" placeholder="Cari..." id="searchUser" />
  </div>

  <!-- Button Tambah Baru -->
  <button class="btn btn-dark btn-sm">
    <i class="fas fa-plus"></i> Tambah Baru
  </button>
</div>
<br>
          <ul class="nav nav-tabs w-60 d-flex justify-content-between" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <a class="nav-link active" id="data-jabatan-tab" data-bs-toggle="tab" href="#data-jabatan" role="tab" aria-controls="data-jabatan" aria-selected="true">Data Jabatan</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="unit-kerja-tab" data-bs-toggle="tab" href="#unit-kerja" role="tab" aria-controls="unit-kerja" aria-selected="false">Unit Kerja</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="golongan-tab" data-bs-toggle="tab" href="#golongan" role="tab" aria-controls="golongan" aria-selected="false">Golongan</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link" id="kompetensi-tab" data-bs-toggle="tab" href="#kompetensi" role="tab" aria-controls="kompetensi" aria-selected="false">Kompetensi</a>
  </li>
</ul>
<br>
<!-- Table Responsif -->
<div class="table-responsive">
  <table class="table table-striped table-bordered table-hover">
    <thead class="thead-dark">
      <tr>
        <th>No.</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Peran</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">1</th>
        <td>John Doe</td>
        <td>johndoe@example.com</td>
        <td>Admin</td>
        <td><span class="badge bg-success">Aktif</span></td>
        <td>
          <a href="edit_pengguna.php?id=1" class="btn btn-primary btn-sm">Edit</a>
          <a href="hapus_pengguna.php?id=1" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
        </td>
      </tr>
      <tr>
        <th scope="row">2</th>
        <td>Jane Smith</td>
        <td>janesmith@example.com</td>
        <td>User</td>
        <td><span class="badge bg-success">Aktif</span></td>
        <td>
          <a href="edit_pengguna.php?id=2" class="btn btn-primary btn-sm">Edit</a>
          <a href="hapus_pengguna.php?id=2" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
        </td>
      </tr>
      <tr>
        <th scope="row">3</th>
        <td>Samuel Lee</td>
        <td>samuellee@example.com</td>
        <td>Manager</td>
        <td><span class="badge bg-warning text-dark">Non-aktif</span></td>
        <td>
          <a href="edit_pengguna.php?id=3" class="btn btn-primary btn-sm">Edit</a>
          <a href="hapus_pengguna.php?id=3" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
        </td>
      </tr>
    </tbody>
  </table>
</div>



          <!-- End Navbar -->
        </div>


      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="assets/js/setting-demo.js"></script>
    
  <script>// Show the Add User form (Modal)
function showAddUserForm() {
    document.getElementById("addUserFormModal").style.display = "block";
}

// Hide the Add User form (Modal)
function hideAddUserForm() {
    document.getElementById("addUserFormModal").style.display = "none";
}</script>
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
  </body>
</html>
