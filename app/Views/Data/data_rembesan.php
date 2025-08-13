<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Gabungan - PT Indonesia Power</title>

    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/data.css">

    <!-- Export Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
</head>
<body>
    <?= $this->include('layouts/header'); ?>

    <div class="data-container">
        <!-- Header -->
        <div class="table-header">
            <h2 class="table-title">
                <i class="fas fa-table me-2"></i>Data Input Rembesan Bendungan
            </h2>

            <!-- Navigasi Cepat -->
            <div class="btn-group mb-3" role="group" aria-label="Navigasi Tabel">
                <a href=<?= base_url('input-data') ?> class="btn btn-outline-primary">
                    <i class="fas fa-table"></i> Tabel Gabungan
                </a>
                <a href="<?= base_url('data/tabel_thomson') ?>" class="btn btn-outline-success">
                    <i class="fas fa-eye"></i> Lihat Tabel Thomson
                </a>

                <a href="<?= base_url('lihat/tabel_ambang') ?>" class="btn btn-outline-warning">
                <i class="fas fa-ruler"></i> Rumus Ambang Batas
                </a>

            </div>

            <div class="table-controls">
                <div class="input-group" style="max-width: 300px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" placeholder="Cari data..." id="searchInput">
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5><i class="fas fa-filter me-2"></i>Filter Data</h5>
            <div class="filter-group">
                <!-- Tahun -->
                <div class="filter-item">
                    <label for="tahunFilter" class="form-label">Tahun</label>
                    <select id="tahunFilter" class="form-select">
                        <option value="">Semua Tahun</option>
                        <?php 
                        $uniqueYears = array_unique(array_column(array_column($dataGabungan, 'pengukuran'), 'tahun'));
                        foreach ($uniqueYears as $year): ?>
                            <option value="<?= $year ?>"><?= $year ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Bulan -->
                <div class="filter-item">
                    <label for="bulanFilter" class="form-label">Bulan</label>
                    <select id="bulanFilter" class="form-select">
                        <option value="">Semua Bulan</option>
                        <?php 
                        $uniqueMonths = array_unique(array_column(array_column($dataGabungan, 'pengukuran'), 'bulan'));
                        foreach ($uniqueMonths as $month): ?>
                            <option value="<?= $month ?>"><?= $month ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Periode -->
                <div class="filter-item">
                    <label for="periodeFilter" class="form-label">Periode</label>
                    <select id="periodeFilter" class="form-select">
                        <option value="">Semua Periode</option>
                        <?php 
                        $uniquePeriods = array_unique(array_column(array_column($dataGabungan, 'pengukuran'), 'periode'));
                        foreach ($uniquePeriods as $period): ?>
                            <option value="<?= $period ?>"><?= $period ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Reset -->
                <div class="filter-item" style="align-self: flex-end;">
                    <button id="resetFilter" class="btn btn-secondary">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="data-table" id="exportTable">
                <?php
                $srList = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];
                $srColspan = count($srList) * 2;
                $twHeaders = ['A1 {R}', 'A1 {L}', 'B1', 'B3', 'B5'];
                ?>

                <thead>
                    <!-- Row 1 -->
                    <tr>
                        <th rowspan="3">Tahun</th>
                        <th rowspan="3">Bulan</th>
                        <th rowspan="3">Periode</th>
                        <th rowspan="3">Tanggal</th>
                        <th rowspan="3">TMA Waduk</th>
                        <th rowspan="3">Curah Hujan</th>
                        <th rowspan="2" colspan="<?= count($twHeaders) ?>" class="section-thomson">Thomson Weir</th>
                        <th colspan="<?= $srColspan ?>" class="section-sr">SR</th>
                        <th colspan="6" rowspan="2" class="section-bocoran">Bocoran Baru</th>
                        <th colspan="5" class="section-bocoran">Perhitungan Q Thompson Weir (Liter/Menit)</th>
                        <th rowspan="2" colspan="<?= count($srList) ?>" class="section-sr">Perhitungan Q SR (Liter/Menit)</th>
                        <th rowspan="2" colspan="<?= count($srList) ?>" class="section-sr"></th>
                    </tr>

                    <!-- Row 2 -->
                    <tr>
                        <?php foreach ($srList as $num): ?>
                            <th colspan="2">SR <?= $num ?></th>
                        <?php endforeach; ?>
                        <th colspan="5">Thomson Weir (mm)</th>
                    </tr>

                    <!-- Row 3 -->
                    <tr>
                        <?php foreach ($twHeaders as $tw): ?>
                            <th><?= $tw ?></th>
                        <?php endforeach; ?>

                        <?php foreach ($srList as $num): ?>
                            <th>Nilai</th>
                            <th>Kode</th>
                        <?php endforeach; ?>

                        <th colspan="2">ELV 624 T1</th>
                        <th colspan="2">ELV 615 T2</th>
                        <th colspan="2">Pipa P1</th>

                        <th>R</th>
                        <th>L</th>
                        <th>B-1</th>
                        <th>B-3</th>
                        <th>B-5</th>
                        
                        <?php foreach ($srList as $num): ?>
                            <th>SR <?= $num ?></th> <!-- ✅ Baris Perhitungan Q SR -->
                        <?php endforeach; ?>
                    
                        <th>Talang 1</th>
                        <th>Talang 2</th>
                        <th>Pipa</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $prevTahun = null;
                    $tahunCounts = [];

                    foreach ($dataGabungan as $data) {
                        $tahun = $data['pengukuran']['tahun'] ?? '-';
                        if (!isset($tahunCounts[$tahun])) {
                            $tahunCounts[$tahun] = 0;
                        }
                        $tahunCounts[$tahun]++;
                    }

                    $tahunRowspans = [];

                    foreach ($dataGabungan as $index => $data):
                        $tahun = $data['pengukuran']['tahun'] ?? '-';
                    ?>
                        <tr>
                            <?php if (!isset($tahunRowspans[$tahun])): ?>
                                <td rowspan="<?= $tahunCounts[$tahun] ?>"><?= $tahun ?></td>
                                <?php $tahunRowspans[$tahun] = true; ?>
                            <?php endif; ?>

                            <td><?= $data['pengukuran']['bulan'] ?? '-' ?></td>
                            <td><?= $data['pengukuran']['periode'] ?? '-' ?></td>
                            <td><?= $data['pengukuran']['tanggal'] ?? '-' ?></td>
                            <td><?= $data['pengukuran']['tma_waduk'] ?? '-' ?></td>
                            <td><?= $data['pengukuran']['curah_hujan'] ?? '-' ?></td>

                            <!-- Thomson -->
                            <td><?= $data['thomson']['a1_r'] ?? '-' ?></td>
                            <td><?= $data['thomson']['a1_l'] ?? '-' ?></td>
                            <td><?= $data['thomson']['b1'] ?? '-' ?></td>
                            <td><?= $data['thomson']['b3'] ?? '-' ?></td>
                            <td><?= $data['thomson']['b5'] ?? '-' ?></td>

                            <!-- SR -->
                            <?php foreach ($srList as $num): ?>
                                <td><?= $data['sr']["sr_{$num}_nilai"] ?? '-' ?></td>
                                <td><?= $data['sr']["sr_{$num}_kode"] ?? '-' ?></td>
                            <?php endforeach; ?>

                            <!-- Bocoran -->
                            <td><?= $data['bocoran']['elv_624_t1'] ?? '-' ?></td>
                            <td><?= $data['bocoran']['elv_624_t1_kode'] ?? '-' ?></td>
                            <td><?= $data['bocoran']['elv_615_t2'] ?? '-' ?></td>
                            <td><?= $data['bocoran']['elv_615_t2_kode'] ?? '-' ?></td>
                            <td><?= $data['bocoran']['pipa_p1'] ?? '-' ?></td>
                            <td><?= $data['bocoran']['pipa_p1_kode'] ?? '-' ?></td>

                            <!-- Tambahan: Data Perhitungan Thomson Weir -->
                            <td><?= $data['perhitungan_thomson']['r'] ?? '-' ?></td>
                            <td><?= $data['perhitungan_thomson']['l'] ?? '-' ?></td>
                            <td><?= $data['perhitungan_thomson']['b1'] ?? '-' ?></td>
                            <td><?= $data['perhitungan_thomson']['b3'] ?? '-' ?></td>
                            <td><?= $data['perhitungan_thomson']['b5'] ?? '-' ?></td>

                            <?php foreach ($srList as $num): ?>
    <td>
        <?php
            $nilaiSR = $data['sr']["sr_{$num}_nilai"] ?? null;
            $kodeSR  = $data['sr']["sr_{$num}_kode"] ?? null;
            $qSR     = perhitunganQ_sr($nilaiSR, $kodeSR);

            echo ($qSR === 0)
                ? '-'
                : number_format($qSR, 6, '.', '');
        ?>
    </td>
