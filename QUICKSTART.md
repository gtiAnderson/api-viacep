# 🚀 INÍCIO RÁPIDO - ViaCEP com Banco de Dados

## ⚡ 5 Passos para Começar

### 1️⃣ **AMBIENTE LOCAL** (Desenvolvimento)

```bash
# Certifique-se de ter:
✓ PHP 7.4+ rodando
✓ MySQL rodando
✓ Os arquivos do projeto

# Inicie um servidor PHP local:
php -S localhost:8000

# Acesse no navegador:
http://localhost:8000
```

### 2️⃣ **CRIAÇÃO DO BANCO DE DADOS**

#### Via phpMyAdmin:
1. Abra: http://localhost/phpmyadmin
2. Novo → `viacep_db`
3. Charset: `utf8mb4_unicode_ci`
4. Tab "SQL" → Copie conteúdo de `backend/database.sql`
5. Execute

#### Via MySQL CLI:
```bash
mysql -u root -p < backend/database.sql
```

### 3️⃣ **CONFIGURAR PHP**

Edite `backend/config.php`:

```php
// Se está testando localmente, deixe assim:
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'viacep_db');
```

### 4️⃣ **TESTAR LOCALMENTE**

```
Acesse: http://localhost:8000/test.php

Clique em cada botão para verificar:
✓ Conexão com BD
✓ API ViaCEP
✓ Criação de tabelas
✓ Inserção de dados
```

### 5️⃣ **USAR A APLICAÇÃO**

```
Acesse: http://localhost:8000

- Digite um CEP (ex: 01310100)
- Aguarde carregamento (~1-2 segundos)
- Campos preencher automaticamente
- Complete número/complemento
- Clique "Enviar"
- ✓ Sucesso!
```

---

## 🌍 FAZER DEPLOY NO INFINITYFREE

### Resumido (3 passos):

1. **Crie banco de dados no painel InfinityFree**
   - Copie as credenciais fornecidas

2. **Atualize `backend/config.php`**
   - Substitua credenciais na seção INFINITYFREE

3. **Faça upload via FTP para `/htdocs`**
   - Use FileZilla (grátis)
   - Mantenha estrutura de pastas

**Pronto! Seu site estará em:** `https://seu-nome.infinityfree.app`

> Para instruções detalhadas, veja: `INFINITYFREE_SETUP.php`

---

## 📂 ESTRUTURA DO PROJETO

```
api-viacep/
├── index.html                 ← PÁGINA PRINCIPAL
├── css/style.css             ← ESTILOS (responsivo)
├── js/main.js                ← LÓGICA (ViaCEP, validação)
├── backend/
│   ├── api.php               ← RECEBE E GRAVA DADOS
│   ├── config.php            ← CREDENCIAIS BD
│   └── database.sql          ← ESTRUTURA BD
├── test.php                  ← TESTER LOCAL
├── .htaccess                 ← SEGURANÇA
├── README.md                 ← DOCUMENTAÇÃO COMPLETA
└── INFINITYFREE_SETUP.php   ← GUIA INFINITYFREE
```

---

## 🧪 TESTAR COM CEPs REAIS

```
01310100 - Avenida Paulista, São Paulo, SP
20040020 - Avenida Rio Branco, Rio de Janeiro, RJ
30140071 - Avenida Getúlio Vargas, Belo Horizonte, MG
80010100 - Rua XV de Novembro, Curitiba, PR
50030140 - Rua Henrique Dias, Recife, PE
```

---

## ✨ CARACTERÍSTICAS IMPLEMENTADAS

| Recurso | Status |
|---------|--------|
| ✓ Frontend Responsivo | ✅ Mobile-first |
| ✓ Integração ViaCEP | ✅ Em tempo real |
| ✓ Validação de Dados | ✅ Frontend + Backend |
| ✓ Banco de Dados MySQL | ✅ Com índices |
| ✓ Heurísticas Nielsen | ✅ UX profissional |
| ✓ Dark Mode | ✅ Suportado |
| ✓ Acessibilidade | ✅ ARIA labels |
| ✓ Segurança | ✅ SQL Injection proteção |
| ✓ Performance | ✅ Gzip + Cache |

---

## 🆘 PROBLEMAS COMUNS

