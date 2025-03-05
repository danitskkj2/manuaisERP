<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT link FROM videos WHERE id = $id";
    $result = pg_query($conn, $sql);
    $video = pg_fetch_assoc($result);

    if ($video) {
        // Excluir o vídeo do banco de dados
        $sql = "DELETE FROM videos WHERE id = $id";
        if (pg_query($conn, $sql)) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Erro ao excluir vídeo: " . pg_last_error($conn);
        }
    }
}
?>
