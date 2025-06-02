<?php 

// Nome do servidor
$sName = "localhost";

// Nome de usuário do banco de dados
$uName = "root";

// Senha do banco de dados
$pass = "";

// Nome do banco de dados
$db_name = "chat_app_db";

// Criando conexão com o banco de dados
try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Falha na conexão: " . $e->getMessage();
}
