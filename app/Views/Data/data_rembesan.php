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
            <a href="<?= base_url('input-data') ?>" class="btn btn-outline-primary">
                <i class="fas fa-table"></i> Tabel Gabungan
            </a>
            <a href="<?= base_url('data/tabel_thomson') ?>" class="btn btn-outline-success">
                <i class="fas fa-eye"></i> Lihat Tabel Thomson
            </a>
            <a href="<?= base_url('lihat/tabel_ambang') ?>" class="btn btn-outline-warning">
                <i class="fas fa-ruler"></i> Rumus Ambang Batas
            </a>
        </div>

        <!-- Filter -->
        <div class="table-controls">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Cari data..." id="searchInput">
            </div>
        </div>
    </div>

    <div class="filter-section">
        <h5><i class="fas fa-filter me-2"></i>Filter Data</h5>
        <div class="filter-group">
            <!-- Tahun -->
            <div class="filter-item">
                <label for="tahunFilter" class="form-label">Tahun</label>
                <select id="tahunFilter" class="form-select">
                    <option value="">Semua Tahun</option>
                    <?php
                    if (!empty($pengukuran)):
                        $uniqueYears = array_unique(array_map(fn($p) => $p['tahun'] ?? '-', $pengukuran));
                        foreach ($uniqueYears as $year):
                            if ($year === '-') continue;
                    ?>
                        <option value="<?= esc($year) ?>"><?= esc($year) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <!-- Bulan -->
            <div class="filter-item">
                <label for="bulanFilter" class="form-label">Bulan</label>
                <select id="bulanFilter" class="form-select">
                    <option value="">Semua Bulan</option>
                    <?php
                    if (!empty($pengukuran)):
                        $uniqueMonths = array_unique(array_map(fn($p) => $p['bulan'] ?? '-', $pengukuran));
                        foreach ($uniqueMonths as $month):
                            if ($month === '-') continue;
                    ?>
                        <option value="<?= esc($month) ?>"><?= esc($month) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <!-- Periode -->
            <div class="filter-item">
                <label for="periodeFilter" class="form-label">Periode</label>
                <select id="periodeFilter" class="form-select">
                    <option value="">Semua Periode</option>
                    <?php
                    if (!empty($pengukuran)):
                        $uniquePeriods = array_unique(array_map(fn($p) => $p['periode'] ?? '-', $pengukuran));
                        foreach ($uniquePeriods as $period):
                            if ($period === '-') continue;
                    ?>
                        <option value="<?= esc($period) ?>"><?= esc($period) ?></option>
                    <?php endforeach; endif; ?>
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
            // List SR
            $srList = [1, 40, 66, 68, 70, 79, 81, 83, 85, 92, 94, 96, 98, 100, 102, 104, 106];
            $srColspan = count($srList) * 2;
            $twHeaders = ['A1 {R}', 'A1 {L}', 'B1', 'B3', 'B5'];

            // Index helper
            $indexBy = fn(array $rows) => array_reduce($rows, fn($carry, $item) => isset($item['pengukuran_id']) ? $carry + [$item['pengukuran_id'] => $item] : $carry, []);

            $thomsonBy               = $thomson ? $indexBy($thomson) : [];
            $srBy                    = $sr ? $indexBy($sr) : [];
            $bocoranBy               = $bocoran ? $indexBy($bocoran) : [];
            $perhitunganThomsonBy    = $perhitungan_thomson ? $indexBy($perhitungan_thomson) : [];
            $perhitunganSrBy         = $perhitungan_sr ? $indexBy($perhitungan_sr) : [];
            $perhitunganBocoranBy    = $perhitungan_bocoran ? $indexBy($perhitungan_bocoran) : [];
            $perhitunganIgBy         = $perhitungan_ig ? $indexBy($perhitungan_ig) : [];
            $perhitunganSpillwayBy   = $perhitungan_spillway ? $indexBy($perhitungan_spillway) : [];
            $tebingKananBy           = $tebing_kanan ? $indexBy($tebing_kanan) : [];
            $totalBocoranBy          = $total_bocoran ? $indexBy($total_bocoran) : [];
            $ambangBy                = $ambang ? $indexBy($ambang) : [];
            $perhitunganBatasBy      = $perhitungan_batas ? $indexBy($perhitungan_batas) : []; // ✅ Tambah

            // Format angka
            $fmt = fn($v, $dec = 2) => isset($v) && $v !== '' && $v !== null && $v != 0 ? number_format((float)$v, $dec, '.', '') : '-';

            // Ambil Q SR
            $getSrQ = function($row, $num) {
                if (!$row) return null;
                foreach (["q_sr_$num", "sr_{$num}_q", "sr{$num}_q", "q{$num}", "sr_$num"] as $k) {
                    if (isset($row[$k])) return $row[$k];
                }
                return null;
            };
            ?>

            <thead>
                <tr>
                    <th rowspan="3" class="sticky">Tahun</th>
                    <th rowspan="3" class="sticky-2">Bulan</th>
                    <th rowspan="3" class="sticky-3">Periode</th>
                    <th rowspan="3" class="sticky-4">Tanggal</th>
                    <th rowspan="3" class="sticky-5">TMA Waduk</th>
                    <th rowspan="3" class="sticky-6">Curah Hujan</th>
                    <th rowspan="2" colspan="<?= count($twHeaders) ?>" class="section-thomson">Thomson Weir</th>
                    <th colspan="<?= $srColspan ?>" class="section-sr">SR</th>
                    <th colspan="6" rowspan="2" class="section-bocoran">Bocoran Baru</th>
                    <th colspan="5" class="section-bocoran">Perhitungan Q Thompson Weir (Liter/Menit)</th>
                    <th rowspan="2" colspan="<?= count($srList) ?>" class="section-sr">Perhitungan Q SR (Liter/Menit)</th>
                    <th rowspan="2" colspan="3" class="section-sr">Perhitungan Bocoran Baru</th>
                    <th rowspan="2" colspan="2" class="section-inti">Perhitungan Inti Galery</th>
                    <th rowspan="2" colspan="2" class="section-inti">Perhitungan Bawah Bendungan/Spillway</th>
                    <th rowspan="2" colspan="2" class="section-inti">Perhitungan Tebing Kanan</th>
                    <th rowspan="2" colspan="1" class="section-inti">Perhitungan Tebing Kanan</th>
                    <th rowspan="2" colspan="1" class="section-inti">Total Bocoran</th>
                    <th rowspan="2" colspan="1" class="section-inti">Batasan Maksimal (Tahun)</th>
                </tr>
                <tr>
                    <?php foreach ($srList as $num): ?>
                        <th colspan="2">SR <?= $num ?></th>
                    <?php endforeach; ?>
                    <th colspan="5">Thomson Weir (mm)</th>
                </tr>
                <tr>
                    <?php foreach ($twHeaders as $tw): ?>
                        <th><?= $tw ?></th>
                    <?php endforeach; ?>
                    <?php foreach ($srList as $num): ?>
                        <th>Nilai</th><th>Kode</th>
                    <?php endforeach; ?>
                    <th colspan="2">ELV 624 T1</th>
                    <th colspan="2">ELV 615 T2</th>
                    <th colspan="2">Pipa P1</th>
                    <th>R</th><th>L</th><th>B-1</th><th>B-3</th><th>B-5</th>
                    <?php foreach ($srList as $num): ?><th>SR <?= $num ?></th><?php endforeach; ?>
                    <th>Talang 1</th><th>Talang 2</th><th>Pipa</th>
                    <th>A1</th><th>Ambang</th>
                    <th>B3</th><th>Ambang</th>
                    <th>SR</th><th>Ambang</th>
                    <th>B5</th>
                    <th>R1</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <?php if (!empty($pengukuran)):
                $tahunCounts = [];
                foreach ($pengukuran as $p) $tahunCounts[$p['tahun'] ?? '-'] = ($tahunCounts[$p['tahun'] ?? '-'] ?? 0) + 1;
                $tahunRowspans = [];

                foreach ($pengukuran as $p):
                    $tahun   = $p['tahun'] ?? '-';
                    $bulan   = $p['bulan'] ?? '-';
                    $periode = $p['periode'] ?? '-';
                    $pid     = $p['id'] ?? null;

                    $thom   = $pid ? ($thomsonBy[$pid] ?? []) : [];
                    $srRow  = $pid ? ($srBy[$pid] ?? []) : [];
                    $boco   = $pid ? ($bocoranBy[$pid] ?? []) : [];
                    $pth    = $pid ? ($perhitunganThomsonBy[$pid] ?? []) : [];
                    $psr    = $pid ? ($perhitunganSrBy[$pid] ?? []) : [];
                    $pbb    = $pid ? ($perhitunganBocoranBy[$pid] ?? []) : [];
                    $pig    = $pid ? ($perhitunganIgBy[$pid] ?? []) : [];
                    $psp    = $pid ? ($perhitunganSpillwayBy[$pid] ?? []) : [];
                    $tk     = $pid ? ($tebingKananBy[$pid] ?? []) : [];
                    $tbTot  = $pid ? ($totalBocoranBy[$pid] ?? []) : [];
            ?>
                <tr data-tahun="<?= esc($tahun) ?>" data-bulan="<?= esc($bulan) ?>" data-periode="<?= esc($periode) ?>">
                    <?php if (!isset($tahunRowspans[$tahun])): ?>
                        <td rowspan="<?= $tahunCounts[$tahun] ?>" class="sticky"><?= esc($tahun) ?></td>
                        <?php $tahunRowspans[$tahun] = true; ?>
                    <?php endif; ?>
                    <td class="sticky-2"><?= esc($bulan) ?></td>
                    <td class="sticky-3"><?= esc($periode) ?></td>
                    <td class="sticky-4"><?= esc($p['tanggal'] ?? '-') ?></td>
                    <td class="sticky-5"><?= esc($p['tma_waduk'] ?? '-') ?></td>
                    <td class="sticky-6"><?= esc($p['curah_hujan'] ?? '-') ?></td>

                    <td><?= esc($thom['a1_r'] ?? '-') ?></td>
                    <td><?= esc($thom['a1_l'] ?? '-') ?></td>
                    <td><?= esc($thom['b1'] ?? '-') ?></td>
                    <td><?= esc($thom['b3'] ?? '-') ?></td>
                    <td><?= esc($thom['b5'] ?? '-') ?></td>

                    <?php foreach ($srList as $num): ?>
                        <td><?= esc($srRow["sr_{$num}_nilai"] ?? '-') ?></td>
                        <td><?= esc($srRow["sr_{$num}_kode"] ?? '-') ?></td>
                    <?php endforeach; ?>

                    <td><?= esc($boco['elv_624_t1'] ?? '-') ?></td>
                    <td><?= esc($boco['elv_624_t1_kode'] ?? '-') ?></td>
                    <td><?= esc($boco['elv_615_t2'] ?? '-') ?></td>
                    <td><?= esc($boco['elv_615_t2_kode'] ?? '-') ?></td>
                    <td><?= esc($boco['pipa_p1'] ?? '-') ?></td>
                    <td><?= esc($boco['pipa_p1_kode'] ?? '-') ?></td>

                    <td><?= esc($pth['r'] ?? '-') ?></td>
                    <td><?= esc($pth['l'] ?? '-') ?></td>
                    <td><?= esc($pth['b1'] ?? '-') ?></td>
                    <td><?= esc($pth['b3'] ?? '-') ?></td>
                    <td><?= esc($pth['b5'] ?? '-') ?></td>

                    <?php foreach ($srList as $num): ?>
                        <?php $q = $getSrQ($psr, $num); ?>
                        <td><?= $q === null ? '-' : $fmt($q, 6) ?></td>
                    <?php endforeach; ?>

                    <td><?= $fmt($pbb['talang1'] ?? null, 2) ?></td>
                    <td><?= $fmt($pbb['talang2'] ?? null, 2) ?></td>
                    <td><?= $fmt($pbb['pipa'] ?? null, 2) ?></td>

                    <td><?= $fmt($pig['a1'] ?? null, 2) ?></td>
                    <td><?= $fmt($pig['ambang_a1'] ?? null, 2) ?></td>

                    <td><?= $fmt($psp['B3'] ?? ($psp['b3'] ?? null), 2) ?></td>
                    <td><?= $fmt($psp['ambang'] ?? null, 2) ?></td>

                    <td><?= $fmt($tk['sr'] ?? null, 2) ?></td>
                    <td><?= $fmt($tk['ambang'] ?? null, 2) ?></td>
                    <td><?= esc($tk['B5'] ?? ($tk['b5'] ?? '-')) ?></td>

                    <td><?= $fmt($tbTot['R1'] ?? ($tbTot['r1'] ?? null), 2) ?></td>

                    <td><?= $fmt($perhitunganBatasBy[$pid]['batas_maksimal'] ?? null, 2) ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>

    <div class="export-buttons mt-3">
        <button id="exportExcel" class="btn btn-success"><i class="fas fa-file-excel me-1"></i> Export Excel</button>
        <button id="exportPDF" class="btn btn-primary"><i class="fas fa-file-pdf me-1"></i> Export PDF</button>
    </div>
