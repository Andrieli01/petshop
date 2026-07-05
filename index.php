<?php
session_start();
require_once 'includes/auth.php';
verificarLogin();
require_once 'includes/header.php';
?>

<div class="text-center">
    <h1 class="mb-3">Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h1>
    <p class="lead">Sistema de Gerenciamento do Pet Shop</p>
    <a href="/petshop/pets/" class="btn btn-primary btn-lg me-2">Gerenciar Pets</a>
    <a href="/petshop/especies/" class="btn btn-success btn-lg">Gerenciar Espécies</a>
</div>

<?php require_once 'includes/footer.php'; ?>
