<?php
// Dados para a conexão com o banco de dados
$servidor = 'localhost';
$usuario = 'root';
$senha = 'Denner3103';
$banco = 'Restaurante';

// Gerando a conexão com o banco de dados
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verificando se foi feita a conexão com o banco de dados
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

?>