#!/usr/bin/env php
<?php
/**
 * GUIA RÁPIDO DE CONFIGURAÇÃO - INFINITYFREE
 * 
 * Siga estes passos para fazer deploy em menos de 5 minutos!
 * 
 * IMPORTANTE: Este é um guia - não execute como script PHP
 * Use como referência passo a passo
 */

echo <<<'GUIA'

╔═══════════════════════════════════════════════════════════════════════════╗
║               GUIA RÁPIDO - DEPLOY INFINITYFREE                          ║
║                   Sistema ViaCEP com Banco de Dados                       ║
╚═══════════════════════════════════════════════════════════════════════════╝

📋 PASSOS PARA FAZER DEPLOY (5-10 MINUTOS)
═══════════════════════════════════════════

PASSO 1: Preparar Conta InfinityFree
───────────────────────────────────
□ Acesse: https://www.infinityfree.net/
□ Clique em "Sign Up" (Registre-se)
□ Preencha: Email, Senha
□ Confirme o email recebido
□ Faça login no painel de controle
□ Você receberá um domínio grátis (ex: seu-nome.infinityfree.app)

PASSO 2: Criar Banco de Dados MySQL
────────────────────────────────────
□ No painel, procure por "MySQL Databases" ou "Database"
□ Clique em "Create New Database"
□ Preencha com um nome (ex: viacep_db)
□ Anote as informações fornecidas:

   ┌─────────────────────────────────────────┐
   │ Database Host:     sql.infinityfree.com │
   │ Database Name:     epiz_12345_viacep_db │
   │ Database User:     epiz_12345_viacep    │
   │ Database Password: sua_senha_aleatória  │
   │ Port (opcional):   3306                 │
   └─────────────────────────────────────────┘

□ Copie essas informações para um lugar seguro!

