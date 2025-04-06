# PinkSale - Sistema SaaS para Consultores de Beleza

## Estrutura de Arquivos

/docs
    /brand
        - logo-pinksale.png           # Logo símbolo
        - logo-pinksale-texto.png     # Logo com texto
    /emails
        /templates
            - email-clientes.png      # Template de email para clientes
            - email-consultor.png     # Template de email para consultores
            - email-ativacao.png      # Email de ativação de conta
            - email-notificacoes.png  # Template de notificações do sistema

## 1. Regras Gerais do Sistema
- [x] Padronização de campos:
  - Campos OBS/OBSERVAÇÃO/DESCRIÇÃO como textarea
  - Autopreenchimento de endereço via API de CEP
- [x] Validações de exclusão (proteção de registros vinculados)
- [x] Padronização de busca de produtos (formato: "Marca - Produto")
- [x] Sistema multi-marca por usuário
- [x] Unificação da tabela de contatos para Clientes, Consultores, Parceiros e Abordagens

## 2. Módulos Core

### 2.1 Cadastro e Autenticação
- [ ] Formulário de registro com:
  - Email (único) com confirmação
  - Seleção multi-marca
  - Aceite de termos
- [ ] Sistema de ativação por email
- [ ] Trial de 7 dias
- [ ] Gestão de planos e assinaturas

### 2.2 Gestão de Clientes
- [x] Formulário principal com:
  - [x] Dados pessoais completos
  - [x] Endereço com autopreenchimento via CEP
  - [x] Sistema de aniversário
  - [x] Campos específicos de beleza (tipo de pele, tom)
  - [x] Sistema de fidelidade
- [x] Validações e máscaras:
  - [x] Formato de telefone
  - [x] Formato de CEP
  - [x] Campos obrigatórios
- [x] Sistema de busca e filtros
- [x] Ações em lote
- [ ] Histórico de compras
- [ ] Sistema de fidelidade

### 2.3 Gestão de Produtos
- [] Listagem principal com:
  - Busca por nome, código e marca
  - Filtros por status
  - Ordenação por colunas
  - Paginação
- [] Ações CRUD completas
- [] Interface responsiva
- [] Validações e formatações
- [ ] Gestão de estoque
- [ ] Histórico de movimentações

### 2.2 Dashboard Principal
#### Indicadores de Performance
- [ ] Metas e Realizações:
  - Sessões (agendadas/realizadas)
  - Vendas mensais
  - Abordagens cadastradas
  - Inícios efetivados
  - Encomendas pendentes
  - Necessidade de reposição
  - Entrevistas (agendadas/realizadas)
  - Urnas cadastradas

#### Indicadores Financeiros
- [ ] Receitas (vendas e bônus)
- [ ] Despesas mensais
- [ ] Saldo mensal
- [ ] Métricas de performance:
  - Valor médio por venda (últimos 3 meses)
  - Venda média por sessão
  - Média de participantes por sessão

#### Visualizações Gráficas
- [ ] Evolução de índices (12 meses):
  - Urnas cadastradas
  - Abordagens
  - Sessões realizadas
  - Vendas
- [ ] Análise de despesas:
  - Despesas gerais
  - Taxas de cartão
  - Taxas de parceiros
- [ ] Recebimentos por método:
  - Cartão
  - Cartão parceiros
  - Dinheiro
  - Cheque
- [ ] Evolução do balanço (12 meses):
  - Despesas+Custos
  - Receitas
  - Saldo

## 3. Módulo de Clientes e Parceiros

### 3.1 Gestão de Clientes
#### Cadastro e Importação
- [x] Formulário de cadastro com:
  - [x] Data de aniversário (dia/mês)
  - [x] Endereço completo com autopreenchimento via CEP
  - [x] Estados e cidades dinâmicos
  - [x] Observações (textarea)
  - [x] Configurações de fidelidade
  - [x] Características específicas:
    - [x] Tipo de pele (Seca, Normal, Oleosa, Mista)
    - [x] Tom de pele (personalizável)
- [ ] Sistema de importação em massa:
  - [ ] Template XLSX para download
  - [ ] Validação de dados
  - [ ] Importação em lote

#### Cartão Fidelidade
- [ ] Sistema configurável por cliente:
  - Prazo de validade
  - Meta de quantidade de vendas
  - Valor mínimo por venda
  - Valor do bônus
  - Regras de acumulação
- [ ] Gestão de pontos/bônus
- [ ] Histórico de utilização

