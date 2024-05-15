<?php
session_start();
session_destroy();
// Redirecționați utilizatorul la pagina de login sau la altă pagină relevantă
header("Location: loginh.php");
exit;
?>
