<?php

// Configurações do banco de dados (edite com seus dados do phpMyAdmin/HostGator).
$db_host = 'localhost';
$db_name = 'NOME_DO_BANCO';
$db_user = 'USUARIO_DO_BANCO';
$db_pass = 'SENHA_DO_BANCO';

/**
 * Retorna conexão PDO única para a aplicação.
 */
function getPDOConnection(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    global $db_host, $db_name, $db_user, $db_pass;

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db_host, $db_name);

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
    return $pdo;
}

