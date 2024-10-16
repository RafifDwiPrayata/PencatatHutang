<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pencatat Hutang Sederhana</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete() {
            return confirm("Apakah Anda yakin ingin menghapus data ini?");
        }
    </script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: rgb(223, 223, 223);
            background: linear-gradient(90deg, rgba(223, 223, 223, 1) 0%, rgba(78, 176, 231, 1) 50%, rgba(223, 223, 223, 1) 100%);
            color: #ECF0F1;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #ECF0F1;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2C3E50;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input,
        select {
            padding: 10px;
            font-size: 1rem;
            border: 2px solid #3498DB;
            border-radius: 5px;
            outline: none;
        }

        input:focus,
        select:focus {
            border-color: #2980B9;
        }

        button {
            background-color: #3498DB;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980B9;
        }

        button[type="submit"] {
            background-color: #E74C3C;
        }

        button[type="submit"]:hover {
            background-color: #C0392B;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            text-align: center;
            border: 2px solid #3498DB;
            color: #000;
        }

        table th {
            background-color: #3498DB;
            color: #fff;
            font-weight: bold;
        }

        table td form button {
            background-color: #e74c3c;
            padding: 15px;
        }

        table td form button:hover {
            background-color: #c0392b;
        }

        table td a {
            background-color: #3498DB;
            color: #fff;
            padding: 13px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
        }

        table td a:hover {
            background-color: #2b709e;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons form {
            display: inline;
        }

        .total-hutang {
            background-color: #3498DB;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            margin-top: 50px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 2px solid #2980B9;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Catatan Hutang</h1>

        <?php
        require 'db.php';
        $id = '';
        $name = '';
        $amount = '';
        $status = 'Belum Lunas';
        $isEditing = false;

        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            $stmt = $db->prepare("SELECT * FROM debts WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if ($row) {
                $name = $row['name'];
                $amount = $row['amount'];
                $status = $row['status'];
                $isEditing = true;
            }
        }
        ?>

        <form action="crud.php" method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="text" name="name" placeholder="Nama" value="<?= $name ?>" required autofocus>
            <input type="number" name="amount" placeholder="Jumlah" value="<?= $amount ?>" min="0" required>
            <input type="hidden" name="status" value="Belum Lunas">
            <?php if ($isEditing): ?>
                <button type="submit" name="edit">Edit</button>
            <?php else: ?>
                <button type="submit" name="add">Tambah</button>
            <?php endif; ?>
        </form>

        <?php

        $stmt = $db->query("SELECT SUM(amount) AS total_amount FROM debts");
        $totalRow = $stmt->fetch();
        $totalAmount = number_format($totalRow['total_amount'], 0, ',', '.');
        ?>

        <div class="total-hutang">
            <h3>Total Hutang: Rp <?= $totalAmount ?></h3>
        </div>

        <h2>Daftar Hutang</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $db->query("SELECT * FROM debts");
                foreach ($stmt as $row) {
                    $formattedAmount = number_format($row['amount'], 0, ',', '.');
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$formattedAmount}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='index.php?edit={$row['id']}'>Edit</a>
                            <form action='crud.php' method='POST' style='display:inline;' onsubmit='return confirmDelete()'>
                                <input type='hidden' name='id' value='{$row['id']}'>
                                <button type='submit' name='delete'>Hapus</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>