</div>

<?= $this->include('layouts/footer'); ?>

<!-- Bootstrap & Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tahunFilter = document.getElementById('tahunFilter');
    const bulanFilter = document.getElementById('bulanFilter');
    const periodeFilter = document.getElementById('periodeFilter');
    const resetFilter = document.getElementById('resetFilter');
    const tableBody = document.querySelector('#exportTable tbody');

    function filterTable() {
        const tVal = tahunFilter.value;
        const bVal = bulanFilter.value;
        const pVal = periodeFilter.value;

        tableBody.querySelectorAll('tr').forEach(tr => {
            const match = (!tVal || tr.dataset.tahun === tVal) &&
                          (!bVal || tr.dataset.bulan === bVal) &&
                          (!pVal || tr.dataset.periode === pVal);
            tr.style.display = match ? '' : 'none';
        });
    }

    tahunFilter.addEventListener('change', filterTable);
    bulanFilter.addEventListener('change', filterTable);
    periodeFilter.addEventListener('change', filterTable);
    resetFilter.addEventListener('click', () => {
        tahunFilter.value = '';
        bulanFilter.value = '';
        periodeFilter.value = '';
        filterTable();
    });

    // Export Excel
    document.getElementById('exportExcel').addEventListener('click', () => {
        const wb = XLSX.utils.table_to_book(document.getElementById('exportTable'), { sheet: "Data Rembesan" });
        XLSX.writeFile(wb, 'data_rembesan.xlsx');
    });

    // Export PDF
    document.getElementById('exportPDF').addEventListener('click', () => {
        html2canvas(document.getElementById('exportTable')).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jspdf.jsPDF('l', 'pt', 'a4');
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
            pdf.save("data_rembesan.pdf");
        });
    });
});
</script>

