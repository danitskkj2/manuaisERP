<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

include '../db.php';

// Buscar manuais
$sql_manuais = "SELECT * FROM manuais ORDER BY modulo, nome";
$result_manuais = pg_query($conn, $sql_manuais);
$manuais = pg_fetch_all($result_manuais) ?: [];

// Buscar vídeos
$sql_videos = "SELECT * FROM videos ORDER BY modulo, nome";
$result_videos = pg_query($conn, $sql_videos);
$videos = pg_fetch_all($result_videos) ?: [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <h2>Painel de Administração</h2>
        <a href="logout.php" class="logout-button">Sair</a>
        <hr>

        <!-- Tabela de Manuais -->
        <h3>Manuais</h3>
        <div class="pesquisa">
            <input type="text" id="search-manuais" placeholder="Pesquisar manual..." onkeyup="pesquisar('manuais')">
            <button class="btn" onclick="window.location.href='adicionar_manual.php'">Adicionar Manual</button>
        </div>
        <table class="sortable" id="manuais">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Módulo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($manuais as $manual): ?>
                    <tr>
                        <td><?= $manual['id']; ?></td>
                        <td><?= $manual['nome']; ?></td>
                        <td><?= $manual['modulo']; ?></td>
                        <td>
                            <a href="editar_manual.php?id=<?= $manual['id']; ?>" class="btn edit-button">Editar</a>
                            <a href="excluir_manual.php?id=<?= $manual['id']; ?>" class="btn delete-button" onclick="return confirm('Deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <!-- Tabela de Vídeos -->
        <h3>Vídeos</h3>
        <div class="pesquisa">
            <input type="text" id="search-videos" placeholder="Pesquisar vídeo..." onkeyup="pesquisar('videos')">
            <button class="btn" onclick="window.location.href='adicionar_video.php'">Adicionar Vídeo</button>
        </div>
        <table class="sortable" id="videos">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Módulo</th>
                    <th>Link</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($videos as $video): ?>
                    <tr>
                        <td><?= $video['id']; ?></td>
                        <td><?= $video['nome']; ?></td>
                        <td><?= $video['modulo']; ?></td>
                        <td><a href="<?= $video['link']; ?>" target="_blank">Assistir</a></td>
                        <td>
                            <a href="editar_video.php?id=<?= $video['id']; ?>" class="btn edit-button">Editar</a>
                            <a href="excluir_video.php?id=<?= $video['id']; ?>" class="btn delete-button" onclick="return confirm('Deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Tablesort JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tablesort/5.2.1/tablesort.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tablesort/5.2.1/sorts/tablesort.number.min.js"></script>      

    <script>
        // Ativar ordenação na tabela
        document.querySelectorAll(".sortable").forEach(table => new Tablesort(table));
        
        function pesquisar(tipo) {
            const searchInput = document.getElementById('search-' + tipo).value.toLowerCase();
            const rows = document.querySelectorAll(`#${tipo} tbody tr`);

            rows.forEach(row => {
                const nome = row.cells[1].textContent.toLowerCase();
                if (nome.includes(searchInput)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
