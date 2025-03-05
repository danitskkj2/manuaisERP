<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include '../db.php';

// Verifica se o id foi passado na URL
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = $_GET['id'];

// Buscar vídeo pelo id
$sql = "SELECT * FROM videos WHERE id = $id";
$result = pg_query($conn, $sql);
$video = pg_fetch_assoc($result);

if (!$video) {
    header("Location: admin.php");
    exit();
}

// Buscar módulos existentes
$sql_modulos = "SELECT DISTINCT modulo FROM manuais";
$result_modulos = pg_query($conn, $sql_modulos);
$modulos = [];
while ($row = pg_fetch_assoc($result_modulos)) {
    $modulos[] = $row['modulo'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = htmlspecialchars($_POST['nome']);
    $modulo = $_POST['modulo'];
    $link = filter_var($_POST['link'], FILTER_SANITIZE_URL);

    // Verificar se o link é válido
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        echo "❌ O link fornecido não é válido.";
        exit();
    }

    // Atualizar no banco de dados
    $sql = "UPDATE videos SET nome = '$nome', modulo = '$modulo', link = '$link' WHERE id = $id";

    if (pg_query($conn, $sql)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Erro ao editar vídeo: " . pg_last_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vídeo</title>
    <link rel="stylesheet" href="admin_forms.css">
</head>
<body>
    <div class="admin-container">
        <h2>Editar Vídeo</h2>
        <form action="editar_video.php?id=<?php echo $video['id']; ?>" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($video['nome']); ?>" required><br><br>

            <label for="modulo">Módulo:</label>
            <select name="modulo" id="modulo" required>
                <option value="">Escolha o módulo</option>
                <?php foreach ($modulos as $modulo): ?>
                    <option value="<?php echo $modulo; ?>" <?php echo ($modulo == $video['modulo']) ? 'selected' : ''; ?>>
                        <?php echo $modulo; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="link">Link do Vídeo:</label>
            <input type="url" name="link" id="link" value="<?php echo htmlspecialchars($video['link']); ?>" required><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
