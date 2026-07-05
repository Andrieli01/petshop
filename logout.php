<?php
session_start();
session_destroy();
header("Location: /petshop/login.php");
exit();
?>