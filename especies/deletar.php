<?php
session_start();
require_once '../includes/auth.php';
verificarLogin();
require_once '../includes/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: /petshop/especies/index.php");
    exit();
}

$stmt = $conexao->prepare("SELECT * FROM especies WHERE id = :id");
$stmt->execute([':id' => $id]);
$especie = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$especie) {
    header("Location: /petshop/especies/index.php");
    exit();
}

try {
    $verificaPets = $conexao->prepare("SELECT COUNT(*) FROM pets WHERE especie_id = :id");
    $verificaPets->execute([':id' => $id]);
    if ($verificaPets->fetchColumn() > 0) {
        header("Location: /petshop/especies/index.php?erro=Não é possível excluir: existem pets vinculados a esta espécie.");
        exit();
    }

    $stmt = $conexao->prepare("DELETE FROM especies WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: /petshop/especies/index.php?sucesso=Espécie excluída com sucesso!");
    exit();
} catch (PDOException $e) {
    die("Erro ao excluir espécie: " . $e->getMessage());
}
