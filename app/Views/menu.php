<?= $this->include('layouts/header'); ?>

<main class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-primary">Selamat Datang</h1>
        <p class="lead text-muted">Silakan pilih menu untuk mengelola sistem monitoring PLTA Saguling</p>
    </div>

    <div class="row justify-content-center g-4">
        <!-- Menu 1: Input Data Rembesan -->
        <div class="col-12 col-md-5 col-lg-4">
            <a href="<?= base_url('/input-data') ?>" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column align-items-center py-4">
                        <div class="menu-icon bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-droplet-fill display-4 text-primary"></i>
                        </div>
                        <h5 class="mt-3 menu-title">Data Perhitungan Rembesan</h5>
                        <p class="text-muted small mt-2 text-center">
                            Input dan analisis data rembesan bendungan secara real-time.
                        </p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Menu 2: Lihat Grafik -->
        <div class="col-12 col-md-5 col-lg-4">
            <a href="<?= base_url('/grafik-data') ?>" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column align-items-center py-4">
                        <div class="menu-icon bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="bi bi-graph-up-arrow display-4 text-success"></i>
                        </div>
                        <h5 class="mt-3 menu-title">Lihat Grafik Data</h5>
                        <p class="text-muted small mt-2 text-center">
                            Visualisasi data monitoring dalam bentuk grafik dinamis.
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Optional: Info Tambahan -->
    <div class="text-center mt-5">
        <small class="text-muted">
            Sistem Monitoring PLTA Saguling | PT PLN Indonesia Power - Saguling POMU
        </small>
    </div>
</main>

<?= $this->include('layouts/footer'); ?>