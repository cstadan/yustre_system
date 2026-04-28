<?php
// logout.php
// Cierra la sesión del usuario y redirige al login
session_start();
session_destroy();
// Redirigir al login
header('Location: http://u-storage-cs.com/');
exit();