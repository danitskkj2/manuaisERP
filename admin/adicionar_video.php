<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include '../db.php';

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
    
    if (empty($nome) || empty($modulo) || empty($link)) {
        echo "❌ Todos os campos são obrigatórios!";
        exit();
    }
    
    // Inserir no banco de dados
    $sql = "INSERT INTO videos (nome, modulo, link) VALUES ('$nome', '$modulo', '$link')";
    if (pg_query($conn, $sql)) {
        echo "<p>✅ Novo vídeo inserido com sucesso!</p>";
        header("Location: admin.php");
        exit();
    } else {
        echo "❌ Erro ao inserir no banco de dados: " . pg_last_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Vídeo</title>
    <link rel="stylesheet" href="admin_forms.css">
</head>
<body>
    <div class="admin-container">
        <h2>Adicionar Novo Vídeo</h2>
        <form action="adicionar_video.php" method="post">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required><br><br>

            <label for="modulo">Módulo:</label>
            <select name="modulo" id="modulo" required>
                <option value="">Escolha o módulo</option>
                <?php foreach ($modulos as $modulo): ?>
                    <option value="<?php echo $modulo; ?>"><?php echo $modulo; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="link">Link do Vídeo:</label>
            <input type="url" name="link" id="link" required><br><br>

            <button type="submit">Adicionar Vídeo</button>
        </form>
    </div>
</body>
</html>
