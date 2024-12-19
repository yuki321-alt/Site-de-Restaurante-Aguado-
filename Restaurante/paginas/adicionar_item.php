<?php
require "../php/db_conn.php";
session_start();
if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    header('Location: cardapio.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $preco = $_POST['preco'];
    $classe_id = $_POST['classe_id'];
    $foto = $_FILES['foto'];

    if ($foto && $foto['tmp_name']) {
        $fotoPath = '../img/cardapio/' . basename($foto['name']);
        move_uploaded_file($foto['tmp_name'], $fotoPath);
    } else {
        $fotoPath = '../img/cardapio/default.jpg';
    }

    $sql = "INSERT INTO comidas (nome, preco, foto) VALUES ('$nome', '$preco', '$fotoPath')";
    if ($conn->query($sql)) {
        $comida_id = $conn->insert_id;
        $conn->query("INSERT INTO comidas_classes (comidas_id, classes_id) VALUES ('$comida_id', '$classe_id')");
        header('Location: cardapio.php');
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Item</title>
    <link rel="stylesheet" href="../css/adicionar_item.css">
</head>
<body>
    <header>
        <h1>Adicionar Novo Item</h1>
    </header>
    <form method="POST" enctype="multipart/form-data">
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required>

        <label for="preco">Preço</label>
        <input type="number" step="0.01" id="preco" name="preco" required>

        <label for="classe_id">Categoria</label>
        <select id="classe_id" name="classe_id" required>
            <?php
            $classes = $conn->query("SELECT * FROM classes");
            while ($classe = $classes->fetch_assoc()) {
                echo '<option value="' . $classe['id'] . '">' . htmlspecialchars($classe['classe']) . '</option>';
            }
            ?>
        </select>

        <label for="foto">Foto</label>
        <input type="text" id="foto" name="foto" placeholder="../img/foto_ou_pasta">

        <button type="submit">Adicionar</button>
    </form>
</body>
</html>