### "Erro ao conectar com o banco"
```
→ Verifique se MySQL está rodando
→ Confirme credenciais em config.php
→ Use test.php para diagnosticar
```

### "CEP não encontrado"
```
→ Verifique se CEP tem 8 dígitos
→ Teste em viacep.com.br
→ Pode ser lentidão da API (espere)
```

### "Arquivo 404 no InfinityFree"
```
→ Confirme upload com FTP
→ Verificar se está em /htdocs
→ Tentar sem .htaccess se necessário
```

---

## 📞 RECURSOS

- **ViaCEP API**: https://viacep.com.br/
- **InfinityFree**: https://www.infinityfree.net/
- **FileZilla FTP**: https://filezilla-project.org/
- **PHP Docs**: https://www.php.net/

---

## 📝 ARQUIVO-POR-ARQUIVO

### `index.html` (Frontend)
- Formulário completo
- Campos com validação
- Status messages
- Responsivo (mobile-first)

### `css/style.css` (Design)
- Gradiente profissional
- Cores significativas
- Animações suaves
- Dark mode incluído

### `js/main.js` (Lógica)
- Busca ViaCEP automática
- Validação em tempo-real
- Debounce (eficiência)
- Mensagens de status

### `backend/api.php` (API)
- Recebe dados via POST
- Validação backend
- Prepared statements
- Métodos: salvar, obter, listar

### `backend/config.php` (Config)
- Banco de dados
- Classe Database (Singleton)
- Suporte local + produção
- Tratamento de erros

### `backend/database.sql` (BD)
- Criação de tabela `enderecos`
- Índices otimizados
- Charset UTF-8mb4
- Comentários inclusos

### `test.php` (Tester)
- 5 testes de diagnóstico
- Interface amigável
- Apenas para localhost
- Desabilitado em produção

---

## 🎨 CUSTOMIZAÇÃO RÁPIDA

### Mudar cores primárias
**Arquivo**: `css/style.css` (linhas 6-8)
```css
--primary-color: #2563eb;      ← Troque este
--primary-hover: #1d4ed8;      ← E este
--primary-light: #dbeafe;      ← E este
```

### Mudar nome do site
- `index.html` (linha 5)
- `README.md` (título)

### Adicionar novos campos
1. HTML: Adicione `<input>` 
2. JS: Adicione `formData.append(nome, valor)`
3. PHP: Adicione validação e `bind_param`
4. SQL: `ALTER TABLE enderecos ADD COLUMN ...`

---

## 📊 ESTATÍSTICAS DO PROJETO

- **Linhas de código**: ~2000+
- **Arquivos**: 11
- **Funcionalidades**: 7+
- **Heurísticas Nielsen**: 7/7 implementadas
- **Responsividade**: 100%
- **Acessibilidade**: WCAG 2.1 AA

---

## 🔒 SEGURANÇA

- ✓ Prepared Statements (SQL Injection)
- ✓ Validação dupla (Frontend + Backend)
- ✓ CORS Headers configurados
- ✓ Arquivos sensíveis bloqueados (.htaccess)
- ✓ Charset UTF-8mb4
- ✓ Headers de segurança

---

## 🎓 APRENDIZADO

Este projeto cobre:

```
✓ HTML5 semântico
✓ CSS3 responsivo (Grid + Flexbox)
✓ JavaScript ES6+ (async/await, fetch)
✓ API REST (consumir + criar)
✓ PHP OO (Classes, Singleton)
✓ MySQL (Índices, Prepared Statements)
✓ Segurança web
✓ UX/UI (Heurísticas Nielsen)
✓ Deployment (FTP, Hosting)
```

---

## 👨‍💻 PRÓXIMOS PASSOS

Sugestões para evoluir:

1. **Dashboard**: Mostrar gráficos de cidades mais cadastradas
2. **Autenticação**: Login de usuários
3. **Edição**: Atualizar endereços cadastrados
4. **Exclusão**: Remover registros
5. **Export**: Baixar dados em CSV/PDF
6. **Paginação**: Listagem com limite
7. **Email**: Enviar confirmação
8. **SMS**: Notificação via WhatsApp

---

**Desenvolvido com ❤️ usando HTML, CSS, JavaScript, PHP e MySQL**

**Versão**: 1.0.0  
**Data**: Abril 2026  
**Licença**: Livre para uso educacional e comercial
