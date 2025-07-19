<?php
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
$nama = isset($_SESSION['nama']) ? $_SESSION['nama'] : '';
$user_role = isset($_SESSION['peran']) ? $_SESSION['peran'] : '';

?>

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-wrapper scrollbar-inner">
        <div class="sidebar-content">
            <!-- Logo and Brand -->
            <div class="sidebar-brand">
                <a href="<?php echo $user_role === 'admin' ? 'index.php' : 'menuuser.php'; ?>" class="d-flex align-items-center">
                    <img src="img/logosultra.png" alt="Logo" class="sidebar-logo" style="width: 45px; height: 45px; margin-right: 10px;">
                    <span class="sidebar-brand-text">DPRD SULTRA</span>
                </a>
            </div>

            <!-- User Info -->
            <!-- User Info -->
<div class="user-panel pb-3 mb-3">
    <div class="d-flex align-items-center">
        <div class="image">
            <i class="fas fa-user-circle fa-2x text-light"></i>
        </div>
        <div class="info">
            <span class="d-block text-light">
                <?php
                if(isset($_SESSION['nama'])) {
                    echo $_SESSION['nama'];
                } else {
                    echo "User";
                }
                ?>
            </span>
            <small class="text-muted">
                <?php 
                if(isset($_SESSION['peran'])) {
                    echo "Masuk sebagai " . ucfirst($_SESSION['peran']);
                }
                ?>
            </small>
        </div>
    </div>
</div>
            <!-- Sidebar Menu -->
            <ul class="nav nav-primary">
                <?php if($user_role === 'admin'): ?>
                <!-- Menu untuk Admin -->
                <li class="nav-item <?php echo $current_page === 'index.php' ? 'active' : ''; ?>">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item <?php echo $current_page === 'manajemenpengguna.php' ? 'active' : ''; ?>">
                    <a href="manajemenpengguna.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>Manajemen Pengguna</p>
                    </a>
                </li>
                <li class="nav-item <?php echo $current_page === 'Hasilanalisisjabatan.php' ? 'active' : ''; ?>">
                    <a href="hasilanalisisjabatan.php" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <p>Analisis Jabatan</p>
                    </a>
                </li>
                <?php else: ?>
                <!-- Menu untuk User -->
                <li class="nav-item <?php echo $current_page === 'menuuser.php' ? 'active' : ''; ?>">
                    <a href="menuuser.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>Dashboard User</p>
                    </a>
                </li>
                <li class="nav-item <?php echo $current_page === 'input_data.php' ? 'active' : ''; ?>">
                    <a href="input_data.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <p>Input Data</p>
                    </a>
                </li>
                <li class="nav-item <?php echo $current_page === 'hasilinputuser.php' ? 'active' : ''; ?>">
                    <a href="hasilinputuser.php" class="nav-link">
                        <i class="fas fa-file-alt"></i>
                        <p>Resume</p>
                    </a>
                </li>
                <?php endif; ?>
                
                <!-- Logout Button untuk semua user -->
                <li class="nav-item mt-3">
                    <a href="logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->