#### Listagem e Visualização
- [x] Lista principal com:
  - [x] Dados principais do cliente
  - [ ] Data da última compra
  - [x] Endereço concatenado
  - [x] Estado/cidade concatenados
  - [x] Ações rápidas (Visualizar, Editar, Excluir, Copiar para Consultores)
- [x] Proteção contra exclusão de registros vinculados
- [ ] Sistema de cópia para consultores com confirmação de início

#### Perfil Detalhado do Cliente
- [x] Visualização em abas:
  1. Perfil
     - [x] Dados cadastrais completos
  2. Vendas
     - [ ] Total histórico
     - [ ] Lista detalhada de itens
     - [ ] Controle de vida útil dos produtos
  3. Indicações
     - [ ] Registro de indicações (Abordagens, Clientes, Consultores)
     - [ ] Categorização por tipo
  4. Sessões
     - [ ] Histórico de participação
     - [ ] Dados consolidados
  5. Cartão Fidelidade
     - [ ] Status atual
     - [ ] Histórico de uso
     - [ ] Configurações

#### Integrações
- [ ] Conexão com módulo de vendas
- [ ] Integração com sistema de indicações
- [ ] Vínculo com sessões e eventos
- [ ] Sistema de notificações para:
  - Aniversários
  - Vida útil de produtos
  - Metas de fidelidade

## 4. Módulo de Abordagens

### 4.1 Cadastro de Abordagens
- [ ] Formulário principal com:
  - Nome completo (único)
  - Sistema de indicação integrado:
    - Busca unificada (Clientes, Consultores, Parceiros, Sessões, Urnas)
    - Formatação específica por tipo de indicação
  - Endereço com autopreenchimento via CEP
  - Estados e cidades dinâmicos
  - Observações (textarea)
  - Tipo de abordagem (Início/Cliente)
- [ ] Sistema de entrevistas para tipo "Início":
  - Botão "Criar Entrevista"
  - Agendamento automático
  - Status pendente
  - Título padronizado
  - Descrição vinculada às observações
- [ ] Modais de cadastro rápido:
  - Cliente
  - Consultor
  - Parceiro
  - Sessão
  - Urna

### 4.2 Gestão de Abordagens
#### Listagem Principal
- [ ] Sistema de destaque automático:
  - Abordagens vencidas
  - Vencimentos do dia
  - Regras de priorização
- [ ] Filtros predefinidos por data
- [ ] Título dinâmico conforme filtro
- [ ] Ações rápidas:
  - Agendar entrevista
  - Visualizar
  - Editar
  - Excluir
  - Transferir para Cliente/Consultores
- [ ] Edição rápida de campos:
  - Data de retorno
  - Último contato

#### Transferências
- [ ] Sistema de conversão:
  - Abordagem tipo "Cliente" → Cliente
  - Abordagem tipo "Início" → Consultor
- [ ] Preservação de dados:
  - Manutenção do campo "Indicado por"
  - Histórico de conversão

#### Edição e Visualização
- [ ] Form de edição completo
- [ ] Sistema de exclusão
- [ ] Botões de transferência contextuais
- [ ] Preservação de relacionamentos

## 5. Módulo de Parceiros e Consultores

### 5.1 Gestão de Parceiros
#### Cadastro de Parceiros
- [ ] Formulário principal com:
  - Nome do parceiro (obrigatório e único)
  - Endereço completo com autopreenchimento via CEP
  - Estados e cidades dinâmicos
  - Observações (textarea)

#### Listagem e Visualização de Parceiros
- [ ] Lista principal com:
  - Dados principais do parceiro
  - Endereço concatenado
  - Estado/cidade concatenados
  - Máscaras para campos formatados
- [ ] Ações rápidas (Visualizar, Editar, Excluir)

#### Edição de Parceiros
- [ ] Form de edição completo
- [ ] Sistema de exclusão
- [ ] Validações de campos obrigatórios

### 5.2 Gestão de Consultores
#### Cadastro de Consultores
- [ ] Formulário principal com:
  - Nome completo (obrigatório e único)
  - Endereço completo com autopreenchimento via CEP
  - Estados e cidades dinâmicos
  - Observações (textarea)
  - Campo "Iniciado por mim"
- [ ] Sistema de convite pós-cadastro:
  - Envio automático de link para cadastro no sistema
  - Solicitação de autorização para acompanhamento de metas
  - Trigger apenas para consultores com "Iniciado por mim = Sim"

#### Listagem e Visualização de Consultores
- [ ] Lista principal com:
  - Dados principais do consultor
  - Endereço concatenado
  - Estado/cidade concatenados
  - Máscaras para campos formatados
