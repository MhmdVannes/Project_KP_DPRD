<?php
// Termasuk file koneksi ke database
include('koneksi.php');

// Menangani saat form dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);
    $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
    $peran = mysqli_real_escape_string($conn, $_POST['peran']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Memastikan password tidak kosong dan meng-encryptnya
    if (empty($password)) {
        $password = "defaultpassword";  // Atau sesuaikan sesuai kebijakan
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);  // Meng-hash password
    }

    // Menyusun query untuk menyimpan data pengguna ke dalam database
    $sql = "INSERT INTO users (nama, nip, jabatan, peran, password) VALUES ('$nama', '$nip', '$jabatan', '$peran', '$password')";
    
    // Menjalankan query dan memeriksa apakah berhasil
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pengguna berhasil ditambahkan!'); window.location.href = 'manajemenpengguna.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
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
        .page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
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
    <!-- Informasi Akun: Nama dan NIP -->
    <li class="dropdown-item">
      <div class="account-info">
        <p class="account-name fw-bold">
          <?php 
          if(isset($_SESSION['nama'])) {
            echo $_SESSION['nama']; // Menampilkan nama pengguna
          } else {
            echo "User";
          }
          ?>
        </p>
      </div>
    </li>
    <!-- Tombol Logout -->
    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
  </div>
</ul>

      </li>
    </ul>
  </div>
</nav>
<div class="container mt-5">
    <h1 class="page-title text-center mb-3">Tambah Pengguna</h1>

    <!-- Form untuk menambah pengguna -->
    <form method="POST">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" required>
        </div>

        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
        </div>

        <div class="mb-3">
            <label for="peran" class="form-label">Peran</label>
            <select class="form-select" id="peran" name="peran" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-success">Tambah Pengguna</button>
        <a href="manajemenpengguna.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


        </div>
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
<?php
// Menutup koneksi database
$conn->close();
?>