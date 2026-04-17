# 📐 DOCUMENTAÇÃO TÉCNICA - Arquitetura e Fluxo

## 🏗️ Arquitetura do Sistema

```
┌─────────────────────────────────────────────────────────────────┐
│                         CLIENTE (Browser)                       │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │  Frontend (HTML/CSS/JavaScript)                          │   │
│  │  - index.html        (Estrutura)                         │   │
│  │  - css/style.css     (Apresentação)                      │   │
│  │  - js/main.js        (Interatividade)                    │   │
│  └──────────────────────────────────────────────────────────┘   │
│                            ↕                                     │
│                    Fetch API (HTTP)                              │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
           ↕                                              ↕
    API ViaCEP                                     Backend PHP
  (https://viacep.com.br)                    (backend/api.php)
           ↕                                              ↕
   JSON Response                                    ┌─────────────┐
   (Endereço encontrado)                           │   Database  │
                                                    │   MySQL     │
                                                    │             │
                                                    │ enderecos   │
                                                    │   table     │
                                                    └─────────────┘
```

## 🔄 Fluxo de Dados

### 1. Busca de CEP (ViaCEP)

```
Usuário digita CEP
    ↓
JavaScript valida (8 dígitos)
    ↓
Debounce (500ms) para evitar múltiplas requisições
    ↓
fetch() para https://viacep.com.br/ws/[CEP]/json/
    ↓
API retorna dados (logradouro, bairro, cidade, uf)
    ↓
Formulário preenchido automaticamente
    ↓
Feedback: "Dados carregados com sucesso!"
```

**Exemplo de requisição:**
```javascript
fetch('https://viacep.com.br/ws/01310100/json/')
  .then(r => r.json())
  .then(data => {
    if (data.erro) {
      // CEP inválido
    } else {
      // Preencher campos
    }
  })
```

**Exemplo de resposta ViaCEP:**
```json
{
  "cep": "01310-100",
  "logradouro": "Avenida Paulista",
  "complemento": "",
  "bairro": "Bela Vista",
  "localidade": "São Paulo",
  "uf": "SP",
  "ibge": "3550308",
  "gia": "",
  "ddd": "11",
  "siafi": "7107"
}
```

### 2. Envio de Dados (Backend)

```
Usuário clica "Enviar"
    ↓
Validação Frontend (campos obrigatórios)
    ↓
FormData com POST para backend/api.php
    ↓
Backend valida dados:
  - CEP: 8 dígitos
  - Estado: 2 letras maiúsculas
  - Tamanho de campos
    ↓
Prepared Statement INSERT
    ↓
Banco de dados recebe dados
    ↓
Retorna JSON com sucesso/erro
    ↓
Frontend mostra mensagem e seção de sucesso
```

**Dados enviados (FormData POST):**
```
cep:       "01310100"
logradouro: "Avenida Paulista"
numero:     "1578"
complemento: "Apto 1501"
bairro:     "Bela Vista"
cidade:     "São Paulo"
estado:     "SP"
```

**Resposta do Backend:**
```json
{
  "success": true,
  "message": "Endereço cadastrado com sucesso! ID: 42",
  "data": {
    "id": 42,
    "cep": "01310100",
    "logradouro": "Avenida Paulista",
    "numero": "1578",
    "complemento": "Apto 1501",
    "bairro": "Bela Vista",
    "cidade": "São Paulo",
    "estado": "SP"
  }
}
```

## 🗄️ Banco de Dados

### Estrutura da Tabela

```sql
CREATE TABLE enderecos (
    id              INT         PRIMARY KEY AUTO_INCREMENT,
    cep             VARCHAR(8)  NOT NULL,
    logradouro      VARCHAR(200) NOT NULL,
    numero          VARCHAR(10) NOT NULL,
    complemento     VARCHAR(100) NULL,
    bairro          VARCHAR(100) NOT NULL,
    cidade          VARCHAR(100) NOT NULL,
    estado          VARCHAR(2)  NOT NULL,
    data_criacao    TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP  DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_cep (cep),
    INDEX idx_cidade (cidade),
    INDEX idx_estado (estado),
    INDEX idx_data_criacao (data_criacao)
)
```

### Índices

- **idx_cep**: Buscar por CEP (frequente)
- **idx_cidade**: Agrupar por cidade (relatórios)
- **idx_estado**: Filtrar por estado
- **idx_data_criacao**: Ordenar cronologicamente

## 🔐 Segurança

### 1. SQL Injection Prevention

**ANTES (Inseguro):**
```php
$sql = "INSERT INTO enderecos VALUES ('$cep', '$cidade', ...)";
$db->query($sql); // ❌ VULNERÁVEL!
```

**DEPOIS (Seguro):**
```php
$stmt = $db->prepare("INSERT INTO enderecos VALUES (?, ?, ...)");
$stmt->bind_param("ss", $cep, $cidade);
$stmt->execute(); // ✓ SEGURO!
```