- [ ] Ações rápidas (Visualizar, Editar, Excluir)

#### Edição de Consultores
- [ ] Form de edição completo
- [ ] Sistema de exclusão
- [ ] Validações de campos obrigatórios

## 6. Módulo de Sessões e Urnas

### 6.1 Sessões
#### Cadastro de Sessões
- [ ] Formulário principal com:
  - Seleção de anfitrião (cliente ou parceiro)
  - Autopreenchimento de dados do anfitrião:
    - Contato, Email, Telefones
    - Endereço completo
  - Data e horários
  - Sistema de snapshot de dados

#### Listagem de Sessões
- [ ] Lista principal com:
  - Dados principais da sessão
  - Indicadores consolidados:
    - Valor total de vendas
    - Quantidade de abordagens indicadas
    - Quantidade de inícios efetivos
  - Sistema de destaque para sessões pendentes
  - Filtros de período pré-definidos
- [ ] Ações rápidas (Visualizar, Editar, Excluir)

#### Visualização Detalhada
- [ ] Sistema de abas:
  1. Perfil
     - Dados completos da sessão
  2. Vendas
     - Valor total
     - Lista detalhada de vendas
  3. Indicações
     - Total de indicações
     - Lista de abordagens vinculadas
  4. Inícios Efetivos
     - Total de conversões
     - Lista de consultores convertidos

#### Edição de Sessões
- [ ] Form completo de edição
- [ ] Campo específico para participantes
- [ ] Sistema de exclusão

### 6.2 Urnas
#### Cadastro de Urnas
- [ ] Formulário principal com:
  - Seleção de parceiro com autopreenchimento
  - Sistema de status (ATIVA/RETIRADA)
  - Data fim condicional
  - Snapshot de dados do parceiro

#### Listagem de Urnas
- [ ] Lista principal com:
  - Dados principais da urna
  - Contadores de abordagens:
    - Clientes (total e efetivados)
    - Inícios (total e efetivados)
  - Sistema de destaque para urnas ativas vencidas
  - Filtros de período pré-definidos
- [ ] Ações rápidas (Visualizar, Editar, Excluir)

#### Visualização Detalhada
- [ ] Sistema de abas:
  1. Perfil
     - Dados completos da urna
  2. Abordagens
     - Lista de abordagens vinculadas
     - Separação entre clientes e inícios
  3. Efetivadas
     - Lista de conversões
     - Separação entre clientes e consultores

#### Edição de Urnas
- [ ] Form completo de edição
- [ ] Sistema de exclusão
- [ ] Validações de status e datas

## 7. Módulo de Vendas

### 7.1 Vendas
- [x] Estrutura básica de rotas
- [x] Componente de listagem (VendasIndex)
- [ ] Sistema de follow-up 2+2+2
- [ ] Registro de vendas com campos como data, valor total, desconto, status (EM ABERTO, PAGO, CANCELADA), observações
- [ ] Relacionamentos:
  - comprador_id (contato que efetua a compra)
  - consultor_id e parceiro_id (opcionais para comissões)
  - sessao_id (se a venda ocorrer durante uma sessão)
- [ ] Sistema de dedução automática do estoque (inclusive para kits)
- [ ] Sistema de cálculo de comissões
- [ ] Sistema de integração com sessões
- [ ] Sistema de pagamento
- [ ] Sistema de devolução

### 7.2 VendaItens
- [ ] Itens da venda, registrando produto (ou kit), quantidade, preço unitário, desconto aplicado e subtotal
- [ ] Sistema de cálculo de subtotal
- [ ] Sistema de cálculo de total da venda
- [ ] Sistema de cálculo de desconto
- [ ] Sistema de cálculo de valor líquido

## 8. Layouts e Interface

### 8.1 Design
- [x] Uso de Tailwind CSS e Alpine.js para um layout moderno
- [ ] Sidebar retrátil e header
- [ ] Componentes Blade com slots (como $header e $slot)

### 8.2 Componentes UI Base
- [x] Input
  - Suporte a tipos diferentes
  - Estados de erro e disabled
  - Estilização Tailwind
- [x] Select
  - Estados de erro e disabled
  - Estilização consistente com inputs
- [x] Badge
  - Variantes de cores
  - Sistema de status
- [x] Alert
  - Tipos: info, success, warning, error
  - Opção dismissível
  - Integração Alpine.js
