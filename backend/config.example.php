<?php
/**
 * EXEMPLO DE CONFIGURAÇÃO LOCAL
 * 
 * Para usar este arquivo:
 * 1. Copie este arquivo: cp config.example.php config.local.php
 * 2. Edite config.local.php com suas credenciais
 * 3. Em backend/config.php, carregue este arquivo se existir
 * 
 * NUNCA faça commit de credenciais reais!
 */

// ============================================
// CONFIGURAÇÃO LOCAL DE EXEMPLO
// ============================================

/* DESCOMENTE E EDITE CONFORME NECESSÁRIO */

/*
// Variáveis de Ambiente (Alternativa com .env)
$env = parse_ini_file('.env', true);

define('DB_HOST', $env['database']['host'] ?? 'localhost');
define('DB_USER', $env['database']['user'] ?? 'root');
define('DB_PASSWORD', $env['database']['password'] ?? '');
define('DB_NAME', $env['database']['name'] ?? 'viacep_db');
define('DB_PORT', $env['database']['port'] ?? 3306);

define('APP_DEBUG', $env['app']['debug'] ?? true);
define('APP_ENV', $env['app']['env'] ?? 'development');
*/

/*
// Ou diretamente nas variáveis:

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'sua_senha_aqui');
define('DB_NAME', 'viacep_db');
define('DB_PORT', 3306);

// Modo debug
define('APP_DEBUG', true);
define('APP_ENV', 'local');
*/

// ============================================
// DOCUMENTAÇÃO DE VARIÁVEIS
// ============================================

/**
 * VARIÁVEIS DISPONÍVEIS:
 * 
 * Database:
 * - DB_HOST         (string)  Endereço do servidor MySQL
 * - DB_USER         (string)  Usuário do MySQL
 * - DB_PASSWORD     (string)  Senha do MySQL
 * - DB_NAME         (string)  Nome do banco de dados
 * - DB_PORT         (int)     Porta (padrão 3306)
 * - DB_CHARSET      (string)  Charset (padrão utf8mb4)
 * 
 * Application:
 * - APP_DEBUG       (bool)    Abilitar mensagens de erro completas
 * - APP_ENV         (string)  'local', 'staging' ou 'production'
 * - APP_TIMEZONE    (string)  Timezone PHP (ex: 'America/Sao_Paulo')
 * 
 * Security:
 * - API_CORS        (bool)    Permitir requisições CORS
 * - API_RATE_LIMIT  (int)     Limite de requisições por minuto
 * 
 * Logging:
 * - LOG_LEVEL       (string)  'debug', 'info', 'warning', 'error'
 * - LOG_FILE        (string)  Caminho do arquivo de log
 */

// ============================================
// EXEMPLO DE .env FILE
// ============================================

/*
[database]
host = localhost
user = root
password = 
name = viacep_db
port = 3306
charset = utf8mb4

[app]
debug = true
env = local
timezone = America/Sao_Paulo

[api]
cors = true
rate_limit = 100

[logging]
level = debug
file = logs/app.log
*/

// ============================================
// COMO USAR EM backend/config.php
// ============================================

/*
// No início do config.php, adicione:

if (file_exists('backend/config.local.php')) {
    require_once 'backend/config.local.php';
} else {
    // Configuração padrão
    define('DB_HOST', 'localhost');
    // ...
}
*/

// ============================================
// AMBIENTE LOCAL TIPICO (XAMPP/WAMP)
// ============================================

/*
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');           // Nenhuma senha por padrão
define('DB_NAME', 'viacep_db');
define('DB_PORT', 3306);

define('APP_DEBUG', true);
define('APP_ENV', 'local');
define('APP_TIMEZONE', 'America/Sao_Paulo');

define('API_CORS', true);
define('API_RATE_LIMIT', 1000);      // Sem limite em local

define('LOG_LEVEL', 'debug');
define('LOG_FILE', 'logs/local.log');
*/

// ============================================
// AMBIENTE INFINITYFREE
// ============================================

/*
define('DB_HOST', 'sql.infinityfree.com');
define('DB_USER', 'epiz_12345_viacep');
define('DB_PASSWORD', 'senha_fornecida_pelo_infinityfree');
define('DB_NAME', 'epiz_12345_viacep_db');
define('DB_PORT', 3306);

define('APP_DEBUG', false);          // Nunca true em produção!
define('APP_ENV', 'production');
define('APP_TIMEZONE', 'America/Sao_Paulo');

define('API_CORS', true);            // Necessário para CORS
define('API_RATE_LIMIT', 100);       // Proteção contra abuso

define('LOG_LEVEL', 'error');        // Apenas erros em produção
define('LOG_FILE', 'logs/production.log');
*/

// ============================================
// TESTING TÍPICO
// ============================================

/*
define('DB_HOST', 'localhost:3307'); // Porta diferente para testes
define('DB_USER', 'test_user');
define('DB_PASSWORD', 'test_pass');
define('DB_NAME', 'viacep_test_db');
define('DB_PORT', 3307);

define('APP_DEBUG', true);
define('APP_ENV', 'testing');

define('API_CORS', false);           // Desabilitar CORS em testes
define('API_RATE_LIMIT', 10000);     // Sem limite

define('LOG_LEVEL', 'debug');
define('LOG_FILE', 'logs/test.log');
*/

?>
