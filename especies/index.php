<?php
session_start();
require_once '../includes/auth.php';
verificarLogin();
require_once '../includes/conexao.php';

$stmt = $conexao->query("SELECT * FROM especies ORDER BY especie ASC");
$especies = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once '../includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Espécies</h2>
    <a href="/petshop/especies/criar.php" class="btn btn-success">+ Nova Espécie</a>
</div>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['sucesso']) ?></div>
<?php endif; ?>

<?php if (isset($_GET['erro'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['erro']) ?></div>
<?php endif; ?>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($especies)): ?>
            <tr>
                <td colspan="3" class="text-center text-muted">Nenhuma espécie cadastrada.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($especies as $especie): ?>
                <tr>
                    <td><?= $especie['id'] ?></td>
                    <td><?= htmlspecialchars($especie['especie']) ?></td>
                    <td>
                        <a href="/petshop/especies/editar.php?id=<?= $especie['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="/petshop/especies/deletar.php?id=<?= $especie['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Deseja realmente excluir esta espécie?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>