### 2. CORS Headers

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Content-Type: application/json; charset=utf-8');
```

### 3. Validação Dupla

**Frontend (UX):**
- CEP deve ter 8 dígitos
- Campos obrigatórios
- Feedback instantâneo

**Backend (Segurança):**
- Validação de formato
- Verificação de tamanho
- Prepared statements

### 4. Proteção de Arquivos (.htaccess)

```apache
RewriteRule ^backend/config\.php - [F,L]  # Bloqueia config.php
RewriteRule ^backend/database\.sql - [F,L] # Bloqueia SQL
<FilesMatch "\.(env|log|sql)$">
    Require all denied
</FilesMatch>
```

## 📊 Fluxo de Requisições HTTP

### 1. Requisição ViaCEP (GET)

```
GET /ws/01310100/json/ HTTP/1.1
Host: viacep.com.br
User-Agent: Mozilla/5.0...

HTTP/1.1 200 OK
Content-Type: application/json
{...dados json...}
```

### 2. Requisição Backend (POST)

```
POST /backend/api.php HTTP/1.1
Host: seu-site.com
Content-Type: application/x-www-form-urlencoded
Origin: https://seu-site.com

cep=01310100&logradouro=Avenida+Paulista&...

HTTP/1.1 200 OK
Content-Type: application/json
{"success": true, "message": "...", "data": {...}}
```

## 🎯 Padrões de Design

### 1. Singleton Pattern (Database)

```php
class Database {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
}

// Uso:
$db = Database::getInstance();
$db->query("SELECT * FROM enderecos");
```

**Benefício:** Única instância de conexão com o BD

### 2. MVC (Model-View-Controller)

```
View: index.html (apresentação)
     ↓
Controller: js/main.js (eventos, validação)
     ↓
Model: backend/api.php (lógica de negócio)
     ↓
Database: backend/config.php (dados)
```

### 3. Try-Catch (Error Handling)

```php
try {
    $stmt->execute();
} catch (Exception $e) {
    return [
        'success' => false,
        'message' => 'Erro: ' . $e->getMessage()
    ];
}
```

## 🚀 Performance

### 1. Assets Optimization

**CSS (inline no head):**
- Uma requisição (index.html)
- Compressão Gzip

**JavaScript (defer loading):**
- Script carrega após DOM pronto
- Local.storage para cache

**Imagens:**
- FontAwesome CDN (ícones)
- Otimizadas para web

### 2. Database Performance

**Índices:**
```sql
INDEX idx_cep (cep)         -- Busca rápida
INDEX idx_cidade (cidade)   -- Agrupamento rápido
INDEX idx_data_criacao      -- Ordenação rápida
```

**Prepared Statements:**
- Query compilada uma vez
- Parâmetros seguros
- Melhor performance

### 3. Browser Caching (.htaccess)

```apache
ExpiresByType text/css "access plus 1 month"
ExpiresByType application/javascript "access plus 1 month"
ExpiresByType image/gif "access plus 1 year"
```

### 4. Compression

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE application/json
    # ... comprime tudo
</IfModule>
```

## 🧪 Testes

### Teste Manual

1. **Teste de Caminho Feliz:**
   - Digite CEP válido
   - Confirme preenchimento
   - Digite número
   - Clique Enviar
   - ✓ Sucesso

2. **Teste de Erro:**
   - Digite CEP inválido
   - ✓ Mensagem de erro
   - Digite número inválido
   - ✓ Erro de validação

3. **Teste de Responsividade:**
   - Mobile (375px)
   - Tablet (768px)
   - Desktop (1920px)

### Teste Automático (test.php)

```
http://localhost:8000/test.php

1. Testar Conexão com BD
2. Testar API ViaCEP
3. Criar/Atualizar Tabelas
4. Testar Inserção
5. Listar Dados
```

## 📈 Monitoramento

### Logs Recomendados

```php
// backend/api.php
error_log("INSERT: CEP=$cep, ID=$id", 0, 'logs/inserts.log');
error_log("ERROR: " . $e->getMessage(), 0, 'logs/errors.log');
```

### Métricas

- Requisições por hora
- Taxa de erro
- Tempo de resposta
- CEPs mais buscados

## 🔗 Integrações

### 1. ViaCEP API

- **Endpoint:** https://viacep.com.br/ws/[CEP]/json/
- **Método:** GET
- **Rate Limit:** Não informado (público)
- **CORS:** ✓ Habilitado

### 2. Banco de Dados

- **Host:** sql.infinityfree.com (produção)
- **Port:** 3306
- **Driver:** MySQLi
- **Charset:** utf8mb4

## 📝 Logs e Debug

### Habilitar Debug

```php
// backend/config.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

### Exemplo de Log

```
[2026-04-14 14:23:45] INSERT: CEP=01310100, ID=42, STATUS=OK
[2026-04-14 14:24:12] ERROR: Database connection refused
[2026-04-14 14:25:00] INVALID_CEP: 123, Message=CEP deve ter 8 dígitos
```

---

**Documento Técnico**  
**Data:** Abril 2026  
**Versão:** 1.0.0  
**Autor:** Sistema ViaCEP v1.0