<?php endforeach; ?>




                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Export Buttons -->
        <div class="export-buttons">
            <button id="exportExcel" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </button>
            <button id="exportPDF" class="btn btn-primary">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </button>
        </div>
    </div>

    <?= $this->include('layouts/footer'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tahunFilter = document.getElementById('tahunFilter');
            const bulanFilter = document.getElementById('bulanFilter');
            const periodeFilter = document.getElementById('periodeFilter');
            const searchInput = document.getElementById('searchInput');
            const resetFilter = document.getElementById('resetFilter');
            const rows = document.querySelectorAll('tbody tr');

            function applyFilters() {
                const tahunVal = tahunFilter.value.toLowerCase();
                const bulanVal = bulanFilter.value.toLowerCase();
                const periodeVal = periodeFilter.value.toLowerCase();
                const searchVal = searchInput.value.toLowerCase();

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const tahun = cells[0]?.textContent.toLowerCase() || '';
                    const bulan = cells[1]?.textContent.toLowerCase() || '';
                    const periode = cells[2]?.textContent.toLowerCase() || '';
                    const allText = row.textContent.toLowerCase();

                    const match = (
                        (tahunVal === '' || tahun === tahunVal) &&
                        (bulanVal === '' || bulan === bulanVal) &&
                        (periodeVal === '' || periode === periodeVal) &&
                        allText.includes(searchVal)
                    );

                    row.style.display = match ? '' : 'none';
                });
            }

            [tahunFilter, bulanFilter, periodeFilter].forEach(el => el.addEventListener('change', applyFilters));
            searchInput.addEventListener('input', applyFilters);

            resetFilter.addEventListener('click', () => {
                tahunFilter.value = '';
                bulanFilter.value = '';
                periodeFilter.value = '';
                searchInput.value = '';
                applyFilters();
            });

            // Export Excel
            document.getElementById('exportExcel').addEventListener('click', () => {
                const wb = XLSX.utils.table_to_book(document.getElementById('exportTable'), { sheet: "Sheet 1" });
                XLSX.writeFile(wb, 'Data_Gabungan.xlsx');
            });

            // Export PDF
            document.getElementById('exportPDF').addEventListener('click', () => {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({ orientation: 'landscape' });

                doc.setFontSize(16);
                doc.text('Data Gabungan Semua Tabel', 14, 15);
                doc.setFontSize(10);
                doc.text(`Dicetak pada: ${new Date().toLocaleString()}`, 14, 20);

                doc.autoTable({
                    html: '#exportTable',
                    startY: 25,
                    styles: {
                        fontSize: 7,
                        cellPadding: 2,
                        overflow: 'linebreak'
                    },
                    headStyles: {
                        fillColor: [52, 152, 219],
                        textColor: 255
                    }
                });

                doc.save('Data_Gabungan.pdf');
            });
        });
    </script>
</body>
</html>
