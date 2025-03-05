<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin.php"); // Redireciona se já estiver logado
    exit();
}

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = pg_escape_string($conn, $_POST['usuario']);
    $senha = pg_escape_string($conn, $_POST['senha']);
    
    // Verificar as credenciais do administrador
    $sql = "SELECT * FROM admins WHERE usuario = '$usuario'";
    $result = pg_query($conn, $sql);
    
    if (pg_num_rows($result) > 0) {
        $admin = pg_fetch_assoc($result);
        // Verificar se a senha fornecida corresponde ao hash armazenado no banco de dados
        if (crypt($senha, $admin['senha_hash']) === $admin['senha_hash']) {
            $_SESSION['admin'] = $usuario;
            header("Location: admin.php");
            exit();
        } else {
            echo "<p>Usuário ou senha incorretos!</p>";
        }        
    } else {
        echo "<p>Usuário ou senha incorretos!</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Login do Administrador</h2>
        <form action="index.php" method="POST">
            <label for="usuario">Usuário:</label>
            <input type="text" name="usuario" required>
            <br><br>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
            <br><br>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
