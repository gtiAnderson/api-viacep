-- Para InfinityFree: O banco já é criado automaticamente
-- Para ambiente local: Execute este comando


CREATE DATABASE IF NOT EXISTS `viacep_db` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `viacep_db`;

CREATE TABLE IF NOT EXISTS `enderecos` (
    -- Campos principais
    `id` INT AUTO_INCREMENT PRIMARY KEY COMMENT 'ID único do registro',
    `cep` VARCHAR(8) NOT NULL COMMENT 'CEP (8 dígitos)',
    `logradouro` VARCHAR(200) NOT NULL COMMENT 'Nome da rua/avenida',
    `numero` VARCHAR(10) NOT NULL COMMENT 'Número do endereço',
    `complemento` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Complemento (apto, sala, etc)',
    `bairro` VARCHAR(100) NOT NULL COMMENT 'Bairro',
    `cidade` VARCHAR(100) NOT NULL COMMENT 'Cidade/Município',
    `estado` VARCHAR(2) NOT NULL COMMENT 'Estado (UF)',
    
    -- Controle
    `data_criacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de criação do registro',
    `data_atualizacao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Data da última atualização',
    
    -- Índices para performance
    INDEX `idx_cep` (`cep`) COMMENT 'Índice para busca por CEP',
    INDEX `idx_cidade` (`cidade`) COMMENT 'Índice para busca por cidade',
    INDEX `idx_estado` (`estado`) COMMENT 'Índice para busca por estado',
    INDEX `idx_data_criacao` (`data_criacao`) COMMENT 'Índice para ordenação por data'
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela para armazenar endereços obtidos via ViaCEP';

-- ============================================
-- INSERIR DADOS DE EXEMPLO (Opcional)
-- ============================================
-- Descomente se quiser adicionar alguns registros de teste

/*
INSERT INTO `enderecos` 
    (`cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`) 
VALUES 
    ('01310100', 'Avenida Paulista', '1578', 'Apto 1501', 'Bela Vista', 'São Paulo', 'SP'),
    ('20040020', 'Avenida Rio Branco', '156', NULL, 'Centro', 'Rio de Janeiro', 'RJ'),
    ('30140071', 'Avenida Getúlio Vargas', '1420', NULL, 'Funcionários', 'Belo Horizonte', 'MG');
*/

-- ============================================
-- CRIAR USUÁRIO PARA A APLICAÇÃO (LOCAL)
-- ============================================
-- Para InfinityFree: Use o usuário criado automaticamente
-- Para ambiente local: Descomente abaixo

/*
CREATE USER IF NOT EXISTS 'viacep_user'@'localhost' IDENTIFIED BY 'senha_segura_123';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER ON `viacep_db`.* TO 'viacep_user'@'localhost';
FLUSH PRIVILEGES;
*/

-- ============================================
-- INFORMAÇÕES IMPORTANTES
-- ============================================

/*
PARA USAR NO INFINITYFREE:

1. Acesse https://www.infinityfree.net/
2. Faça login ou crie uma conta
3. Clique em "Access Panel" (Painel de Controle)
4. Navegue até "MySQL Databases" ou "Database"
5. Clique em "Create New Database"
6. Copie as informações de:
   - Database Name
   - Database User
   - Database Password
   - Database Host (geralmente: sql.infinityfree.com)

7. Atualize o arquivo 'backend/config.php' com essas informações:
   - DB_HOST = Host fornecido
   - DB_USER = Usuário fornecido
   - DB_PASSWORD = Senha fornecida
   - DB_NAME = Nome do banco fornecido

8. Use phpmyadmin ou SQL console do InfinityFree para executar este script

9. Modifique apenas as linhas CREATE DATABASE e GRANT PRIVILEGES conforme necessário
*/
