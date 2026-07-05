<?php
session_start();
require_once '../includes/auth.php';
verificarLogin();
require_once '../includes/conexao.php';

$stmt = $conexao->query("SELECT pets.*, especies.especie FROM pets JOIN especies ON pets.especie_id = especies.id");
$pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once '../includes/header.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Pets</h2>
    <a href="/petshop/pets/criar.php" class="btn btn-success">+ Novo Pet</a>
</div>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Nome</th>
            <th>Espécie</th>
            <th>Nascimento</th>
            <th>Gênero</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php if(empty($pets)): ?>
    <tr>
        <td colspan="6" class="text-center text-muted">Nenhum pet cadastrado.</td>
    </tr>
<?php else: ?>
    <?php foreach($pets as $pet): ?>
        <tr>
            <td><?= htmlspecialchars($pet['id']) ?></td>
            <td><?= htmlspecialchars($pet['nome']) ?></td>
            <td><?= htmlspecialchars($pet['especie']) ?></td>
            <td><?= htmlspecialchars($pet['nascimento']) ?></td>
            <td><?= htmlspecialchars($pet['genero']) ?></td>
            <td>
                <a href="/petshop/pets/editar.php?id=<?= $pet['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="/petshop/pets/deletar.php?id=<?= $pet['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este pet?')">Excluir</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>

    </tbody>
</table>
<?php require_once '../includes/footer.php'; ?>