- [x] Modal
  - Sistema de nomeação
  - Controle de largura
  - Acessibilidade
  - Navegação por teclado
  - Animações
  - Integração Alpine.js

### 8.3 Próximos Componentes UI
- [x] Button
  - Variantes: primary, secondary, danger
  - Estados: loading, disabled
  - Tamanhos: sm, md, lg
- [x] Card
  - Header com slots
  - Body com padding consistente
  - Footer opcional
- [x] Table
  - Cabeçalho fixo
  - Paginação
  - Ordenação
  - Responsividade
- [x] Dropdown
  - Posicionamento automático
  - Suporte a ícones
  - Keyboard navigation

### 8.4 Próximos Passos
- [x] Atualizar rotas no arquivo routes/web.php
- [x] Atualizar menus de navegação
- [ ] Revisar consistência visual dos componentes
- [ ] Implementar dark mode

## 9. Módulo de Aniversariantes
- [ ] Listagem principal com:
  - Navegação entre meses
  - Checkbox para marcar contatos realizados
  - Sistema de destaque para:
    - Aniversariantes do dia
    - Aniversariantes com data anterior sem contato
- [ ] Sistema de filtros por período

## 10. Módulo de Produtos e Estoque

### 10.1 Entrada de Estoque - Inicial/Avulso
#### Cadastro Manual
- [ ] Formulário principal com:
  - Data de inclusão
  - Busca de produtos
  - Sistema de desconto (0-100%, intervalos de 5%)
  - Tabela de produtos adicionados com:
    - Campos editáveis (Valor, Quantidade, Desconto)
    - Cálculo automático de valor com desconto
    - Botão de remoção por item
- [ ] Integração com módulo de despesas:
  - Lançamento automático no tipo "PRODUTO"
  - Cálculo de valor total gasto

#### Importação via Excel
- [ ] Template para download com:
  - Colunas não editáveis:
    - Marca
    - Código
    - Produto
    - Seção
  - Colunas editáveis:
    - Valor
    - Quantidade
- [ ] Ordenação automática por:
  - Marca (crescente)
  - Seção (crescente)
  - Produto (crescente)
- [ ] Sistema de upload e validação

### 10.2 Pedidos de Compra
#### Cadastro Manual
- [ ] Formulário principal com:
  - Dados básicos do pedido
  - Busca e adição de produtos
  - Sistema de quantidade
- [ ] Formas de pagamento:
  - Boleto
  - Débito Online
  - Cartão de Crédito (1x a 12x)
- [ ] Sistema de cartões:
  - Seleção de cartão cadastrado
  - Configuração de datas:
    - Vencimento da fatura
    - Fechamento da fatura
- [ ] Sistema de bônus:
  - Utilização de bônus disponível
  - Pagamentos parciais
  - Contabilização automática

#### Cadastro Semiautomático
- [ ] Sistema de importação via TXT:
  - Colagem de texto do portal do fornecedor
  - Análise automática de produtos
  - Sistema de detecção de kits:
    - Verificação de itens subsequentes
    - Marcação automática para exclusão
- [ ] Tabela de produtos com:
  - Campos não editáveis (Valor, Quantidade, Total, PTS)
  - Destaque para produtos não encontrados
  - Sistema de seleção de produtos do catálogo
  - Checkbox para exclusão de itens

#### Importação XML
- [ ] Sistema de upload múltiplo:
  - Suporte a múltiplos XMLs por pedido
  - Extração automática de dados:
    - Número do pedido
    - Data do pedido
    - Valor total
    - Códigos e nomes dos produtos
- [ ] Tratamento de dados:
  - Remoção de zeros à esquerda nos códigos
  - Formatação de nomes de produtos
- [ ] Sistema de pedidos existentes:
  - Detecção de pedidos já importados
  - Opção de adicionar produtos
  - Bloqueio de edição de dados principais

### 10.3 Produtos Pendentes
- [ ] Sistema de gestão de pendências:
  - Baixa por número de pedido
  - Baixa individual por item
  - Baixa parcial de quantidades
- [ ] Listagem filtrada:
  - Exibição apenas de produtos pendentes
  - Status de recebimento
- [ ] Integração com configurações:
  - Toggle via "Gerenciar Produtos Pendentes"
  - Controle de histórico por ativação

### 10.4 Saída de Estoque
- [ ] Cadastro de saídas:
  - Tipos: BRINDE, DEMONSTRAÇÃO, PERDA, USO
  - Sistema de busca de produtos
  - Controle de quantidades
  - Baixa automática no estoque