PASSO 3: Criar Estrutura do Banco (Tabelas)
────────────────────────────────────────────
□ Vá para "MySQL Management" ou clique no "phpmyadmin"
□ Selecione seu banco de dados (lado esquerdo)
□ Clique na aba "SQL"
□ Cole APENAS este SQL (remova CREATE DATABASE e GRANT):

    ▼▼▼ COPIE APENAS O QUE ESTÁ ABAIXO ▼▼▼
    
    CREATE TABLE IF NOT EXISTS `enderecos` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `cep` VARCHAR(8) NOT NULL,
        `logradouro` VARCHAR(200) NOT NULL,
        `numero` VARCHAR(10) NOT NULL,
        `complemento` VARCHAR(100) NULL DEFAULT NULL,
        `bairro` VARCHAR(100) NOT NULL,
        `cidade` VARCHAR(100) NOT NULL,
        `estado` VARCHAR(2) NOT NULL,
        `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `data_atualizacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_cep` (`cep`),
        INDEX `idx_cidade` (`cidade`),
        INDEX `idx_estado` (`estado`),
        INDEX `idx_data_criacao` (`data_criacao`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    ▲▲▲ FIM DO SQL ▲▲▲

□ Clique em "Execute" / "Go"
□ Você debe ver: "Query executed successfully"

PASSO 4: Atualizar Arquivo de Configuração
───────────────────────────────────────────
□ Abra o arquivo: backend/config.php
□ Localize esta seção:

    else {
        // Substitua pelas credenciais fornecidas pelo InfinityFree
        define('DB_HOST', 'sql.infinityfree.com');
        define('DB_USER', 'seu_usuario_do_infinityfree');
        define('DB_PASSWORD', 'sua_senha_do_infinityfree');
        define('DB_NAME', 'seu_banco_de_dados_infinityfree');
        define('DB_PORT', 3306);
    }

□ Substitua pelos valores que você anotou:
  - DB_USER:     epiz_12345_viacep (por exemplo)
  - DB_PASSWORD: sua_senha_aleatória
  - DB_NAME:     epiz_12345_viacep_db (por exemplo)

□ Salve o arquivo

EXEMPLO PREENCHIDO:
    else {
        define('DB_HOST', 'sql.infinityfree.com');
        define('DB_USER', 'epiz_12345_viacep');
        define('DB_PASSWORD', 'MySecure9Pass!');
        define('DB_NAME', 'epiz_12345_viacep_db');
        define('DB_PORT', 3306);
    }

PASSO 5: Fazer Upload via FTP
──────────────────────────────
□ Baixe um cliente FTP (grátis):
  - FileZilla: https://filezilla-project.org/
  - WinSCP: https://winscp.net/eng/download.php

□ Abra o cliente FTP e conecte com:
  - Host (Server):  ftp.infinityfree.com
  - Username:       seu_usuario_infinityfree
  - Password:       sua_senha_infinityfree
  - Port (padrão):  21

□ No FileZilla:
  - Lado esquerdo: selecione a pasta do projeto (api-viacep)
  - Clique direito → Enviar (Send)
  - Conectar → Navegar para /htdocs
  - Fazer upload de TODOS os arquivos

□ Estrutura que deve ficar em /htdocs:
    /htdocs/
    ├── index.html
    ├── css/
    │   └── style.css
    ├── js/
    │   └── main.js
    ├── backend/
    │   ├── api.php
    │   └── config.php
    └── .htaccess

PASSO 6: Testar a Aplicação
────────────────────────────
□ Espere 2-3 minutos pelo propagação DNS
□ Abra: https://seu-nome.infinityfree.app
  (Substitua "seu-nome" pelo seu domínio)

□ Você deve ver:
  ✓ Página carregada com estilo azul/roxo
  ✓ Campo de CEP vazio
  ✓ Outros campos cinzas (desabilitados)

□ Teste com um CEP:
  - Digite: 01310100 (Avenida Paulista, São Paulo)
  - Aguarde 1-2 segundos
  - Veja o logradouro aparecer: "Avenida Paulista"
  - Complete com número: 1578
  - Clique "Enviar"
  - Deve ver: "Endereço cadastrado com sucesso!"

SUCESSO! ✓
═══════════════════════════════════════════

Se tudo funcionou, parabéns! Seu sistema está online!

URL: https://seu-nome.infinityfree.app
Banco: epiz_12345_viacep_db @ sql.infinityfree.com

🔒 SEGURANÇA
────────────
✓ config.php é protegido (.htaccess bloqueia acesso)
✓ Prepared statements protegem contra SQL Injection
✓ Validação de dados no backend
✓ CORS headers configurados


⚠️  SE ENCONTRAR PROBLEMAS
════════════════════════════

ERRO: "Erro de conexão com o banco de dados"
─────────────────────────────────────────────
Solução:
1. Verifique se a tabela foi criada (phpmyadmin → seu banco → enderecos)
2. Confirme as credenciais no config.php
3. Teste a conexão criando arquivo teste.php:
   
   <?php
   $conn = new mysqli('sql.infinityfree.com', 'usa, 'pass', 'db');
   if ($conn->connect_error) {
       echo "ERRO: " . $conn->connect_error;
   } else {
       echo "CONEXÃO OK!";
   }
   ?>
   
   Acesse: https://seu-nome.infinityfree.app/teste.php

ERRO: "Arquivo não encontrado (404)"
─────────────────────────────────────
Solução:
1. Verifique se todos os arquivos foram subidos via FTP
2. Teste URL: https://seu-nome.infinityfree.app/index.html
3. Verifique no FTP se a pasta está em /htdocs/

ERRO: "CEP não encontrado mesmo com CEP válido"
───────────────────────────────────────────────
Solução:
1. Verifique conexão de internet
2. A API ViaCEP às vezes fica lenta
3. Tente outro CEP: 20040020 (Rio de Janeiro)

ERRO: "Forbidden 403"
─────────────────────
Solução:
1. O .htaccess pode estar causando problema
2. Tente renomear .htaccess → .htaccess.old
3. Se funcionar, o problema é o .htaccess
4. Procure por caminho incorreto em RewriteBase


📞 CONTATOS ÚTEIS
═════════════════
• ViaCEP API: https://viacep.com.br/ (teste CEPs aqui)
• InfinityFree Support: https://www.infinityfree.net/support
• FileZilla Help: https://wiki.filezilla-project.org/
• PHP Docs: https://www.php.net/manual/


💾 EXEMPLOS DE CEP PARA TESTAR
═══════════════════════════════
01310100 - Avenida Paulista, São Paulo, SP
20040020 - Avenida Rio Branco, Rio de Janeiro, RJ
30140071 - Avenida Getúlio Vargas, Belo Horizonte, MG
80010100 - Rua XV de Novembro, Curitiba, PR
50030140 - Rua Henrique Dias, Recife, PE


✨ FIM DO GUIA ✨
════════════════

GUIA;
