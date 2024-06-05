<!DOCTYPE html>
<html>

<head>
    <title>Data Mahasiswa</title>
    <style>
        /* Your custom styles for PDF */
    </style>
</head>

<body>
    <h1>Data Mahasiswa</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Tanggal Input</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $item) : ?>
                <tr>
                    <td><?= $item->nim ?></td>
                    <td><?= $item->nama ?></td>
                    <td><?= $item->created_at ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>