### 10.5 Extrato de Estoque
- [ ] Visualização de movimentações:
  - Identificação por tipo:
    - Número do pedido (Pedidos de Compra)
    - SAÍDA (Saída Estoque)
    - INICIAL/AVULSO (Cadastro Inicial)
  - Dados das transações:
    - Valor (quando aplicável)
    - Quantidade de produtos
    - Forma de pagamento/tipo de saída
    - Data do evento
    - Data de inclusão
- [ ] Sistema de detalhamento:
  - Visualização completa do registro
  - Opção de edição
  - Histórico de alterações
- [ ] Filtros de período pré-definidos

### 10.3 Contabilização
- [ ] Sistema de extrato:
  - Valor total do pedido
  - Detalhamento de formas de pagamento
  - Registro de bônus utilizados
- [ ] Integração financeira:
  - Fluxo de caixa (por data de parcela)
  - Balanço (por data do pedido)
- [ ] Sistema de bônus:
  - Lançamento como despesa (produtos)
  - Crédito na tabela de bônus

### 10.4 Gestão de Kits
- [ ] Sistema de cadastro:
  - Composição de produtos
  - Preços individuais
- [ ] Visualização no saldo:
  - Desmembramento em itens
  - Contabilização individual

### 10.6 Saldo de Estoque
- [ ] Sistema de três tabelas:
  1. Kits e Sistemas Disponíveis:
    - Cálculo automático de kits montáveis
    - Preview de componentes via tooltip
    - Filtros de período
  2. Seção 1:
    - Listagem de produtos com:
      - Nome do produto
      - Média de vendas (90 dias)
      - Saldo inicial
      - Entradas
      - Saídas
      - Saldo atual
      - Valor unitário
    - Destaque automático para reposição
    - Valor total do estoque
    - Sistema de detalhamento por produto
  3. Seção 2:
    - Mesma estrutura da Seção 1
    - Sem cálculo de valores

#### Sistema de Detalhamento
- [ ] Visualização por produto:
  - Saídas:
    - Entregues (vendas finalizadas)
    - Reservados (vendas pendentes)
    - Baixas (saídas manuais)
    - Empréstimos ativos
  - Entradas:
    - Movimentações de estoque
    - Empréstimos recebidos
  - Contabilização integrada com kits

#### Contabilização de Kits
- [ ] Sistema de gestão componente-kit:
  - Baixa automática de componentes
  - Atualização de saldos individuais
  - Integração com:
    - Vendas
    - Empréstimos
    - Entradas
    - Saídas

### 10.7 Catálogo de Produtos
#### Cadastro de Produtos
- [ ] Formulário principal com:
  - Campos básicos do produto
  - Sistema de tipo (Normal/Kit)
  - Componentes para kits:
    - Interface de tags
    - Seleção múltipla
    - Quantidades por item
- [ ] Integração com sistema admin:
  - Controle de marcas editáveis
  - Permissões por marca

#### Gestão de Produtos
- [ ] Listagem principal:
  - Produtos manuais
  - Produtos automáticos (admin)
  - Indicador de editável/não-editável
- [ ] Sistema de edição:
  - Formulário completo
  - Validações por marca
  - Controle de exclusão

## 11. Módulo de Empréstimos e Trocas

### 11.1 Empréstimos
#### Cadastro de Empréstimos
- [ ] Formulário principal com:
  - Seleção de produtos com marca
  - Tipo por produto (Entregue/Recebido)
  - Sistema de desconto (0-100%, intervalos de 5%)
  - Tabela de produtos com:
    - Marca (primeira coluna)
    - Produto
    - Quantidade
    - Valor cheio
    - Desconto
    - Valor final
  - Cálculo automático de totais
- [ ] Tela de resumo pós-cadastro

#### Listagem de Empréstimos
- [ ] Lista principal com:
  - Número sequencial
  - Tipo (Entregue/Recebido)
  - Dados do empréstimo
  - Filtros avançados
  - Filtros de período predefinidos
- [ ] Edição rápida na tabela:
  - Status com data obrigatória
  - Quantidade (validação min/max)
  - Sistema de devolução parcial

#### Edição de Empréstimos
- [ ] Form completo de edição
- [ ] Sistema de exclusão
- [ ] Validações de status

### 11.2 Pagamentos e Recebimentos de Consultores
#### Cadastro de Movimentações
- [ ] Formulário principal com:
  - Tipo (Pagamento/Recebimento)
  - Seleção de consultor
  - Valor
  - Observações
