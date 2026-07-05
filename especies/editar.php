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

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');

    if ($nome === '') {
        $erro = 'O nome da espécie é obrigatório.';
    } else {
        try {
            $stmt = $conexao->prepare("UPDATE especies SET especie = :especie WHERE id = :id");
            $stmt->execute([':especie' => $nome, ':id' => $id]);
            header("Location: /petshop/especies/index.php?sucesso=Espécie atualizada com sucesso!");
            exit();
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}
?>
<?php require_once '../includes/header.php'; ?>

<h2 class="mb-4">Editar Espécie</h2>

<?php if ($erro): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="POST" action="">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Espécie</label>
        <input type="text" class="form-control" id="nome" name="nome"
               value="<?= htmlspecialchars($_POST['nome'] ?? $especie['especie']) ?>" required>
    </div>
    <button type="submit" class="btn btn-warning">Atualizar</button>
    <a href="/petshop/especies/index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php require_once '../includes/footer.php'; ?>
