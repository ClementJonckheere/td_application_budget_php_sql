<?php
function db_connect()
{
    $db = new PDO(
        'mysql:host=localhost;dbname=budget2022;charset=utf8',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    return $db;
}

function render(string $path, array $params = []) {
    extract($params);
    include __DIR__ . './../views/'.$path;
    include __DIR__ . './../views/template.php';
}