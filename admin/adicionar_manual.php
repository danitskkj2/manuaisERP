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
    
    // Verificando se o nome e módulo foram preenchidos
    if (empty($nome) || empty($modulo)) {
        echo "❌ Todos os campos são obrigatórios!";
        exit();
    }

    // Verifica se o arquivo foi enviado e se tem um formato válido
    if ($_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $arquivo = $_FILES['arquivo']['name'];
        $extensao = pathinfo($arquivo, PATHINFO_EXTENSION);
        $extensoes_permitidas = ['pdf', 'docx', 'pptx']; // Extensões permitidas

        if (in_array($extensao, $extensoes_permitidas)) {
            // Move o arquivo para o diretório de manuais
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], '../manuais/' . $arquivo)) {
                // Inserção no banco de dados
                $sql = "INSERT INTO manuais (nome, modulo, arquivo) VALUES ('$nome', '$modulo', '$arquivo')";
                if (pg_query($conn, $sql)) {  
                    echo "<p>✅ Novo manual inserido com sucesso!</p>";
                    // Redireciona após sucesso
                    header("Location: admin.php");
                    exit();
                } else {
                    echo "❌ Erro ao inserir no banco de dados: " . pg_last_error($conn);
                }
            } else {
                echo "❌ Erro ao mover o arquivo para o diretório de manuais.";
            }
        } else {
            echo "❌ Tipo de arquivo não permitido. Apenas PDF, DOCX e PPTX são aceitos.";
        }
    } else {
        echo "❌ Erro no envio do arquivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Manual</title>
    <link rel="stylesheet" href="admin_forms.css">
</head>
<body>
    <div class="admin-container">
        <h2>Adicionar Novo Manual</h2>
        <form action="adicionar_manual.php" method="post" enctype="multipart/form-data">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" required><br><br>

            <label for="modulo">Módulo:</label>
            <select name="modulo" id="modulo" required>
                <option value="">Escolha o módulo</option>
                <?php foreach ($modulos as $modulo): ?>
                    <option value="<?php echo $modulo; ?>"><?php echo $modulo; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="arquivo">Arquivo:</label>
            <input type="file" name="arquivo" id="arquivo" required><br><br>

            <button type="submit">Adicionar Manual</button>
        </form>
    </div>
</body>
</html>
