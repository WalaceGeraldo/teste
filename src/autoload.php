<?php

spl_autoload_register(function ($class) {
    // Prefixo do namespace do projeto
    $prefix = 'App\\';

    // Diretório base para o prefixo
    $base_dir = __DIR__ . '/';

    // Verifica se a classe usa o prefixo
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Obtém o nome relativo da classe
    $relative_class = substr($class, $len);

    // Substitui o prefixo namespace com o diretório base, troca separadores de namespace
    // por separadores de diretório e adiciona .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se o arquivo existir, requer ele
    if (file_exists($file)) {
        require $file;
    }
});
