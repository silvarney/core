<?php
$pdo = new PDO('mysql:host=127.0.0.1;port=3308', 'root', 'root');
$pdo->exec("CREATE DATABASE IF NOT EXISTS reservas_crm");
echo "Database created successfully.\n";
