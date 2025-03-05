<?php
include 'db.php';

$sql_manuais = "SELECT nome, modulo, arquivo, 'manual' AS tipo FROM manuais";
$sql_videos = "SELECT nome, modulo, link AS arquivo, 'video' AS tipo FROM videos";

$sql = "$sql_manuais UNION $sql_videos ORDER BY modulo, nome";
$result = pg_query($conn, $sql);

if (!$result) {
    echo "Erro na consulta ao banco de dados.";
    exit;
}

$itens = [];
while ($row = pg_fetch_assoc($result)) {
    $itens[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
    <title>Manual do Usuário - ERP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Manual do Usuário - ERP</h1>
        <input type="text" id="search" placeholder="Pesquisar conteúdo..." onkeyup="pesquisar()">
    </header>
    
    <main>
        <div class="resultado"></div>
        <section class="setores">
            <h2>Módulos:</h2>
            <ul>
                <?php
                $modulo_atual = "";
                foreach ($itens as $item) {
                    if ($item['modulo'] !== $modulo_atual) {
                        if ($modulo_atual !== "") {
                            echo "</ul></li>";
                        }
                        $modulo_atual = $item['modulo'];
                        echo "<li onclick='mudaSublist(this)'>";
                        echo "<img src='assets/img/seta.png' class='seta'> <strong>{$modulo_atual}</strong>";
                        echo "<ul class='sub-lista'>";
                    }
                    
                    $icone = ($item['tipo'] === 'manual') ? 'doc.webp' : 'video.png';
                    $target = ($item['tipo'] === 'video') ? "target='_blank'" : "";
                    $arquivo = ($item['tipo'] === 'manual') ? "manuais/{$item['arquivo']}" : $item['arquivo'];
                    echo "<li><img src='assets/img/$icone' id='doc'> <a href='$arquivo' $target>{$item['nome']}</a></li>";
                }
                if (!empty($itens)) {
                    echo "</ul></li>";
                }
                if (!empty($itens)) {
                    echo "</ul></li>";
                }
                ?>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; Cotriel - 2025</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
