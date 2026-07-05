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

// Busca o pet no banco
$stmt = $conexao->prepare("SELECT * FROM pets WHERE id = :id");
$stmt->execute([':id' => $id]);
$pet = $stmt->fetch(PDO::FETCH_ASSOC);

// Se não encontrou o pet, volta para a lista
if (!$pet) {
    header("Location: /petshop/pets/index.php");
    exit();
}

// Busca as especies p preencher o formulario
$especies = $conexao->query("SELECT * FROM especies ORDER BY especie ASC")->fetchAll(PDO::FETCH_ASSOC);

$erro = '';

// Qd formulario for enviado

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $nascimento = $_POST['nascimento'] ?? '';
    $especie_id = $_POST['especie_id'] ?? '';
    $prontuario = $_POST['prontuario'] ?? '';
    $genero = $_POST['genero'] ?? '';

    if ($nome === '' || $nascimento === '' || $especie_id === '' || $genero === '') {
        $erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $verificaEspecie = $conexao->prepare("SELECT id FROM especies WHERE id = :id");
            $verificaEspecie->execute([':id' => $especie_id]);
            if (!$verificaEspecie->fetch()) {
                $erro = 'Espécie selecionada não existe.';
            } else {
                $stmt = $conexao->prepare("UPDATE pets SET nome = :nome, nascimento = :nascimento, especie_id = :especie_id, prontuario = :prontuario, genero = :genero WHERE id = :id");
                $stmt->execute([
                    ':nome' => $nome,
                    ':nascimento' => $nascimento,
                    ':especie_id' => $especie_id,
                    ':prontuario' => $prontuario,
                    ':genero' => $genero,
                    ':id' => $id
                ]);
                header("Location: /petshop/pets/index.php");
                exit();
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}
?>

<?php require_once '../includes/header.php'; ?>

<h2>Editar Pet</h2>

<?php if ($erro): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<form method="POST" action="">

    <div class="mb-3">
        <label class="form-label">Nome:</label>
        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($pet['nome']) ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Nascimento:</label>
        <input type="date" name="nascimento" class="form-control" value="<?= $pet['nascimento'] ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Espécie:</label>
        <select name="especie_id" class="form-control" required>
            <?php foreach ($especies as $especie): ?>
                <option value="<?= $especie['id'] ?>" <?= $especie['id'] == $pet['especie_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($especie['especie']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Prontuário:</label>
        <textarea name="prontuario" class="form-control" rows="4"><?= htmlspecialchars($pet['prontuario']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Gênero:</label><br>
        <input type="radio" name="genero" value="macho" <?= $pet['genero'] === 'macho' ? 'checked' : '' ?> required> Macho
        <input type="radio" name="genero" value="femea" <?= $pet['genero'] === 'femea' ? 'checked' : '' ?> required> Fêmea
    </div>

    <a href="/petshop/pets/index.php" class="btn btn-secondary">Cancelar</a>
    <button type="submit" class="btn btn-warning">Atualizar</button>

</form>

<?php require_once '../includes/footer.php'; ?>