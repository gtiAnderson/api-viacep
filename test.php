<?php
/**
 * SCRIPT DE TESTE LOCAL
 * 
 * Use este arquivo para testar se o backend está funcionando
 * Acesse: http://localhost:8000/test.php
 * 
 * NÃO faça upload para produção!
 */

// Detém se não for ambiente local
if ($_SERVER['HTTP_HOST'] !== 'localhost' && $_SERVER['HTTP_HOST'] !== 'localhost:8000' && $_SERVER['HTTP_HOST'] !== '127.0.0.1:8000') {
    die('This file is only for local testing. Não acesse em produção!');
}

require_once 'backend/config.php';

$db = Database::getInstance();
$message = '';
$status = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'test_connection') {
        try {
            $conn = $db->getConnection();
            if ($conn->ping()) {
                $message = '✓ Conexão com o banco de dados OK!';
                $status = 'success';
            }
        } catch (Exception $e) {
            $message = '✗ Erro na conexão: ' . $e->getMessage();
            $status = 'error';
        }
    }

    if ($acao === 'create_table') {
        try {
            $sql = file_get_contents('backend/database.sql');
            // Remove comentários e linhas de CREATE DATABASE, USE, GRANT, FLUSH
            $sql = preg_replace('/^CREATE DATABASE.*?;$/im', '', $sql);
            $sql = preg_replace('/^USE.*?;$/im', '', $sql);
            $sql = preg_replace('/^CREATE USER.*?;$/im', '', $sql);
            $sql = preg_replace('/^GRANT.*?;$/im', '', $sql);
            $sql = preg_replace('/^FLUSH.*?;$/im', '', $sql);
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

            // Separa em múltiplas queries
            $queries = array_filter(array_map('trim', explode(';', $sql)));

            foreach ($queries as $query) {
                if (!empty($query)) {
                    $db->query($query);
                }
            }

            $message = '✓ Tabela criada ou atualizada com sucesso!';
            $status = 'success';
        } catch (Exception $e) {
            $message = '✗ Erro ao criar tabela: ' . $e->getMessage();
            $status = 'error';
        }
    }

    if ($acao === 'test_insert') {
        try {
            require_once 'backend/api.php';
            $manager = new EnderecoManager();
            
            $dados = [
                'cep' => '01310100',
                'logradouro' => 'Avenida Paulista',
                'numero' => '1578',
                'complemento' => 'Apto 1501',
                'bairro' => 'Bela Vista',
                'cidade' => 'São Paulo',
                'estado' => 'SP'
            ];

            $resultado = $manager->salvarEndereco($dados);
            
            if ($resultado['success']) {
                $message = '✓ Inserção de teste OK! ID: ' . $resultado['data']['id'];
                $status = 'success';
            } else {
                $message = '✗ Erro na inserção: ' . $resultado['message'];
                $status = 'error';
            }
        } catch (Exception $e) {
            $message = '✗ Erro: ' . $e->getMessage();
            $status = 'error';
        }
    }

    if ($acao === 'test_viacep') {
        try {
            $cep = '01310100';
            $response = file_get_contents("https://viacep.com.br/ws/{$cep}/json/");
            $data = json_decode($response, true);

            if ($data && !isset($data['erro'])) {
                $message = '✓ API ViaCEP OK! Retornou: ' . $data['logradouro'];
                $status = 'success';
            } else {
                $message = '✗ API ViaCEP retornou erro';
                $status = 'error';
            }
        } catch (Exception $e) {
            $message = '✗ Erro ao conectar ViaCEP: ' . $e->getMessage();
            $status = 'error';
        }
    }

    if ($acao === 'list_data') {
        try {
            require_once 'backend/api.php';
            $manager = new EnderecoManager();
            $resultado = $manager->listarEnderecos();
            
            if ($resultado['success']) {
                $message = '✓ Total de registros: ' . $resultado['total'];
                $status = 'success';
            } else {
                $message = '✗ Erro ao listar: ' . $resultado['message'];
                $status = 'error';
            }
        } catch (Exception $e) {
            $message = '✗ Erro: ' . $e->getMessage();
            $status = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Local - ViaCEP</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        header h1 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }
        header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }
        .message.success {
            background-color: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        .message.error {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #7f1d1d;
        }
        .message.info {
            background-color: #dbeafe;
            border-color: #2563eb;
            color: #1e40af;
        }
        .tests {
            display: grid;
            gap: 12px;
        }
        form {
            display: FlexContainer;
            gap: 10px;
        }
        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        button:active {
            transform: translateY(0);
        }
        .info-box {
            background-color: #f0f4ff;
            border-left: 4px solid #2563eb;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .info-box strong {
            color: #2563eb;
        }
        code {
            background-color: #f3f4f6;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🧪 Teste Local</h1>
            <p>Sistema ViaCEP com Banco de Dados</p>
        </header>

        <div class="content">
            <?php if ($message): ?>
                <div class="message <?php echo $status; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="tests">
                <form method="POST">
                    <input type="hidden" name="acao" value="test_connection">
                    <button type="submit">✓ Testar Conexão com BD</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="acao" value="test_viacep">
                    <button type="submit">🔗 Testar API ViaCEP</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="acao" value="create_table">
                    <button type="submit">📊 Criar/Atualizar Tabelas</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="acao" value="test_insert">
                    <button type="submit">➕ Testar Inserção</button>
                </form>

                <form method="POST">
                    <input type="hidden" name="acao" value="list_data">
                    <button type="submit">📋 Listar Dados</button>
                </form>
            </div>

            <div class="info-box">
                <strong>📌 Como Usar:</strong><br>
                1. Clique em "Testar Conexão com BD" para verificar se o MySQL está funcionando<br>
                2. Clique em "Testar API ViaCEP" para verificar internet<br>
                3. Clique em "Criar/Atualizar Tabelas" para criar a tabela no banco<br>
                4. Clique em "Testar Inserção" para inserir um registro de teste<br>
                5. Clique em "Listar Dados" para ver todos os registros<br><br>
                
                <strong>📍 Depois:</strong><br>
                Acesse <code>http://localhost:8000</code> para testar o formulário completo!
            </div>
        </div>
    </div>
</body>
</html>