- [ ] Integração com despesas:
  - Lançamento automático tipo "PRODUTOS"
  - Formatação de observações
  - Tratamento de sinal (positivo/negativo)

#### Listagem de Movimentações
- [ ] Lista principal com:
  - Dados da movimentação
  - Filtros de período predefinidos
  - Sistema DataTables
  - Ações rápidas (Editar/Excluir)

#### Edição de Movimentações
- [ ] Form completo de edição
- [ ] Sistema de exclusão
- [ ] Validações de valores

### 11.3 Saldo de Consultores
- [ ] Sistema de visualização com grupos:
  1. Vendas:
    - Comissão
    - Taxa cartão
  2. Empréstimos:
    - Entregue
    - Recebido
    - Saldo
  3. Pagamentos
  4. Saldo Final
- [ ] Links integrados:
  - Pagamentos/Recebimentos → Listagem específica
  - Comissões → Relatório de Vendas Parceiros
  - Taxa cartão → Recebimentos do parceiro
- [ ] Separação visual de grupos
- [ ] Cálculos automáticos de saldos

## 12. Módulo Financeiro

### 12.1 Recebimentos
#### Cadastro de Recebimentos
- [ ] Sistema multi-tipo:
  - Dinheiro/PIX
  - Cartão próprio
  - Cartão parceiro
- [ ] Seleção de vendas:
  - Modal de vendas em aberto
  - Cálculo automático de totais
  - Atualização de status
- [ ] Integração com bônus:
  - Exibição de saldo
  - Uso parcial/total
- [ ] Parcelamento:
  - Geração automática
  - Agenda de cobranças
  - Fluxo de caixa por vencimento
  - Balanço por recebimento

#### Recebimentos por Tipo
- [ ] Dinheiro/PIX:
  - Parcelamento manual
  - Datas personalizadas
  - Registro em agenda
  - Suporte a devoluções
- [ ] Cartão próprio:
  - Seleção de máquina
  - Cálculo automático de taxas
  - Lançamento em despesas
  - Fluxo por modalidade
- [ ] Cartão parceiro:
  - Seleção de parceiro/consultor
  - Cálculo de taxa parceiro
  - Integração com comissões

### 12.2 Bônus
- [ ] Cadastro de bônus
- [ ] Listagem e gestão
- [ ] Sistema de utilização

### 12.3 Máquinas de Cartão
- [ ] Cadastro com configurações:
  - Taxas por modalidade
  - Prazos de recebimento
  - Valores fixos
- [ ] Cálculos automáticos:
  - Taxa efetiva
  - Fluxo de recebimento
- [ ] Gestão de planos

### 12.4 Despesas
#### Cadastro de Despesas
- [ ] Formulário principal:
  - Multi-forma de pagamento
  - Integração com cartões
  - Tipos customizáveis
- [ ] Parcelamento:
  - Cartão de crédito até 12x
  - Fluxo por parcela

#### Gestão de Despesas
- [ ] Listagem unificada:
  - Manuais e automáticas
  - Dados relacionados:
    - Evento/Fluxo
    - Pedidos
    - Parceiros
  - Filtros de período
- [ ] Sistema de edição
- [ ] Exclusão com validações

### 12.5 Cartões de Crédito
#### Cadastro de Cartões
- [ ] Formulário com:
  - Bandeira
  - Últimos 4 dígitos
  - Configurações de fatura:
    - Data fechamento
    - Data vencimento
- [ ] Validações de unicidade

#### Gestão de Cartões
- [ ] Listagem completa
- [ ] Ações de edição
- [ ] Sistema de exclusão

### 12.6 Pagamentos e Recebimentos de Parceiros
#### Cadastro de Movimentações
- [ ] Formulário principal com:
  - Tipo (Pagamento/Recebimento)
  - Seleção de parceiro
  - Valor com tratamento de sinal
  - Observações
- [ ] Integração automática:
  - Recebimentos via cartão parceiro
  - Comissões de vendas

#### Listagem de Movimentações
- [ ] Lista principal com:
  - Dados da movimentação
  - Diferenciação visual por tipo
  - Filtros de período
  - Sistema DataTables
  - Ações rápidas (Editar/Excluir)

### 7.1 Vendas (Complemento)
#### Cadastro de Vendas
- [ ] Sistema de comissões:
  - Cálculo automático
  - Integração com despesas
- [ ] Origem da venda:
  - Venda avulsa
  - Integração com sessões
- [ ] Sistema de descontos:
  - Valor fixo (R$)
  - Percentual (%)
