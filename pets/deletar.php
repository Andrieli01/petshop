<?php
session_start();
require_once '../includes/auth.php';
verificarLogin();
require_once '../includes/conexao.php';

// Pega o ID da URL e valida
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: /petshop/pets/index.php");
    exit();
}

// Verifica se o pet existe
$stmt = $conexao->prepare("SELECT * FROM pets WHERE id = :id");
$stmt->execute([':id' => $id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pet) {
    header("Location: /petshop/pets/index.php");
    exit();
}

// Exclui o pet
try {
    $stmt = $conexao->prepare("DELETE FROM pets WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header("Location: /petshop/pets/index.php");
    exit();
} catch (PDOException $e) {
    die("Erro ao excluir pet: " . $e->getMessage());
}