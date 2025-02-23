<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Struktur Organisasi - DPRD Provinsi Sulawesi Tenggara</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #064e8a;
            --secondary-color: #f0f0f0;
            --accent-color: #ffc107;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--secondary-color);
        }
        
        .sidebar {
            background-color: var(--primary-color);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            padding-top: 20px;
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            margin: 5px 10px;
            border-radius: 5px;
            padding: 8px 15px;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .page-title {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #053e6d;
            border-color: #053e6d;
        }
        
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
        }
        
        .orgchart {
            background-color: var(--light-color);
            padding: 20px;
            border-radius: 10px;
            min-height: 500px;
            position: relative;
        }
        
        .org-node {
            background-color: white;
            border: 2px solid var(--primary-color);
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            display: inline-block;
            position: relative;
            min-width: 150px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .org-node.level-1 {
            background-color: var(--primary-color);
            color: white;
        }
        
        .org-node.level-2 {
            background-color: #0d6efd;
            color: white;
        }
        
        .org-node.level-3 {
            background-color: #198754;
            color: white;
        }
        
        .org-line {
            position: absolute;
            background-color: var(--dark-color);
        }
        
        .org-line.vertical {
            width: 2px;
        }
        
        .org-line.horizontal {
            height: 2px;
        }
        
        .org-node-title {
            font-weight: bold;
            border-bottom: 1px solid rgba(0,0,0,0.1);
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        
        .tab-content {
            background-color: white;
            border-radius: 0 0 10px 10px;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
        }
        
        .nav-tabs .nav-link.active {
            border-color: #dee2e6 #dee2e6 #fff;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .nav-tabs .nav-link {
            color: var(--dark-color);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-primary" style="width: 250px;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <img src="/api/placeholder/40/40" alt="Logo DPRD" class="me-2">
            <span class="fs-4">Admin DPRD</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-users"></i> Manajemen Pengguna
                </a>
            </li>
            <li>
                <a href="#" class="nav-link active">
                    <i class="fas fa-sitemap"></i> Struktur Organisasi
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-user-tie"></i> Manajemen Jabatan
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-tasks"></i> Analisis Beban Kerja
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-award"></i> Manajemen Kompetensi
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-file-alt"></i> Manajemen Dokumen
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-chart-bar"></i> Laporan & Analitik
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-cog"></i> Pengaturan Sistem
                </a>
            </li>
            <li>
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-question-circle"></i> Bantuan
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="/api/placeholder/32/32" alt="Admin" width="32" height="32" class="rounded-circle me-2">
                <strong>Administrator</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="#">Profil</a></li>
                <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Keluar</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="page-title">
            <i class="fas fa-sitemap"></i> Manajemen Struktur Organisasi
        </h2>
        
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs" id="structureTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#table-content" type="button" role="tab" aria-controls="table-content" aria-selected="true">
                    <i class="fas fa-list"></i> Daftar Struktur
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="visual-tab" data-bs-toggle="tab" data-bs-target="#visual-content" type="button" role="tab" aria-controls="visual-content" aria-selected="false">
                    <i class="fas fa-project-diagram"></i> Visualisasi Struktur
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="structureTabsContent">
            <!-- Table View Tab -->
            <div class="tab-pane fade show active" id="table-content" role="tabpanel" aria-labelledby="table-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Daftar Struktur Organisasi DPRD</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStructureModal">
                        <i class="fas fa-plus"></i> Tambah Struktur Baru
                    </button>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="20%">Nama Struktur</th>
                                        <th width="15%">Jenis</th>
                                        <th width="20%">Induk</th>
                                        <th width="25%">Deskripsi</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Pimpinan DPRD</td>
                                        <td>Pimpinan</td>
                                        <td>-</td>
                                        <td>Ketua dan Wakil Ketua DPRD</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Komisi I</td>
                                        <td>Komisi</td>
                                        <td>-</td>
                                        <td>Bidang Pemerintahan</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Komisi II</td>
                                        <td>Komisi</td>
                                        <td>-</td>
                                        <td>Bidang Ekonomi dan Keuangan</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Komisi III</td>
                                        <td>Komisi</td>
                                        <td>-</td>
                                        <td>Bidang Pembangunan</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Badan Anggaran</td>
                                        <td>Badan</td>
                                        <td>-</td>
                                        <td>Membahas dan menyetujui anggaran</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Badan Kehormatan</td>
                                        <td>Badan</td>
                                        <td>-</td>
                                        <td>Menjaga martabat dan kehormatan DPRD</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Badan Legislasi Daerah</td>
                                        <td>Badan</td>
                                        <td>-</td>
                                        <td>Menyusun rancangan program legislasi daerah</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-secondary" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Visual View Tab -->
            <div class="tab-pane fade" id="visual-content" role="tabpanel" aria-labelledby="visual-tab">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Visualisasi Struktur Organisasi DPRD</h5>
                    <div>
                        <button class="btn btn-outline-primary me-2">
                            <i class="fas fa-download"></i> Unduh Gambar
                        </button>
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="orgchart" id="orgChart">
                            <!-- Level 1: Pimpinan -->
                            <div class="org-node level-1" style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%);">
                                <div class="org-node-title">Pimpinan DPRD</div>
                                <div class="org-node-content">
                                    1 Ketua<br>3 Wakil Ketua
                                </div>
                            </div>
                            
                            <!-- Vertical line from Pimpinan -->
                            <div class="org-line vertical" style="top: 95px; left: 50%; height: 30px;"></div>
                            
                            <!-- Horizontal line for level 2 -->
                            <div class="org-line horizontal" style="top: 125px; left: 25%; width: 50%;"></div>
                            
                            <!-- Vertical lines to each level 2 node -->
                            <div class="org-line vertical" style="top: 125px; left: 25%; height: 20px;"></div>
                            <div class="org-line vertical" style="top: 125px; left: 50%; height: 20px;"></div>
                            <div class="org-line vertical" style="top: 125px; left: 75%; height: 20px;"></div>
                            
                            <!-- Level 2: Komisi -->
                            <div class="org-node level-2" style="position: absolute; top: 145px; left: 25%; transform: translateX(-50%);">
                                <div class="org-node-title">Komisi</div>
                                <div class="org-node-content">
                                    3 Komisi<br>45 Anggota
                                </div>
                            </div>
                            
                            <!-- Level 2: Badan -->
                            <div class="org-node level-2" style="position: absolute; top: 145px; left: 50%; transform: translateX(-50%);">
                                <div class="org-node-title">Badan</div>
                                <div class="org-node-content">
                                    3 Badan<br>35 Anggota
                                </div>
                            </div>
                            
                            <!-- Level 2: Fraksi -->
                            <div class="org-node level-2" style="position: absolute; top: 145px; left: 75%; transform: translateX(-50%);">
                                <div class="org-node-title">Fraksi</div>
                                <div class="org-node-content">
                                    6 Fraksi<br>50 Anggota
                                </div>
                            </div>
                            
                            <!-- Vertical line from Komisi -->
                            <div class="org-line vertical" style="top: 220px; left: 25%; height: 30px;"></div>
                            
                            <!-- Horizontal line for Komisi children -->
                            <div class="org-line horizontal" style="top: 250px; left: 10%; width: 30%;"></div>
                            
                            <!-- Vertical lines to each Komisi node -->
                            <div class="org-line vertical" style="top: 250px; left: 10%; height: 20px;"></div>
                            <div class="org-line vertical" style="top: 250px; left: 25%; height: 20px;"></div>
                            <div class="org-line vertical" style="top: 250px; left: 40%; height: 20px;"></div>
                            
                            <!-- Level 3: Komisi children -->
                            <div class="org-node level-3" style="position: absolute; top: 270px; left: 10%; transform: translateX(-50%);">
                                <div class="org-node-title">Komisi I</div>
                                <div class="org-node-content">
                                    Bidang Pemerintahan<br>15 Anggota
                                </div>
                            </div>
                            
                            <div class="org-node level-3" style="position: absolute; top: 270px; left: 25%; transform: translateX(-50%);">
                                <div class="org-node-title">Komisi II</div>
                                <div class="org-node-content">
                                    Bidang Ekonomi & Keuangan<br>15 Anggota
                                </div>
                            </div>
                            
                            <div class="org-node level-3" style="position: absolute; top: 270px; left: 40%; transform: translateX(-50%);">
                                <div class="org-node-title">Komisi III</div>
                                <div class="org-node-content">
                                    Bidang Pembangunan<br>15 Anggota
                                </div>
                            </div>
                            
                            <!-- Vertical line from Badan -->
                            <div class="org-line vertical" style="top: 220px; left: 50%; height: 30px;"></div>
                            
                            <!-- Horizontal line for Badan children -->
                            <div class="org-line horizontal" style="top: 250px; left: 35%; width: 30%;"></div>
                            
                            <!-- Vertical lines to each Badan node -->
                            <div class="org-line vertical" style="top: 250px; left: 65%; height: 20px;"></div>
                            <div class="org-line vertical" style="top: 250px; left: 50%; height: 20px;"></div>
                            <div class="org-line vertical" style="top: 250px; left: 35%; height: 20px;"></div>
                            
                            <!-- Level 3: Badan children -->
                            <div class="org-node level-3" style="position: absolute; top: 370px; left: 35%; transform: translateX(-50%);">
                                <div class="org-node-title">Badan Anggaran</div>
                                <div class="org-node-content">
                                    15 Anggota
                                </div>
                            </div>
                            
                            <div class="org-node level-3" style="position: absolute; top: 370px; left: 50%; transform: translateX(-50%);">
                                <div class="org-node-title">Badan Kehormatan</div>
                                <div class="org-node-content">
                                    10 Anggota
                                </div>
                            </div>
                            
                            <div class="org-node level-3" style="position: absolute; top: 370px; left: 65%; transform: translateX(-50%);">
                                <div class="org-node-title">Badan Legislasi</div>
                                <div class="org-node-content">
                                    10 Anggota
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Structure Modal -->
    <div class="modal fade" id="addStructureModal" tabindex="-1" aria-labelledby="addStructureModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStructureModalLabel">Tambah Struktur Organisasi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStructureForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="structureName" class="form-label">Nama Struktur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="structureName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="structureType" class="form-label">Jenis Struktur <span class="text-danger">*</span></label>
                                <select class="form-select" id="structureType" required>
                                    <option value="">Pilih Jenis...</option>
                                    <option value="Pimpinan">Pimpinan</option>
                                    <option value="Komisi">Komisi</option>
                                    <option value="Badan">Badan</option>
                                    <option value="Fraksi">Fraksi</option>
                                    <option value="Sekretariat">Sekretariat</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="parentStructure" class="form-label">Induk Struktur</label>
                                <select class="form-select" id="parentStructure">
                                    <option value="">Tidak Ada (Struktur Utama)</option>
                                    <option value="1">Pimpinan DPRD</option>
                                    <option value="2">Komisi</option>
                                    <option value="3">Badan</option>
                                    <option value="4">Fraksi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="memberCount" class="form-label">Jumlah Anggota</label>
                                <input type="number" class="form-control" id="memberCount" min="0">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="structureDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="structureDescription" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="structureDuties" class="form-label">Tugas dan Fungsi</label>
                            <textarea class="form-control" id="structureDuties" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3 border-top pt-3">
                            <label class="form-label fw-bold">Informasi Tambahan</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="hasBudget">
                                        <label class="form-check-label" for="hasBudget">
                                            Memiliki Anggaran Tersendiri
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="hasSecretariat">
                                        <label class="form-check-label" for="hasSecretariat">
                                            Memiliki Sekretariat
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="isActive" checked>