- [ ] Controle de vida útil:
  - Data por produto
  - Integração com perfil cliente
- [ ] Sistema de bônus:
  - Exibição de saldo
  - Uso parcial/total
- [ ] Status de entrega:
  - Por item
  - Data de entrega
  - Sistema 2+2+2
- [ ] Resumo da venda:
  - Dados completos
  - Opção de impressão
  - Envio automático por email

#### Listagem de Vendas
- [ ] Lista principal com:
  - Filtros avançados
  - Períodos predefinidos
  - Status de pagamento
  - Totalizadores
- [ ] Destaques automáticos:
  - Produtos com vida útil vencida
  - Entregas pendentes
- [ ] Ações contextuais:
  - Detalhamento
  - Edição completa
  - Atualização de status

#### Relatórios
- [ ] Entregas pendentes:
  - Produtos solicitados
  - Agrupamento por cliente
  - Datas previstas

## 13. Módulo de Análise de Vendas

### 13.1 Produtos Mais Vendidos
- [ ] Listagem principal com:
  - Ranking por quantidade vendida
  - Filtros por período
  - Detalhamento por produto:
    - Lista de clientes compradores
    - Quantidades individuais

### 13.2 Ranking de Clientes
- [ ] Lista principal com:
  - Ordenação por valor total de compras
  - Filtros por período
  - Detalhamento por cliente:
    - Produtos adquiridos
    - Valores individuais

### 13.3 Sistema 2+2+2
- [ ] Controle de pós-venda:
  - 2 dias após entrega
  - 2 semanas após entrega
  - 2 meses após entrega
- [ ] Listagem de pendências:
  - Agrupamento por cliente
  - Ordenação por data de entrega
  - Sistema de marcação de contatos
- [ ] Relatórios detalhados
- [ ] Integração com agenda

## 14. Módulo de Agenda

### 14.1 Gestão de Compromissos
- [ ] Cadastro manual de eventos
- [ ] Integração automática com:
  - Sessões
  - Abordagens
  - Urnas
  - Aniversários
  - Pagamentos parcelados
  - Cheques
  - Sistema 2+2+2
- [ ] Diferenciação visual:
  - Cores por tipo de compromisso
  - Status de conclusão
- [ ] Sincronização bidirecional:
  - Atualização automática com origem
  - Edição de status para recebimentos

## 15. Módulo de Relatórios Financeiros

### 15.1 Balanço Mensal
- [ ] Visualização semestral móvel:
  - 6 meses por vez
  - Navegação entre períodos
- [ ] Sistema de colapso por categoria
- [ ] Cálculos automáticos:
  - Receitas (Vendas + Bônus)
  - Custos (Produtos + Movimentações)
  - Lucro Bruto
  - Despesas (Categorias dinâmicas)
  - Lucro Líquido
- [ ] Formatação visual:
  - Receitas em azul
  - Custos/Despesas em vermelho
  - Resultados condicionais

### 15.2 Fluxo de Caixa
- [ ] Visualização de 7 meses:
  - Mês atual
  - 3 meses anteriores
  - 3 meses futuros
- [ ] Cálculos automáticos:
  - Saldo inicial
  - Entradas detalhadas
  - Saídas por categoria
  - Saldo final
- [ ] Controle de pendências:
  - Saldo de clientes
  - Saldo de consultores
  - Saldo de parceiros
- [ ] Sistema de parcelamentos:
  - Integração com máquinas de cartão
  - Controle de cartões próprios

## 16. Módulo de Saldos e Extratos

### 16.1 Saldo Clientes e Parceiros
- [ ] Visualização filtrada por período
- [ ] Exibição de saldos:
  - Positivos (azul)
  - Negativos (vermelho)
- [ ] Links diretos para:
  - Relatório de vendas
  - Histórico de pagamentos
  - Extrato detalhado
  - Comissões
  - Recebimentos cartão

### 16.2 Saldo Negócio
- [ ] Dashboard principal com:
  - Filtros de período predefinidos
  - Formatação condicional (azul/vermelho)
  - Entradas e saídas destacadas

### 16.3 Vendas Parceiros/Consultores
- [ ] Relatório detalhado com:
  - Data e hora de emissão
  - Filtros por período e parceiro
  - Totalizadores:
    - Pedidos efetivados
    - Cancelamentos
    - Comissões
    - Saldo extrato
    - Taxa cartão
    - Saldo empréstimos
    - Saldo final

## 17. Módulo Minha Conta

### 17.1 Gestão de Perfil
- [ ] Meus Dados:
  - Edição de dados básicos
  - Alteração de senha
  - Seleção de marcas
