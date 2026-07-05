<?php
session_start();
require_once '../includes/auth.php';
verificarLogin();
require_once '../includes/conexao.php';

$especies = $conexao->query("SELECT * FROM especies ORDER BY especie ASC")->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $nascimento = $_POST['nascimento'] ?? '';
    $especie_id = $_POST['especie_id'] ?? '';
    $prontuario = $_POST['prontuario'] ?? '';
    $genero = $_POST['genero'] ?? '';

    if ($nome === '' || $nascimento === '' || $especie_id === '' || $genero === '') {
        $erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $verificaEspecie = $conexao->prepare("SELECT id FROM especies WHERE id = ?");
            $verificaEspecie->execute([$especie_id]);
            if (!$verificaEspecie->fetch()) {
                $erro = 'Espécie selecionada não existe.';
            } else {
                $inserirPet = $conexao->prepare("INSERT INTO pets (nome, nascimento, especie_id, prontuario, genero) VALUES (?, ?, ?, ?, ?)");
                $inserirPet->execute([$nome, $nascimento, $especie_id, $prontuario, $genero]);
                header("Location: /petshop/pets/index.php");
                exit();
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<h2>Novo Pet</h2>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form action="criar.php" method="POST">

    <div class="mb-3">
        <label class="form-label">Nome:</label>
        <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nascimento:</label>
        <input type="date" name="nascimento" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Espécie:</label>
        <select name="especie_id" class="form-control" required>
            <?php foreach($especies as $especie): ?>
                <option value="<?= $especie['id'] ?>"><?= htmlspecialchars($especie['especie']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Prontuário:</label>
        <textarea name="prontuario" class="form-control" rows="4"></textarea>
    </div>

   <div class="mb-3">
    <label class="form-label">Gênero:</label><br>
    <input type="radio" name="genero" value="macho" required> Macho
    <input type="radio" name="genero" value="femea" required> Fêmea
</div>

    <a href="/petshop/pets/index.php" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-success">Salvar</button>

</form>

<?php require_once '../includes/footer.php'; ?>