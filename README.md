# Sistema de Busca de Endereços - ViaCEP API

Um sistema web profissional que integra a **API ViaCEP** para buscar dados de endereço a partir de um CEP, preenchendo automaticamente um formulário e salvando os dados em um banco de dados MySQL.

## 📋 Características

✅ **Frontend Responsivo**: HTML5, CSS3 e JavaScript vanilla
✅ **Integração ViaCEP**: Busca automática de endereços
✅ **Backend Robusto**: PHP com prepared statements (proteção SQL Injection)
✅ **Banco de Dados**: MySQL com estrutura otimizada
✅ **Design Moderno**: Seguindo heurísticas de Nielsen
✅ **Acessibilidade**: ARIA labels, dark mode, suporte mobile
✅ **Segurança**: CORS headers, validação de dados, proteção de arquivos sensíveis
✅ **Performance**: Compressão Gzip, browser caching, índices no BD

## 📁 Estrutura do Projeto

```
api-viacep/
├── index.html                 # página principal
├── css/
│   └── style.css             # estilos responsivos
├── js/
│   └── main.js               # lógica do frontend
├── backend/
│   ├── api.php               # API backend
│   ├── config.php            # configuração do banco
│   └── database.sql          # script SQL
├── .htaccess                 # configurações do servidor
└── README.md                 # este arquivo
```

## 🚀 Como Usar

### Ambiente Local (Desenvolvimento)

#### Requisitos
- PHP 7.4+ com MySQLi
- MySQL 5.7+
- Servidor web (Apache/Nginx)
- Navegador moderno

#### Passos de Instalação

1. **Clone ou baixe o projeto**
   ```bash
   # Via git
   git clone <seu-repositorio>
   
   # Ou extraia o ZIP do projeto
   unzip api-viacep.zip
   cd api-viacep
   ```

2. **Crie o banco de dados localmente**
   
   Abra o phpMyAdmin e:
   - Clique em "Novo"
   - Nome do banco: `viacep_db`
   - Charset: `utf8mb4_unicode_ci`
   - Clique em "Criar"
   
   Ou via MySQL CLI:
   ```bash
   mysql -u root -p < backend/database.sql
   ```

3. **Configure o backend**
   
   Edite `backend/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASSWORD', '');
   define('DB_NAME', 'viacep_db');
   ```

4. **Inicie o servidor local**
   
   Com PHP built-in:
   ```bash
   php -S localhost:8000
   ```
   
   Ou use XAMPP/WAMP/LAMP para rodar pelo Apache.

5. **Acesse a aplicação**
   
   Abra no navegador: `http://localhost:8000`

### Deploy no InfinityFree

