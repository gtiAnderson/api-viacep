// ============================================
// ELEMENTOS DO DOM
// ============================================
const cepInput = document.getElementById('cep');
const streetInput = document.getElementById('street');
const numberInput = document.getElementById('number');
const complementInput = document.getElementById('complement');
const neighborhoodInput = document.getElementById('neighborhood');
const cityInput = document.getElementById('city');
const stateInput = document.getElementById('state');
const addressForm = document.getElementById('addressForm');
const statusMessage = document.getElementById('statusMessage');
const submitBtn = document.getElementById('submitBtn');
const resetBtn = document.getElementById('resetBtn');
const cepError = document.getElementById('cepError');
const cepLoader = document.getElementById('cepLoader');
const btnLoader = document.getElementById('btnLoader');
const btnText = document.getElementById('btnText');
const successSection = document.getElementById('successSection');
const successMessage = document.getElementById('successMessage');
const newRegistrationBtn = document.getElementById('newRegistrationBtn');

// ============================================
// CONFIGURAÇÕES
// ============================================
const API_VIACEP_URL = 'https://viacep.com.br/ws';
const BACKEND_URL = 'backend/api.php';
const DEBOUNCE_TIME = 500;

let debounceTimer;
let addressDataLoaded = false;

// ============================================
// FUNÇÕES AUXILIARES
// ============================================

/**
 * Mostra mensagem de status
 * @param {string} message - Mensagem a ser exibida
 * @param {string} type - Tipo: 'success', 'error', 'warning'
 */
function showStatusMessage(message, type = 'info') {
    statusMessage.className = `status-message ${type}`;
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    statusMessage.innerHTML = `
        <i class="${icons[type]}"></i>
        <span>${message}</span>
    `;
    statusMessage.classList.remove('hidden');
    
    // Auto-hide mensagens de sucesso após 5 segundos
    if (type === 'success') {
        setTimeout(() => {
            statusMessage.classList.add('hidden');
        }, 5000);
    }
}

/**
 * Esconde a mensagem de status
 */
function hideStatusMessage() {
    statusMessage.classList.add('hidden');
}

/**
 * Limpa informações do formulário
 */
function clearAddressFields() {
    streetInput.value = '';
    neighborhoodInput.value = '';
    cityInput.value = '';
    stateInput.value = '';
}

/**
 * Habilita/desabilita campos de endereço
 * @param {boolean} disabled - true para desabilitar, false para habilitar
 */
function setAddressFieldsDisabled(disabled) {
    streetInput.disabled = disabled;
    neighborhoodInput.disabled = disabled;
    cityInput.disabled = disabled;
    stateInput.disabled = disabled;
}

/**
 * Valida se o CEP é válido (8 dígitos)
 * @param {string} cep - CEP a ser validado
 * @returns {boolean}
 */
function isValidCEP(cep) {
    return /^\d{8}$/.test(cep);
}

/**
 * Formata o CEP removendo caracteres não numéricos
 * @param {string} cep - CEP a ser formatado
 * @returns {string}
 */
function formatCEP(cep) {
    return cep.replace(/\D/g, '');
}

// ============================================
// BUSCA NA API VIACEP
// ============================================

/**
 * Busca dados na API ViaCEP
 * @param {string} cep - CEP a buscar
 * @returns {Promise}
 */
async function fetchCEPData(cep) {
    try {
        cepLoader.classList.remove('hidden');
        hideStatusMessage();

        const response = await fetch(`${API_VIACEP_URL}/${cep}/json/`);
        const data = await response.json();

        cepLoader.classList.add('hidden');

        // Verifica se o CEP é válido
        if (data.erro) {
            cepError.textContent = 'CEP não encontrado. Verifique e tente novamente.';
            cepError.classList.add('show');
            clearAddressFields();
            setAddressFieldsDisabled(true);
            showStatusMessage('CEP inválido ou não encontrado. Tente outro.', 'error');
            addressDataLoaded = false;
            return false;
        }

        // Preenche os campos com os dados retornados
        preloadAddressFields(data);
        cepError.classList.remove('show');
        showStatusMessage('Dados carregados com sucesso!', 'success');
        addressDataLoaded = true;
        return true;

    } catch (error) {
        console.error('Erro ao buscar CEP:', error);
        cepLoader.classList.add('hidden');
        cepError.textContent = 'Erro ao conectar com a API. Verifique sua conexão e tente novamente.';
        cepError.classList.add('show');
        clearAddressFields();
        setAddressFieldsDisabled(true);
        showStatusMessage('Erro ao buscar dados. Tente novamente mais tarde.', 'error');
        addressDataLoaded = false;
        return false;
    }
}

/**
 * Preenche os campos de endereço com dados da API
 * @param {object} data - Dados retornados pela API ViaCEP
 */
function preloadAddressFields(data) {
    streetInput.value = data.logradouro || '';
    neighborhoodInput.value = data.bairro || '';
    cityInput.value = data.localidade || '';
    stateInput.value = data.uf || '';
    
    setAddressFieldsDisabled(false);
    
    // Focus no campo de número
    numberInput.focus();
}

// ============================================
// EVENT LISTENERS
// ============================================