<style>
.data-container { padding: 20px; }
.table-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; }
.table-title { font-size: 1.5rem; margin-bottom: 10px; }
.filter-section { margin-bottom: 15px; }
.filter-group { display: flex; gap: 15px; flex-wrap: wrap; }
.filter-item { display: flex; flex-direction: column; }
.data-table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
.data-table th, .data-table td { border: 1px solid #ddd; padding: 4px 6px; text-align: center; }
.data-table th.sticky { position: sticky; top: 0; background: #f8f9fa; z-index: 3; }
.data-table th.sticky-2 { position: sticky; top: 0; background: #f8f9fa; z-index: 3; }
.data-table th.sticky-3 { position: sticky; top: 0; background: #f8f9fa; z-index: 3; }
.data-table th.sticky-4 { position: sticky; top: 0; background: #f8f9fa; z-index: 3; }
.data-table th.sticky-5 { position: sticky; top: 0; background: #f8f9fa; z-index: 3; }
.data-table th.sticky-6 { position: sticky; top: 0; background: #f8f9fa; z-index: 3; }
.section-thomson { background: #e0f7fa; }
.section-sr { background: #fff3e0; }
.section-bocoran { background: #f1f8e9; }
.section-inti { background: #fce4ec; }
</style>

</body>
</html>
