<?php
$pdo = new PDO(
    'mysql:dbname=tp_jeu_de_combat;host=localhost', 
    'root',
    '', 
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
