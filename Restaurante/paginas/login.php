<?php
require "../php/db_conn.php"; // Conexão com o banco de dados
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Consulta no banco para validar login
    $sql = "SELECT * FROM administradores WHERE cpf = ? AND senha = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $cpf, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['admin'] = true;
        $_SESSION['cpf'] = $cpf;
        header("Location: ../index.php"); // Redireciona para a página inicial
        exit;
    } else {
        $error = "CPF ou senha inválidos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" maxlength="14" placeholder="Digite seu CPF" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" maxlength="8" placeholder="Digite sua senha" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
