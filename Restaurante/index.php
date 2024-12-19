<?php
require "php/db_conn.php";
session_start();


if (isset($_POST['logout'])) {
    // Reinicia a sessão
    $_SESSION['admin'] = false;
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (!isset($_SESSION['admin'])) {
    $_SESSION['admin'] = false;
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sotto le Stelle</title>
    <link rel="stylesheet" href="css/inicial.css">
</head>
<body>
<header>
        <img src="img/logo.png" height="100px">
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="paginas/cardapio.php">Cardápio</a></li>
                <li><a href="sobre_nos.html">Sobre nós</a></li>
                <li><a href="contato.html">Contato</a></li>
                <?php
                if ($_SESSION['admin'] == false) {
                    echo '<li><a href="paginas/login.php">Login</a></li>';
                } else {
                    echo '
                        <form method="POST" style="display: inline;">
                            <li><button type="submit" name="logout" style="border: none; text-decoration: none; cursor: pointer;"><a>Sair</a></button></li>
                        </form>';
                }
                ?>
            </ul>
        </nav>
        <a href="html/carrinho.html"><img src="img/carrinho.svg" height="52px"></a>
    </header>

    <img src="img/comida.jpg" height="100%" width="100%">

    <footer>
            <p>&copy; 2024 Sotto Le Stelle. Todos os direitos reservados.</p>
        
            <ul>
                <li><a href="#">Política de privacidade</a></li>
                <li><a href="#">Termos de serviço</a></li>
            </ul>
    </footer>

</body>
</html>