- [ ] Endereço:
  - Gestão de endereço completo

### 17.2 Planos e Assinaturas
- [ ] Visualização de:
  - Plano atual
  - Última transação
  - Data de validade
- [ ] Sistema de upgrade:
  - Integração Mercado Pago
  - Histórico de transações

### 17.3 Sistema de Indicações
- [ ] Gestão de pontos:
  - Registro de indicações
  - Cálculo automático
  - Sistema de recompensas
- [ ] Configurações admin:
  - Pontuação por plano
  - Valor de resgate
  - Regras de conversão

### 17.4 Central de Notificações
- [ ] Configurações gerais:
  - Notificações do sistema
  - Notificações manuais
- [ ] Automações para clientes:
  - Aniversariantes
  - Pedidos de compra
  - Confirmação de pagamentos
- [ ] Controles de ativação

### 17.5 Configurações do Sistema
- [ ] Preferências gerais:
  - Produtos pendentes
  - Sistema de metas
- [ ] Gestão de metas:
  - Configuração personalizada
  - Seleção de indicadores
- [ ] Manutenção do sistema:
  - Limpeza total
  - Limpeza seletiva
  - Backup de dados

## 18. Módulo Administrativo

### 18.1 Painel Geral Admin
- [ ] Dashboard com indicadores:
  - Total usuários cadastrados
  - Total produtos cadastrados
  - Total planos vendidos
- [ ] Gráficos de performance:
  - Usuários por mês
  - Vendas de planos

### 18.2 Gerenciamento de Catálogo
- [ ] Gestão de produtos:
  - CRUD completo
  - Importação em massa
- [ ] Gestão de marcas:
  - CRUD completo
  - Sistema de ativação

### 18.3 Gestão de Planos e Usuários
- [ ] Gerenciamento de planos:
  - Configuração de valores
  - Sistema de pontuação
  - Regras de resgate
- [ ] Gestão de usuários:
  - Histórico de acessos
  - Status de pagamento
  - Controle de desconto
- [ ] Feature de limpeza:
  - Remoção automática
  - Configuração de prazo

### 18.4 Comunicação
- [ ] Sistema de mailing:
  - Seleção manual/automática
  - Filtros predefinidos
  - Templates personalizados
- [ ] Push notifications:
  - Segmentação de usuários
  - Mensagens customizadas

## 19. Sistema de Notificações

### 19.1 Badges Numéricos
- [ ] Indicadores por módulo:
  - Vendas pendentes
  - Abordagens vencidas
  - Urnas ativas
  - Sessões pendentes
  - Aniversariantes
  - Produtos pendentes
  - Cheques a depositar
  - Compromissos do dia

### 19.2 Automações (CRON)
- [ ] Lembretes diários (8h):
  - Pós-venda
  - Cheques
  - Abordagens
  - Agenda
  - Aniversariantes
- [ ] Avisos de vencimento:
  - 5 dias antes
  - Dia do vencimento
- [ ] Notificações de agenda:
  - Verificação horária
  - Alertas de compromissos

## 20. Aplicativo Mobile

### 20.1 Funcionalidades Core
- [ ] Paridade com sistema web
- [ ] Importação de contatos
- [ ] Integração com funções nativas:
  - Discagem direta
  - Cliente de email
  - Câmera

### 20.2 Integrações
- [ ] Widget Freshdesk
- [ ] Google Analytics
- [ ] Sistema de push

## Prioridades Imediatas

### Fase 1: Funcionalidades Core
1. Módulo de Vendas
   - [ ] Sistema de cálculo de subtotal
   - [ ] Sistema de cálculo de desconto
   - [ ] Dedução automática do estoque

2. Gestão de Consultores
   - [ ] Sistema de convite pós-cadastro
   - [ ] Fluxo de autorização
   - [ ] Dashboard básico

3. Sistema de Abordagens
   - [ ] Formulário principal
   - [ ] Sistema de entrevistas
   - [ ] Conversão para Cliente/Consultor

### Fase 2: MVP Visual
1. Layout Base
   - [ ] Header responsivo
   - [ ] Sidebar funcional
   - [ ] Navegação principal

2. Componentes Essenciais
   - [ ] Cards de dados
   - [ ] Tabelas responsivas
   - [ ] Formulários básicos

### Fase 3: Refinamento
1. UI/UX Avançado
   - [ ] Templates de email
   - [ ] Animações e transições
   - [ ] Guia de estilo completo














