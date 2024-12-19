<?php
require "../php/db_conn.php";
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
    <title>Cardápio</title>
    <link rel="stylesheet" href="../css/cardapio.css">
    <script src="../js/cardapio.js" defer></script>
</head>
<body>
    <header>
        <img src="../img/logo.png" height="100px">
        <nav>
            <ul>
                <li><a href="../index.php">Início</a></li>
                <li><a href="cardapio.php">Cardápio</a></li>
                <li><a href="sobre_nos.php">Sobre nós</a></li>
                <li><a href="contato.php">Contato</a></li>
                <?php
                if ($_SESSION['admin'] == false) {
                    echo '<li><a href="login.php">Login</a></li>';
                } else {
                    echo '
                        <form method="POST" style="display: inline;">
                            <li><button type="submit" name="logout" style="background: none; border: none; color: blue; text-decoration: none; cursor: pointer;"><a>Sair</a></button></li>
                        </form>';
                }
                ?>
                <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
                    <li><a href="adicionar_item.php" class="admin-btn">Adicionar Item</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <a href="carrinho.php"><img src="../img/carrinho.svg" height="52px"></a>
    </header>

    <div class="container">
        <?php
        // Buscar todas as classes (tipos de comida)
        $sql = "SELECT * FROM classes";
        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            foreach ($resultado as $tipo) {
                echo '<div class="classe">';
                echo '<a id="btn-' . strtolower($tipo['classe']) . '" onclick="aparecer(\'' . strtolower($tipo['classe']) . '\')" class="bct">' . htmlspecialchars($tipo['classe']) . '</a>';

                // Buscar comidas relacionadas a essa classe
                $classeId = $tipo['id'];
                $sqlComidas = "SELECT c.* FROM comidas c 
                            INNER JOIN comidas_classes cc ON c.id = cc.comidas_id
                            WHERE cc.classes_id = $classeId";
                $comidasResultado = $conn->query($sqlComidas);

                if ($comidasResultado && $comidasResultado->num_rows > 0) {
                    echo '<div class="itens visu" id="itens-' . strtolower($tipo['classe']) . '">';
                    foreach ($comidasResultado as $comida) {
                        echo '<div class="item">';
                        echo '<img src="' . (isset($comida['foto']) ? $comida['foto'] : '../img/cardapio/default.jpg') . '" width="100%" height="200px">';
                        echo '<div class="info">';
                        echo '<p>' . htmlspecialchars($comida['nome']) . '</p>';
                        echo '<p>R$ ' . number_format($comida['preco'], 2, ',', '.') . '</p>';
                        if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
                            echo '<form action="editar_excluir.php" method="POST">';
                            echo '<input type="hidden" name="classe_id" value="' . htmlspecialchars($tipo['id']) . '">';
                            echo '<input type="hidden" name="id" value="' . htmlspecialchars($comida['id']) . '">';
                            echo '<input type="hidden" name="nome" value="' . htmlspecialchars($comida['nome']) . '">';
                            echo '<input type="hidden" name="preco" value="' . htmlspecialchars($comida['preco']) . '">';
                            echo '<input type="hidden" name="foto" value="' . $comida['foto'] . '">';
                            echo '<button type="submit">Editar/Excluir</button>';
                            echo '</form>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    echo '</div>'; // Fim dos itens
                } else {
                    echo "<p>Não há pratos para essa classe.</p>";
                }

                echo '</div>'; // Fim da div classe
            }
        } else {
            echo "<p>Não há categorias de comidas cadastradas.</p>";
        }
        ?>
    </div>

    <footer>
            <p>&copy; 2024 Sotto Le Stelle. Todos os direitos reservados.</p>
        
            <ul>
                <li><a href="#">Política de privacidade</a></li>
                <li><a href="#">Termos de serviço</a></li>
            </ul>
    </footer>
</body>
</html>
