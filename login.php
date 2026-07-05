<?php
session_start();
require_once 'includes/conexao.php';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = "Preencha o email e a senha.";
    } else {
        $consulta = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $consulta->execute([$email]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
        if($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header("Location: /petshop/index.php");
            exit();
        } else {
            $erro = "Email ou senha incorretos!";
        }
    }
}

?>

<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <h2 class="mb-4 text-center">Login</h2>
        <form action="login.php" method="POST">
            <?php if(isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
     <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Digite seu email">
            </div>

            <div class="mb-3">
                <label class="form-label">Senha:</label>
                <input type="password" name="senha" class="form-control" placeholder="Digite sua senha">
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>

        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>