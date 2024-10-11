<?php
require 'db.php';

if (isset($_POST['add'])) {
    $stmt = $db->prepare("INSERT INTO debts (name, amount, status) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['name'], $_POST['amount'], $_POST['status']]);
    header('Location: index.php');
    exit();
}

if (isset($_POST['edit'])) {
    $stmt = $db->prepare("UPDATE debts SET name = ?, amount = ?, status = ? WHERE id = ?");
    $stmt->execute([$_POST['name'], $_POST['amount'], $_POST['status'], $_POST['id']]);
    header('Location: index.php');
    exit();
}

if (isset($_POST['delete'])) {
    $stmt = $db->prepare("DELETE FROM debts WHERE id = ?");
    $stmt->execute([$_POST['id']]);
    header('Location: index.php');
    exit();
}
?>
