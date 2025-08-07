<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tabel Ambang Batas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">
</head>
<body>

<?= $this->include('layouts/header') ?>

<main class="container py-4">
    <h2 class="mb-4 text-center">Tabel Ambang Batas</h2>

    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped table-bordered">
            <thead class="table-warning">
                <tr>
                    <?php foreach ($data[0] as $header): ?>
                        <th><?= esc($header) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i < count($data); $i++): ?>
                    <tr>
                        <?php foreach ($data[$i] as $cell): ?>
                            <td><?= esc($cell) ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

    <section id="rumusAmbang" class="mt-5">
        <h3>Rumus Ambang Batas</h3>
        <p>
            Rumus Ambang Batas digunakan untuk menentukan nilai minimum atau maksimum ambang pengukuran.
            Contoh rumus:
        </p>
        <div class="alert alert-info">
            <strong>Rumus:</strong> Ambang = Nilai Ukur / Faktor Koreksi
        </div>
        <p>Silakan sesuaikan dengan standar yang berlaku di laboratorium atau metode analisis yang digunakan.</p>
    </section>
</main>

<?= $this->include('layouts/footer') ?>

</body>
</html>
