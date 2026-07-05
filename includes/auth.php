<?php

function verificarLogin() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: /petshop/login.php");
        exit();
    }
}