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

// Buscar manual pelo id 
$sql = "SELECT * FROM manuais WHERE id = $id";
$result = pg_query($conn, $sql);
$manual = pg_fetch_assoc($result);

if (!$manual) {
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
    $arquivo = $_FILES['arquivo']['name'];

    // Se um novo arquivo for enviado
    if ($arquivo) {
        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], 'manuais/' . $arquivo)) {
            // Evitar SQL Injection utilizando pg_escape_string
            $nome = pg_escape_string($nome);
            $modulo = pg_escape_string($modulo);
            $arquivo = pg_escape_string($arquivo);
            $sql = "UPDATE manuais SET nome = '$nome', modulo = '$modulo', arquivo = '$arquivo' WHERE id = $id";
        } else {
            echo "Erro ao fazer upload do arquivo.";
        }
    } else {
        // Atualizar sem o arquivo
        $sql = "UPDATE manuais SET nome = '$nome', modulo = '$modulo' WHERE id = $id";
    }

    // Executar a query de atualização
    if (pg_query($conn, $sql)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Erro ao editar manual: " . pg_last_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Manual</title>
    <link rel="stylesheet" href="admin_forms.css">
</head>
<body>
    <div class="admin-container">
        <h2>Editar Manual</h2>
        <form action="editar_manual.php?id=<?php echo $manual['id']; ?>" method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($manual['nome']); ?>" required><br><br>

            <label for="modulo">Módulo:</label>
            <select name="modulo" id="modulo" required>
                <option value="">Escolha o módulo</option>
                <?php foreach ($modulos as $modulo): ?>
                    <option value="<?php echo $modulo; ?>" <?php echo ($modulo == $manual['modulo']) ? 'selected' : ''; ?>>
                        <?php echo $modulo; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="arquivo">Arquivo (opcional):</label>
            <input type="file" name="arquivo" id="arquivo"><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