/**
 * Evento ao alterar o CEP com debounce
 */
cepInput.addEventListener('input', (e) => {
    const cep = formatCEP(e.target.value);
    cepInput.value = cep;

    // Limpa erro anterior
    cepError.classList.remove('show');

    // Limpa o timer anterior
    if (debounceTimer) {
        clearTimeout(debounceTimer);
    }

    // Se CEP tem menos de 8 dígitos, desabilita os campos
    if (cep.length < 8) {
        clearAddressFields();
        setAddressFieldsDisabled(true);
        addressDataLoaded = false;
        return;
    }

    // Valida e busca
    debounceTimer = setTimeout(() => {
        if (isValidCEP(cep)) {
            fetchCEPData(cep);
        } else {
            cepError.textContent = 'CEP deve conter 8 dígitos numéricos.';
            cepError.classList.add('show');
            clearAddressFields();
            setAddressFieldsDisabled(true);
            addressDataLoaded = false;
        }
    }, DEBOUNCE_TIME);
});

/**
 * Submissão do formulário
 */
addressForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Valida se os dados foram carregados
    if (!addressDataLoaded) {
        showStatusMessage('Aguarde o carregamento dos dados do CEP.', 'warning');
        return;
    }

    // Valida campos obrigatórios
    if (!cepInput.value || !streetInput.value || !numberInput.value || !cityInput.value || !stateInput.value) {
        showStatusMessage('Por favor, preencha todos os campos obrigatórios.', 'warning');
        return;
    }

    // Desabilita botão e mostra loader
    submitBtn.disabled = true;
    btnText.textContent = 'Enviando...';
    btnLoader.classList.remove('hidden');

    try {
        // Prepara dados
        const formData = new FormData();
        formData.append('cep', cepInput.value);
        formData.append('logradouro', streetInput.value);
        formData.append('numero', numberInput.value);
        formData.append('complemento', complementInput.value);
        formData.append('bairro', neighborhoodInput.value);
        formData.append('cidade', cityInput.value);
        formData.append('estado', stateInput.value);

        // Envia para o backend
        const response = await fetch(BACKEND_URL, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            // Sucesso - exibe mensagem de sucesso
            showStatusMessage(result.message, 'success');
            successMessage.textContent = result.message;
            
            // Esconde o formulário e mostra seção de sucesso
            addressForm.classList.add('hidden');
            successSection.classList.remove('hidden');
        } else {
            // Erro
            showStatusMessage(result.message || 'Erro ao enviar dados.', 'error');
        }

    } catch (error) {
        console.error('Erro ao enviar:', error);
        showStatusMessage('Erro ao enviar dados. Tente novamente.', 'error');
    } finally {
        // Re-habilita botão
        submitBtn.disabled = false;
        btnText.textContent = 'Enviar';
        btnLoader.classList.add('hidden');
    }
});

/**
 * Botão de limpar formulário
 */
resetBtn.addEventListener('click', () => {
    addressForm.reset();
    clearAddressFields();
    setAddressFieldsDisabled(true);
    hideStatusMessage();
    cepError.classList.remove('show');
    addressDataLoaded = false;
    cepInput.focus();
});

/**
 * Novo cadastro
 */
newRegistrationBtn.addEventListener('click', () => {
    addressForm.reset();
    clearAddressFields();
    setAddressFieldsDisabled(true);
    hideStatusMessage();
    cepError.classList.remove('show');
    addressDataLoaded = false;
    
    // Mostra formulário e esconde sucesso
    addressForm.classList.remove('hidden');
    successSection.classList.add('hidden');
    
    cepInput.focus();
});

/**
 * Inicialização
 */
document.addEventListener('DOMContentLoaded', () => {
    // Desabilita campos de endereço inicialmente
    setAddressFieldsDisabled(true);
    cepInput.focus();
});

// ============================================
// HEURÍSTICAS DE NIELSEN IMPLEMENTADAS
// ============================================
/*
1. Visibilidade do Status do Sistema:
   - Loader durante busca na API
   - Mensagens de status (sucesso, erro, aviso)
   - Desabilita campos que não são aplicáveis

2. Compatibilidade Sistema-Mundo Real:
   - Usa terminologia familiar (CEP, logradouro, bairro)
   - Feedback visual em tempo real (validação)

3. Controle e Liberdade do Usuário:
   - Botão "Limpar Formulário" para reset
   - Botão "Novo Cadastro" após sucesso
   - Pode editar qualquer campo

4. Prevenção de Erros:
   - Valida CEP (apenas números)
   - Mostra mensagens de erro claras
   - Desabilita submit se dados incompletos

5. Fazer e Manter o Usuário Informado:
   - Helpers text ("Ex: 01310100")
   - Seção de instruções ("Como Funciona")
   - Ícones indicam ações

6. Estética e Design Minimalista:
   - Design limpo e focado
   - Cores significativas (verde=sucesso, vermelho=erro)
   - Responsivo em todas as resoluções

7. Acessibilidade:
   - Labels associados aos inputs
   - ARIA labels
   - Suporte a dark mode
   - Respeita prefers-reduced-motion
*/
