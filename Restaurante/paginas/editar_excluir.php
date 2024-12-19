<?php
require "../php/db_conn.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $preco = $_POST['preco'] ?? null;
    $foto = $_POST['foto'] ?? null;
    $classe_id = $_POST['classe_id'] ?? null;

    if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
        echo "Você não tem permissão para acessar esta página.";
        header('location: ../index.php');
        exit;
    }

    echo '<h1>Gerenciar Item</h1>';
    echo '<form method="POST">';
    echo '<input type="hidden" name="id" value="' . htmlspecialchars($id) . '">';
    echo '<label for="nome">Nome:</label>';
    echo '<input type="text" id="nome" name="nome" value="' . htmlspecialchars($nome) . '" required>';
    echo '<label for="preco">Preço:</label>';
    echo '<input type="number" step="0.01" id="preco" name="preco" value="' . htmlspecialchars($preco) . '" required>';
    echo '<label for="foto">Foto Atual:</label>';

    if ($foto) {
        echo '<img src="' . $foto . '" alt="Foto do Item">';
    } else {
        echo '<p>Sem imagem disponível</p>';
    }

    echo '<label for="nova_foto">Atualizar Foto:</label>';
    echo '<input type="text" id="nova_foto" name="nova_foto" placeholder="../img/foto_ou_pasta.jpg.png" value="' . htmlspecialchars($foto) . '">';

    // Adicionar seleção para alterar classe
    echo '<label for="classe_id">Classe:</label>';
    echo '<select id="classe_id" name="classe_id">';
    $classes = $conn->query("SELECT * FROM classes");
    while ($classe = $classes->fetch_assoc()) {
        $selected = $classe['id'] == $classe_id ? 'selected' : '';
        echo '<option value="' . $classe['id'] . '" ' . $selected . '>' . htmlspecialchars($classe['classe']) . '</option>';
    }
    echo '</select>';

    echo '<div class="button-container">';
    echo '<button type="submit" name="editar" class="edit">Salvar Alterações</button>';
    echo '<button type="submit" name="excluir" class="delete">Excluir Item</button>';
    echo '</div>';
    echo '</form>';

    if (isset($_POST['editar'])) {
        $novaFoto = $_POST['nova_foto'] ?: $foto;

        // Atualizar item na tabela comidas
        $sql = "UPDATE comidas SET nome = ?, preco = ?, foto = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $nome, $preco, $novaFoto, $id);

        if ($stmt->execute()) {
            // Atualizar classe do item
            $sqlClasse = "UPDATE comidas_classes SET classes_id = ? WHERE comidas_id = ?";
            $stmtClasse = $conn->prepare($sqlClasse);
            $stmtClasse->bind_param("ii", $classe_id, $id);
            $stmtClasse->execute();

            header('location: cardapio.php');
        } else {
            echo "Erro ao atualizar o item.";
        }
    } elseif (isset($_POST['excluir'])) {
        // Excluir as referências na tabela comidas_classes
        $sqlDeleteClasses = "DELETE FROM comidas_classes WHERE comidas_id = ?";
        $stmtClasses = $conn->prepare($sqlDeleteClasses);
        $stmtClasses->bind_param("i", $id);

        if ($stmtClasses->execute()) {
            // Após excluir as referências, excluir o item na tabela comidas
            $sqlDeleteComidas = "DELETE FROM comidas WHERE id = ?";
            $stmtComidas = $conn->prepare($sqlDeleteComidas);
            $stmtComidas->bind_param("i", $id);

            if ($stmtComidas->execute()) {
                header('location: cardapio.php');
            } else {
                echo "Erro ao excluir o item.";
            }
        } else {
            echo "Erro ao excluir as referências do item.";
        }
    }
}
?>
<link rel="stylesheet" href="../css/editar_excluir.css">
