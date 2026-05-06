<?php

require_once __DIR__ . '/_init.php';

if (usuarioLogado()) {
    redirecionar('dashboard.php');
}

redirecionar('login.php');

