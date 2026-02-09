<?php
require_once __DIR__ . '/../src/autoload.php';
$config = require_once __DIR__ . '/../config/database.php';

echo "Inicializando banco de dados...\n";

try {
    // Conecta sem selecionar o banco para criar se não existir
    $dsn = "mysql:host={$config['host']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbname = $config['dbname'];
    
    // Cria o banco se não existir
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    echo "Banco de dados '$dbname' verificado/criado.\n";

    // Conecta no banco criado
    $dsn = "mysql:host={$config['host']};dbname=$dbname;charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Lê o arquivo SQL
    $sql = file_get_contents(__DIR__ . '/../sql/schema.sql');
    
    // Executa as queries
    $pdo->exec($sql);
    
    echo "Tabelas criadas com sucesso!\n";

} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}
