<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pencatat Hutang Sederhana</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Catatan Hutang</h1>

        <?php
        require 'db.php';
        $id = $name = $amount = $status = '';
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
            <input type="text" name="name" placeholder="Nama" value="<?= $name ?>" required>
            <input type="number" name="amount" placeholder="Jumlah" value="<?= $amount ?>" required>
            <select name="status" required>
                <option value="Belum Lunas" <?= $status == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                <option value="Lunas" <?= $status == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
            </select>
            <?php if ($isEditing): ?>
                <button type="submit" name="edit">Edit</button>
            <?php else: ?>
                <button type="submit" name="add">Tambah</button>
            <?php endif; ?>
        </form>

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
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['status']}</td>
                        <td>
                            <a href='index.php?edit={$row['id']}'>Edit</a>
                            <form action='crud.php' method='POST' style='display:inline;'>
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
