<?php
session_start();
session_destroy(); // Destrói todas as sessões ativas
header("Location: index.php"); // Redireciona para a página inicial
exit();
?>
