<?php
/**
 * API Backend - Recebe dados do formulário e grava no banco de dados
 * 
 * Recebe via POST:
 * - cep: CEP (8 dígitos)
 * - logradouro: Nome da rua/avenida
 * - numero: Número do endereço
 * - complemento: Complemento (opcional)
 * - bairro: Bairro
 * - cidade: Cidade
 * - estado: Estado (UF - 2 caracteres)
 */

require_once 'config.php';
class EnderecoManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * 
     * @return array
     */
    public function salvarEndereco($dados) {
        $validacao = $this->validarDados($dados);
        if (!$validacao['valido']) {
            return [
                'success' => false,
                'message' => $validacao['error']
            ];
        }

        $cep = $dados['cep'];
        $logradouro = $dados['logradouro'];
        $numero = $dados['numero'];
        $complemento = $dados['complemento'] ?? null;
        $bairro = $dados['bairro'];
        $cidade = $dados['cidade'];
        $estado = $dados['estado'];

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO enderecos (cep, logradouro, numero, complemento, bairro, cidade, estado, data_criacao) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
            );

            if (!$stmt) {
                throw new Exception('Erro ao preparar statement: ' . $this->db->getConnection()->error);
            }

            $stmt->bind_param(
                'sssssss',
                $cep,
                $logradouro,
                $numero,
                $complemento,
                $bairro,
                $cidade,
                $estado
            );

            if (!$stmt->execute()) {
                throw new Exception('Erro ao executar: ' . $stmt->error);
            }

            $id = $this->db->lastInsertId();
            $stmt->close();

            return [
                'success' => true,
                'message' => 'Endereço cadastrado com sucesso! ID: ' . $id,
                'data' => [
                    'id' => $id,
                    'cep' => $cep,
                    'logradouro' => $logradouro,
                    'numero' => $numero,
                    'complemento' => $complemento,
                    'bairro' => $bairro,
                    'cidade' => $cidade,
                    'estado' => $estado
                ]
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao salvar endereço: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Valida dados de entrada
     */
    private function validarDados($dados) {
        $camposObrigatorios = ['cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'];
        
        foreach ($camposObrigatorios as $campo) {
            if (empty($dados[$campo])) {
                return [
                    'valido' => false,
                    'error' => "Campo obrigatório faltando: {$campo}"
                ];
            }
        }

        if (!preg_match('/^\d{8}$/', $dados['cep'])) {
            return [
                'valido' => false,
                'error' => 'CEP inválido. Deve conter 8 dígitos.'
            ];
        }

        if (!preg_match('/^[A-Z]{2}$/', $dados['estado'])) {
            return [
                'valido' => false,
                'error' => 'Estado inválido. Use formato de 2 letras maiúsculas (ex: SP).'
            ];
        }

        if (strlen($dados['logradouro']) > 200) {
            return [
                'valido' => false,
                'error' => 'Logradouro muito longo (máximo 200 caracteres).'
            ];
        }

        if (strlen($dados['numero']) > 10) {
            return [
                'valido' => false,
                'error' => 'Número muito longo (máximo 10 caracteres).'
            ];
        }

        if (!empty($dados['complemento']) && strlen($dados['complemento']) > 100) {
            return [
                'valido' => false,
                'error' => 'Complemento muito longo (máximo 100 caracteres).'
            ];
        }

        if (strlen($dados['bairro']) > 100) {
            return [
                'valido' => false,
                'error' => 'Bairro muito longo (máximo 100 caracteres).'
            ];
        }

        if (strlen($dados['cidade']) > 100) {
            return [
                'valido' => false,
                'error' => 'Cidade muito longa (máximo 100 caracteres).'
            ];
        }

        return ['valido' => true];
    }

    /**
     * Obtém um endereço pelo ID
     */
    public function obterEndereco($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM enderecos WHERE id = ?");
            if (!$stmt) {
                throw new Exception('Erro ao preparar statement');
            }

            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $endereco = $result->fetch_assoc();
            $stmt->close();

            if (!$endereco) {
                return [
                    'success' => false,
                    'message' => 'Endereço não encontrado'
                ];
            }

            return [
                'success' => true,
                'data' => $endereco
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao buscar endereço: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lista todos os endereços
     */
    public function listarEnderecos($limite = 100) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM enderecos ORDER BY data_criacao DESC LIMIT ?");
            if (!$stmt) {
                throw new Exception('Erro ao preparar statement');
            }

            $stmt->bind_param('i', $limite);
            $stmt->execute();
            $result = $stmt->get_result();
            $enderecos = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            return [
                'success' => true,
                'data' => $enderecos,
                'total' => count($enderecos)
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao listar endereços: ' . $e->getMessage()
            ];
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método não permitido. Use POST.'
    ]);
    exit;
}

$dados = [
    'cep' => $_POST['cep'] ?? null,
    'logradouro' => $_POST['logradouro'] ?? null,
    'numero' => $_POST['numero'] ?? null,
    'complemento' => $_POST['complemento'] ?? null,
    'bairro' => $_POST['bairro'] ?? null,
    'cidade' => $_POST['cidade'] ?? null,
    'estado' => $_POST['estado'] ?? null
];


$manager = new EnderecoManager();

$acao = $_POST['acao'] ?? 'salvar';

switch ($acao) {
    case 'salvar':
        $resposta = $manager->salvarEndereco($dados);
        break;

    case 'obter':
        $id = $_POST['id'] ?? null;
        $resposta = $manager->obterEndereco($id);
        break;

    case 'listar':
        $resposta = $manager->listarEnderecos();
        break;

    default:
        http_response_code(400);
        $resposta = [
            'success' => false,
            'message' => 'Ação não reconhecida'
        ];
}

http_response_code($resposta['success'] ? 200 : 400);
echo json_encode($resposta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;
