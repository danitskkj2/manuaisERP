<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Pega o nome do arquivo para deletar da pasta de manuais
    $sql = "SELECT arquivo FROM manuais WHERE id = $id";
    $result = pg_query($conn, $sql);
    $manual = pg_fetch_assoc($result);

    if ($manual) {
        // Excluir o arquivo
        $arquivoPath = '../manuais/' . $manual['arquivo'];
        if (file_exists($arquivoPath)) {
            unlink($arquivoPath);  // Exclui o arquivo se ele existir
        }

        // Excluir o manual do banco de dados
        $sql = "DELETE FROM manuais WHERE id = $id";
        if (pg_query($conn, $sql)) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Erro ao excluir manual: " . pg_last_error($conn);
        }
    }
}
?>
