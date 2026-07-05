<?php
session_start();
require_once '../includes/auth.php';
verificarLogin();
require_once '../includes/conexao.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        $erro = 'O nome da espécie é obrigatório.';
    } else {
        try {
            $stmt = $conexao->prepare("INSERT INTO especies (especie) VALUES (:especie)");
            $stmt->execute([':especie' => $nome]);
            header("Location: /petshop/especies/index.php?sucesso=Espécie cadastrada com sucesso!");
            exit();
        } catch (PDOException $e) {
            $erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}
?>
<?php require_once '../includes/header.php'; ?>

<h2 class="mb-4">Nova Espécie</h2>

<?php if ($erro): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Espécie</label>
        <input type="text" class="form-control" id="nome" name="nome"
               value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
    <a href="/petshop/especies/index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../includes/footer.php'; ?>