#### Pré-requisitos
- Conta no [InfinityFree](https://www.infinityfree.com/) (gratuita)
- FTP client (FileZilla, WinSCP, etc.)

#### Passos de Deployment

1. **Crie uma conta no InfinityFree**
   - Acesse [infinityfree.com](https://www.infinityfree.com/)
   - Registre-se e verifique seu email

2. **Crie um banco de dados**
   - Acesse o Painel de Controle
   - Vá para **MySQL Databases**
   - Clique em **Create New Database**
   - Anote as credenciais:
     - **Database Name** (ex: epiz_12345_viacep)
     - **Database User** (ex: epiz_12345_viace)
     - **Database Password** (ex: senha_aleatória)
     - **Database Host** (sempre: sql.infinityfree.com)

3. **Crie o banco de dados no servidor**
   - Vá para **MySQL Management** > **phpmyadmin**
   - Selecione seu banco de dados
   - Abra a aba **SQL**
   - Cole o conteúdo de `backend/database.sql` **REMOVENDO as linhas**:
     ```sql
     CREATE DATABASE IF NOT EXISTS `viacep_db`...
     USE `viacep_db`;
     CREATE USER IF NOT EXISTS...
     GRANT...
     FLUSH PRIVILEGES;
     ```
   - Execute o SQL

4. **Configure o backend para produção**
   - Edite `backend/config.php`
   - Comente a seção LOCAL
   - Descomente a seção INFINITYFREE
   - Substitua pelos dados do seu banco:
     ```php
     define('DB_HOST', 'sql.infinityfree.com');
     define('DB_USER', 'epiz_12345_viace');
     define('DB_PASSWORD', 'sua_senha');
     define('DB_NAME', 'epiz_12345_viacep');
     ```

5. **Faça upload dos arquivos via FTP**
   - Abra seu cliente FTP
   - Conecte-se com:
     - **Host**: ftp.infinityfree.com (ou nome do host)
     - **Usuário**: seu usuário InfinityFree
     - **Senha**: sua senha
   - Navegue até `/htdocs`
   - Faça upload de TODOS os arquivos do projeto
   - Mantenha a estrutura de pastas

6. **Teste a aplicação**
   - Seu site estará em: `seu-nome.infinityfree.app`
   - Acesse e teste com um CEP (ex: 01310100)

#### Verificação de Upload
```
seu-nome.infinityfree.app/
├── index.html           ✓
├── css/style.css       ✓
├── js/main.js          ✓
└── backend/
    ├── api.php         ✓
    ├── config.php      ✓
    └── database.sql    (não é acessível publicamente)
```

## 🔒 Segurança

### Proteções Implementadas

1. **SQL Injection Prevention**
   - Uso de prepared statements
   - Validação de entrada no backend

2. **CORS Protection**
   - Headers CORS configurados
   - Preflight OPTIONS tratado

3. **File Protection** (.htaccess)
   - Bloqueia acesso a `config.php`
   - Bloqueia acesso a `database.sql`
   - Bloqueia acesso a arquivos de configuração

4. **XSS Prevention**
   - JSON encode com UNESCAPED_UNICODE
   - Headers X-Content-Type-Options

5. **Validação**
   - CEP: apenas 8 dígitos
   - Estado: exatamente 2 letras maiúsculas
   - Tamanho máximo de campos

## 🎨 Heurísticas de Nielsen Implementadas

### 1. Visibilidade do Status do Sistema
- ✓ Loader animado durante busca na API
- ✓ Mensagens de status em tempo real
- ✓ Cores indicam estado (azul=info, verde=sucesso, vermelho=erro)

### 2. Compatibilidade Sistema-Mundo Real
- ✓ Linguagem clara e familiar (em português)
- ✓ Nomenclatura conhecida (CEP, logradouro, bairro)
- ✓ Helper text com exemplos

### 3. Controle e Liberdade do Usuário
- ✓ Botão "Limpar Formulário" para reset
- ✓ Botão "Novo Cadastro" após sucesso
- ✓ Campos editáveis

### 4. Prevenção de Erros
- ✓ Validação de CEP em tempo real
- ✓ Mensagens de erro claras
- ✓ Campos obrigatórios indicados com asterisco

### 5. Feedback e Documentação
- ✓ Seção "Como Funciona" com passo a passo
- ✓ Tooltips e helper text
- ✓ Mensagens de sucesso do sistema

### 6. Estética e Design Minimalista
- ✓ Design clean e focado
- ✓ Paleta de cores profissional
- ✓ Espaçamento consistente
- ✓ Tipografia clara

### 7. Acessibilidade
- ✓ ARIA labels para screen readers
- ✓ Suporte a dark mode
- ✓ Respeita `prefers-reduced-motion`
- ✓ Responsivo em mobile (font size 16px Android)
- ✓ Contraste de cores adequado

## 🔧 Configuração Avançada

### Customizar Cores

Edite `css/style.css` nas variáveis CSS:
```css
:root {
    --primary-color: #2563eb;      /* azul primário */
    --success-color: #10b981;      /* verde sucesso */
    --error-color: #ef4444;        /* vermelho erro */
    /* ... mais cores ... */
}
```

### Adicionar Novos Campos

1. Adicione coluna no `backend/database.sql`:
   ```sql
   ALTER TABLE `enderecos` ADD COLUMN `novo_campo` VARCHAR(100);
   ```

2. Adicione no HTML (`index.html`):
   ```html
   <input type="text" id="newField" name="novo_campo">
   ```

3. Adicione no JS (`js/main.js`):
   ```javascript
   formData.append('novo_campo', document.getElementById('newField').value);
   ```

4. Valide no PHP (`backend/api.php`):
   ```php
   'novo_campo' => $_POST['novo_campo'] ?? null
   ```

## 📱 Responsividade

O projeto é 100% responsivo:
- **Desktop** (>1024px): 2 colunas onde aplicável
- **Tablet** (768-1024px): 1 coluna
- **Mobile** (<768px): layout otimizado

Testado em:
- ✓ Chrome Desktop & Mobile
- ✓ Firefox Desktop & Mobile
- ✓ Safari iOS
- ✓ Edge

## 🐛 Troubleshooting

### "Erro de conexão com o banco de dados"
- Verifique as credenciais em `backend/config.php`
- Certifique-se de que o MySQL está rodando
- No InfinityFree, verifique se o banco foi criado

### "CEP não encontrado"
- Verifique se o CEP tem 8 dígitos
- Certifique-se de que é um CEP do Brasil
- Teste em: https://viacep.com.br/

### "Arquivo não encontrado 404"
- Verifique se todos os arquivos foram subidos via FTP
- Confirme o caminho da URL
- Teste com `/index.html` na URL

### "Erro 403 Forbidden"
- Verifique as permissões de arquivo (755 para pastas, 644 para arquivos)
- O `.htaccess` pode estar bloqueando - verifique no painel

### "Lentidão ao buscar CEP"
- É normal (API ViaCEP pode levar 1-2s)
- Verifique sua conexão de internet
- Considere adicionar cache local com localStorage

## 📊 API ViaCEP

A API é pública e não requer autenticação.

**Exemplo de requisição:**
```javascript
fetch('https://viacep.com.br/ws/01310100/json/')
  .then(r => r.json())
  .then(data => console.log(data));
```

**Resposta:**
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

## 📞 Suporte

Para problemas com:
- **ViaCEP**: https://viacep.com.br/
- **InfinityFree**: https://www.infinityfree.net/support
- **PHP/MySQL**: https://www.php.net/docs.php

## 📄 Licença

Este projeto é de código aberto e pode ser usado livremente para fins educacionais e comerciais.

## 👨‍💻 Desenvolvedor

Desenvolvido com ❤️ utilizando:
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Backend**: PHP 7.4+
- **Database**: MySQL com MySQLi
- **API**: ViaCEP

---

**Última atualização**: Abril 2026
**Versão**: 1.0.0
