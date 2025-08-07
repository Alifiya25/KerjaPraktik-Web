<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tabel Thomson</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/home.css') ?>">

    <style>
        .scroll-table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
        }

        table {
            min-width: 1000px;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #f8f9fa;
            z-index: 10;
        }
    </style>
</head>
<body>

    <?= $this->include('layouts/header') ?>

    <main class="container py-4">
        <h2 class="mb-4 text-center">Tabel Thomson</h2>
        
        <div class="table-responsive scroll-table-container">
            <table class="table table-striped table-bordered">
                <<thead class="table-primary">
    <tr>
        <?php foreach ($data[0] as $header): ?>
            <th class="text-center"><?= esc($header) ?></th>
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
    </main>

    <?= $this->include('layouts/footer') ?>

</body>
</html>
