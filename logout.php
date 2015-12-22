<?php
ob_start();
require_once('../core_nufarm/libs.php');
session_start();

session_destroy();


header('Location: /');

// Si no es redirigido por php....
