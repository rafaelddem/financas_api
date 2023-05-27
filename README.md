Olá

Meu nome é Rafael, sou formado em Ciência da Computação desde 2015 e trabalho com desenvolvimento desde 2018, e esse é meu projeto de apresentação (um deles)

Comecei esse projeto já a tanto tempo que nem lembro mais, e nesse meio tempo modifiquei ele outras tantas vezes pelos mais diversos motivos, treinar linguagens de programações novas, frameworks novos, mudanças de funcionalidades... quase sempre começando um novo projeto do zero, por isso meu github possui tantos projetos com o mesmo propósito (mas espero que quando estiver lendo isso, eu já tenha me organizado quanto a eles). Por fim, optei por consolidar meus conhecimentos em PHP, por isso não utilizei nenhum framework nesse projeto, assim como também optei por cria-lo como uma API, e desta forma não sendo necessário utilizar de tecnologias de front-end. 

## 1. Sobre o projeto:

A ideia principal do sistema é criar uma API que seja capaz de gerenciar as finanças pessoais de uma determinada pessoa. Cadastro de compras, salário, empréstimos, geração de relatório de dívidas, previsão de gastos e entradas de valores, etc... A seguir, detalharei melhor cada função.

Obs.: Optei por utilizar os nomes de entidades, atributos e funções em inglês, pois notei que esse é o padrão utilizado na maioria dos projetos. Por esse motivo nomeei as entidades como "owner", "wallet" e "transaction" ao invés de "pessoa", "carteira" e "transação". No entanto, para essa documentação, escolhi também manter alguns nomes em português, pois acredito que isso facilitará a compreenção do funcionamento do sistema. Por exemplo, desssa forma posso descrever algo como "O título da transação identificará a mesma" ao invés de "O valor do atributo 'tittle' da entidade 'transaction' identificará a mesma". Outra questão sobre a decisão de manter os nomes em inglês é que pode levar a algumas complicações, como não encontrar uma tradução, ou achar, mas não ser precisa como na versão em português. Como por exemplo "boleto", que não encontrei uma tradução, e "fatura", que devido a dúvidas na precisão da tradução, optei por dar um nome mais genérico baseado em características da entidade (credit_card_dates).


### 1.1. Entidades


#### 1.1.1. Pessoa \ Pessoa Responsável (Owner)


##### 1.1.1.1. Descrição

A entidade Pessoa (internamente ao sistema, ela é identificada como "owner") é a entidade que representa cada pessoa (fisica ou jurídica) a qual será atribuída a propriedade de determinadas transações, assim como dos valores dessas transações. Por exemplo, caso o usuário de nome "Rafael" opte por cadastrar uma transação de depósito referente a um pagamento dele para outra pessoa, de nome "Marcos", este usuário deverá possuir dois cadastros de Pessoa, um para ele próprio (o qual será criado junto a conta no sistema) e outro para o destinatário do valor. Dessa forma, o sistema saberá que o valor foi transferido de uma pessoa para outra, e poderá calcular os novos valores após a transação.


##### 1.1.1.2. Atributos da entidade:

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços).
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #3).


##### 1.1.1.3. Banco de dados

Nome da tabela: owner

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- name: Referente ao atributo "nome". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 30;
    - não permite valor nulo;
    - valor único;
- active: Referente ao atributo "ativo". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária:
    - id


##### 1.1.1.4. Características da entidade

- Caracteristica #1: Não é permitido a exclusão de um registro de Pessoa, apenas sua inativação;

- Caracteristica #2: Não é permitido que duas Pessoas possuam o mesmo nome;

- Caracteristica #3: Não é permitido a inativação de uma Pessoa que possua pendências (Tarefa #1);

- Caracteristica #4: É exigido a existência de pelo menos uma Carteira (mais sobre a entidade Carteira no item 1.1.2) para cada Pessoa (Tarefa #2).


##### 1.1.1.5. Tarefas

Tarefa #1: 
- Objetivo: Validar se exitem "pendências" para uma determinada Pessoa.
- Método: Buscar por todas as transações que estão em aberto para esta Pessoa, como débitos e emprestimos não devolvidos.

Tarefa #2:
- Objetivo: Garantir que toda Pessoa possuia pelo menos uma Carteira (ver o item 1.1.2 para mais detalhes).
- Método: Criar uma Carteira automaticamente quando uma Pessoa é criada. A Carteira deve ser marcada como de posse da Pessoa em questão.

Tarefa #3:
- Objetivo: Identificar a carteira principal de uma Pessoa.
- Método: Buscar a carteira relacionada a Pessoa em questão, que esteja marcada como a principal.


#### 1.1.2. Carteira (wallet)


##### 1.1.2.1. Descrição

A entidade Carteira (internamente ao sistema, ela é identificada como "wallet") é a entidade que representa os locais onde os valores estão armazenados, como contas em bancos ou mesmo a carteira pessoal do usuário. Será possível que uma Pessoa tenha mais de uma Carteira. Por exemplo, o usuário "Rafael" poderá cadastrar três Carteiras, de nomes "Conta Corrente", "Carteira" e "Poupança", e dessa forma ele poderá separar os valores que estão em sua conta corrente dos valores que estão em sua poupança e do dinheiro que ele possui em sua carteira pessoal.


##### 1.1.2.2. Atributos da entidade:

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- dono (owner_id):
    - objetivo:             Manter o código de identificação do dono (Pessoa) desta entidade:
    - obrigatório:          Sim;
    - tipo dado:            Numérico.
    - tamanho:              (condicionado ao tamanho do código);
    - alteração:            Não permitida.
- carteira principal (main_wallet):
    - obejtivo:             Definir se dentre todas as Carteiras de uma Pessoa, esta é a principal delas;
    - obrigatório:          Sim;
    - tipo dado:            Booleano.
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #3).
- descrição (description): 
    - objetivo:             Salvar uma pequena descrição sobre o registro;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços).
    - tamanho:              Entre 0 e 255 caracteres;
    - alteração:            Permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano.
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #4 e #5).


##### 1.1.2.4. Banco de dados

Nome da tabela: wallet

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- name: Referente ao atributo "nome". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 30;
    - não permite valor nulo;
- owner_id: Referente ao atributo "dono". Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - não permite valor nulo.
- main_wallet: Referente ao atributo "carteira principal". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 0.
- description: Referente ao atributo "descrição". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 255.
- active: Referente ao atributo "ativo". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - owner_id faz referência ao atributo "id" da tabela "owner"


##### 1.1.2.4. Características da entidade

- Caracteristica #1: Não é permitida a exclusão de um registro de Carteira, apenas sua inativação;

- Caracteristica #2: Quando for a única Carteira de uma Pessoa, será obrigatóriamente marcada como a carteira principal (Tarefa #1);

- Caracteristica #3: Quando uma carteira é marcada como principal, as demais Carteiras (da mesma Pessoa) são automaticamente desmarcadas (Tarefa #2);

- Caracteristica #4: Não é permitida a inativação de uma Carteira que esteja marcada como principal (como quando for a única), que tenha valores (Tarefa #3) ou que possua pendências (Tarefa #4);

- Caracteristica #5: Não é permitida a reativação de uma Carteira cujo dono (Pessoa) estiver inativo (Tarefa #5).


##### 1.1.2.5. Tarefas

Tarefa #1: 
- Objetivo: Garantir a existência de pelo menos uma carteira principal para cada Pessoa.
- Método: Buscar todas as Carteiras relacionadas a Pessoa em questão, caso não existe nenhuma, a Carteira que estiver sendo salva será marcada como sendo a principal.

Tarefa #2: 
- Objetivo: Garantir a existência de uma única carteira principal para cada Pessoa.
- Método: Buscar todas as Carteiras relacionadas a Pessoa em questão, que estejam marcadas como principal. Caso seja encontrado alguma Carteira, a mesma será desmarcada como principal.

Tarefa #3: 
- Objetivo: Buscar o valor total presente em uma determinada Carteira.
- Método: Buscar por todas as transações (já quitadas) relacionadas a uma Carteira, e efetuar o somatório destes valores (valores de entradas menos valores de saída).

Tarefa #4: 
- Objetivo: Verificar a existencia de valores pendentes (crédito, emprestimos e transações agendadas) para uma determinada Carteira.
- Método: Buscar por todas as transações pendentes (crédito, emprestimos e transações agendadas...) relacionadas a uma Carteira.

Tarefa #5: 
- Objetivo: Garantir que uma Carteira não seja ativada quando seu usuário estiver desativado.
- Método: Confirmar se o dono (Pessoa) da carteira em questão está ativo.


#### 1.1.3. Cartão (Card)


##### 1.1.3.1. Descrição

A entidade Cartão (internamente ao sistema, ela é identificada como "card") é a entidade que representa os cartões de pagamento. Será possível criar cartões do tipo crétido ou débito, não sendo permitido cartão do tipo débito e crédito. O Cartão sempre será relacionada a uma entidade Carteira, de onde os valores movimentados pelo Cartão serão subtraídos. Ex.: Considere um Cartão de nome "NuBank débito", e que está relacionado a Carteira "NuBank". Considere também uma compra feita de R$ 10,00, e que foi paga com esse cartão. Nesse caso, a Carteira que será relacionada a venda, e portanto, de onde será subtraído o valor da transação, será a de nome "NuBank".


##### 1.1.3.2. Atributos da entidade:

- carteira (wallet_id):
    - objetivo:             Manter o código de identificação da Carteira a qual o cartão pertence:
    - obrigatório:          Sim;
    - tipo dado:            Numérico.
    - tamanho:              (condicionado ao tamanho do código);
    - alteração:            Não permitida.
- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 20 caracteres;
    - alteração:            Não permitida.
- crédito (credit):
    - obejtivo:             Define se o Cartão é do tipo "crédito";
    - obrigatório:          Sim;
    - tipo dado:            Booleano.
    - alteração:            Não permitida.
- primeiro dia do mês (first_day_month):
    - objetivo:             Define o primeiro dia da fatura do cartão;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              Valores de 1 até 28;
    - alteração:            Permitida.
- dias para o vencimento (days_to_expiration):
    - objetivo:             Será utilizado para o calculo do vencimento da fatura. O valor informado aqui será acrecido (em dias) a data do fechamento da fatura;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              Valores de 1 até 20;
    - alteração:            Permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano.
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #4 e #5).


##### 1.1.3.3. Banco de dados

Nome da tabela: card

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- wallet_id: Referente ao atributo "carteira". Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - não permite valor nulo.
- name: Referente ao atributo "nome". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 20;
    - não permite valor nulo;
    - valor único.
- credit: Referente ao atributo "crédito". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 0.
- first_day_month: Referente ao atributo "primeiro dia do mês". Terá as seguintes características:
    - tipo: int;
    - tamanho: 2;
    - não permite valor nulo;
- days_to_expiration: Referente ao atributo "dias para o vencimento". Terá as seguintes características:
    - tipo: int;
    - tamanho: 2;
    - não permite valor nulo.
- active: Referente ao atributo "ativo". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - wallet_id faz referência ao atributo "id" da tabela "wallet"


##### 1.1.3.4. Características da entidade

- Caracteristica #1: Não é permitida a exclusão de um registro de Carteira, apenas sua inativação;

- Caracteristica #2: É permitida a inativação de um Cartão, porém, isso não afeta suas fatura, que permanecerão em aberto até que sejam quitadas;

- Caracteristica #3: Não é permitida a reativação de um Cartão;

- Caracteristica #4: Quando uma entidade Cartão é criada, sua primeira Fatura (mais sobre a entidade Fatura no item 1.1.4) é criada automaticamente. Além de ser adicionado (o Cartão) a Rotina diária de fechamento/criação de faturas.





















#### 1.1.4. Credit Card Dates


##### 1.1.4.1. Descrição

A entidade "credit_card_dates" (chamaremos de Fatura) é a entidade que representa parte dos dados da fatura dos cartões de crédito.


##### 1.1.4.2. Propriedades

Da entidade:

- Essa entidade será mantida internamente quase que totalmente pelo sistema, não sendo possível a sua manutenção pelo usuário. A única exceção é a data de vencimento, que poderá ser alterada pelo usuário em alguns casos;
- O registro referente a fatura deve ser gerado no primeiro dia da mesma, logo no início do dia;
- Somente registros das faturas antigas e da atual serão mantidos, faturas futuras não devem ser salvas, uma vez que suas datas podem ser alteradas;
- Os registros das faturas fechadas e quitadas não podem ser excluídos ou alterados.

Dos seus atributos:

- card_id: Salva o código do Cartão (card) a qual essa Fatura se refere. Se relaciona com a entidade "card"
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Não deve ser permitido a alteração deste valor após seu cadastro;
- start_date: Este atributo salvará o primeiro dia da fatura. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Seu valor deve ser o dia seguinte ao fechamento da última fatura informada. Ex.: Se a última fatura foi do dia 05/05/2023 até o dia 04/06/2023, então está fatura deve iniciar em 05/06/2023;
    - Caso não haja faturas anteriores, deve-se calcular a data da seguinte forma: Pega-se o valor do atributo "first_day_month" do Cartão relacionado (entidade "card"), e calcula-se a última data para esse dia, que seja anterior a data atual. Ver a tarefa #1 (item 1.1.4.4) para mais detalhes;
    - Não deve ser permitido a alteração deste valor após seu cadastro;
    - Deverá respeitar o formato yyyy-mm-dd. Ex.: 2023-01-15.
- end_date: Este atributo salvará o primeiro dia da fatura. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Seu valor deve ser o dia anterior ao dia de início da próxima fatura. Ver a tarefa #2 (item 1.1.4.4) para mais detalhes;
    - O valor deste atributo deve ser sempre maior que o valor do atributo "start_date";
    - Deverá respeitar o formato yyyy-mm-dd. Ex.: 2023-01-15.
- due_date: Este atributo salvará a data de vencimento da fatura. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - O valor deste atributo deve ser sempre maior que o valor do atributo "end_date";
    - O valor do atributo é calculado seguindo as regras apresentadas na tarefa #3 (item 1.1.4.4);
    - O valor pode ser alterado posteriormente, desde que a fatura não esteja quitada.
    - Deverá respeitar o formato yyyy-mm-dd. Ex.: 2023-01-15.
- value: Registra o valor total da fatura. Particularidades:
    - O valor deste atributo será atualizado sempre que uma nova transação referente a essa fatura for feita;
    - Deverá respeitar o formato 000000.00. Ex.: 1225.75.
- paid: Define se a Fatura em questão está quitada ou não. Particularidades:
    - Na criação da entidade deverá ser marcado como "false";
    - Uma vez quitada, esse atributo será alterado para "true". Nesse caso, não será mais permitido a exclusão nem do registro da mesma, nem das transações referentes a essa fatura.


##### 1.1.4.3. Banco de dados

- card_id: Referente ao atributo "card_id". Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - não permite valor nulo.
- start_date: Referente ao atributo "start_date". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- end_date: Referente ao atributo "end_date". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- due_date: Referente ao atributo "due_date". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- value: Referente ao atributo "value". Terá as seguintes características:
    - tipo: double;
    - tamanho: 8 sendo 2 casas decimais.
- paid: Referente ao atributo "paid". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - card_id faz referência ao atributo "id" da entidade "card"


##### 1.1.4.4. Tarefas

Tarefa #1: Calculo do atributo "start_date" da entidade "credit_card_dates". Para um melhor entendimento, chamaremos os valores a serem considerados da seguinte forma:
- O valor do atributo "first_day_month" do cartão relacionado a fatura, será chamado de "primeiro dia do mês";
- O valor do atributo "start_date", será chamado de "data inicial da fatura";
- Onde ler "data atual", considerar o dia em que o cadastro do registro estiver sendo feito.

Caso o dia de hoje seja maior que o primeiro dia do mês, deve-se calcular a data inicial da fatura pegando a data atual e trocando o dia pelo primeiro dia do mês. Ex.: Caso hoje seja dia 15/05/2023, e o primeiro dia do mês seja dia 5, a data inicial da fatura já passou, pois ela foi dia 5 e já estamos no dia 15. Neste caso, troca-se apenas o dia, ficando a data inicial da fatura como 05/05/2023.

Caso o dia de hoje seja igual ao primeiro dia do mês, deve-se conciderar a data inicial da fatura como sendo o dia de hoje. Ex.: Caso hoje seja dia 10/05/2023, e o primeiro dia do mês seja dia 10, a data inicial da fatura será a data de hoje. Ou seja, deve ser cadastrada como 10/05/2023.

Caso o dia de hoje seja menor que o primeiro dia do mês, deve-se calcular a data inicial da fatura pegando a data atual, subtraindo um mês, e trocando o dia da data encontrada pelo primeiro dia do mês. Ex.: Caso hoje seja dia 05/05/2023, e o primeiro dia do mês seja dia 10, a data inicial da fatura ainda não chegou, pois ela será dia 10 e ainda estamos no dia 5. Neste caso, pega-se o dia atual (05/05/2023), subtrai-se um mês (do mês 05/2023 voltamos para o mês 04/2023) e troca-se o dia pelo primeiro dia do mês (do dia 05 vamos para o dia 10), ficando a data inicial da fatura como 10/04/2023.


Tarefa #2: Calculo do atributo "end_date" da entidade "credit_card_dates". Para um melhor entendimento, chamaremos os valores a serem considerados da seguinte forma:
- O valor do atributo "first_day_month" do cartão relacionado a fatura, será chamado de "primeiro dia do mês";
- O valor do atributo "start_date", será chamado de "data inicial da fatura";
- O valor do atributo "end_date", será chamado de "data final da fatura";
- Onde ler "data atual", considerar o dia em que o cadastro do registro estiver sendo feito.

Devemos começar calculando a data inicial da próxima fatura. Para isso, pega-se o primeiro dia do mês, e considera-se a proxima data em que ele será atingido. Por exemplo: 
Caso hoje seja dia 15/05/2023, e o primeiro dia do mês seja dia 05, então a data inicial da próxima fatura será dia 05/06/2023. 
Caso hoje seja dia 10/05/2023, e o primeiro dia do mês seja dia 10, então a data inicial da próxima fatura será dia 10/06/2023. 
Caso hoje seja dia 05/05/2023, e o primeiro dia do mês seja dia 10, então a data inicial da próxima fatura será dia 10/05/2023.

Uma vez definida a data inicial da próxima fatura, basta subtrair um dia para encontrar a data final da fatura atual. Exemplo: Se a data inicial da próxima fatura for 05/06/2023, então a data final da fatura atual será 04/06/2023.

Obs.: Apesar de calcular a data inicial da próxima fatura, ela não deve ser salva no banco. Somente a fatura inicial deve ser salva.


Tarefa #3: Calculo do atributo "due_date" da entidade "credit_card_dates". Para um melhor entendimento, chamaremos os valores a serem considerados da seguinte forma:
- O valor do atributo "duo_date", será chamado de "data de vencimento da fatura";
- O valor do atributo "days_to_expiration" do cartão relacionado a fatura, será chamado de "dias até o vencimento";
- O valor do atributo "end_date", será chamado de "data final da fatura";

O cálculo da data de vencimento da fatura deve ser feito pegando a data final da fatura, e somando os dias até o vencimento. Ex.: Caso a quantidade de dias até o vencimento seja 10, e a data final da fatura seja dia 15/05/2023, então a data de vencimento da mesma será dia 25/05/2023


#### 1.1.5. Payment Method


##### 1.1.5.1. Descrição

A entidade "payment method" é a entidade que representa os métodos de pagamento utilizados em cada transação, como por exemplo "Crédito", "Débito" e "Transferência".


##### 1.1.5.2. Propriedades

Da entidade:

- Não será permitido a exclusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


Dos seus atributos:

- name: Este será o nome de identificação da entidade. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá possuir entre 3 e 30 caracteres;
    - Não será permitido caracteres especiais (exceto: );
    - Não será possível efetuar a alteração desta propriedade;
    - O valor informado deverá ser único, não sendo possível que duas entidades "payment_method" possuam o mesmo "name".
- type: Define se a entidade se refe a transações feitas por cédulas, transações bancárias ou cartão (débito ou crétido). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá aceitar somente os valores "0", "1" ou "2";
    - Não será possível efetuar a alteração desta propriedade.
- active: Define se a entidade "payment method" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.

##### 1.1.5.3. Banco de dados

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- name: Referente ao atributo "name". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 30;
    - não permite valor nulo;
    - valor único.
- type: Referente ao atributo "type". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
- active: Referente ao atributo "active". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id


#### 1.1.6. Transaction Type


##### 1.1.6.1. Descrição

A entidade "transaction type" é a entidade que representa os tipos de transação. Será utilizado como forma de organizar as transações em grupos a critérios do usuário. Possíveis registros seriam "vendas", "compras", "empréstimos", "mensalidade", etc...


##### 1.1.6.2. Propriedades

Da entidade:

- Não será permitido a exclusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


Dos seus atributos:

- name: Este será o nome de identificação da entidade. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá possuir entre 3 e 45 caracteres;
    - Não será permitido caracteres especiais (exceto: );
    - Não será possível efetuar a alteração desta propriedade;
    - O valor informado deverá ser único, não sendo possível que duas entidades "payment_method" possuam o mesmo "name".
- relevance: Define a relevancia da transação ao qual esse registro é relacionado. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá aceitar somente os valores "0", "1" ou "2".
- active: Define se a entidade "transaction type" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.

##### 1.1.6.3. Banco de dados

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- name: Referente ao atributo "name". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 45;
    - não permite valor nulo;
    - valor único.
- relevance: Referente ao atributo "relevance". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
- active: Referente ao atributo "active". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id























#### 1.1.7. Transaction


##### 1.1.7.1. Descrição

A entidade "transaction" é utilizada (junto com a entidade "installment", item 1.1.8) para representar as diversas transações salvas no sistema.


##### 1.1.7.2. Propriedades

Da entidade:

- Será permitido a exclusão de um registro, exceto quando se tratar de uma venda no crédito, e a fatura referente a essa transação já estiver quitada.
- Quando uma venda no crédito for lançada, será necessário conferir se a data da transação ("transaction_date") é a mesma do dia do lançamento. Caso seja anterior, é necessário confirmar se ela pertence a uma fatura ainda em aberto.
- Apesar do valor bruto da transação (atributo "gross_value") ser informado na criação da transação, outros valores podem ser calculados a partir de outros atributos. Dois deles, o valor liquido e o valor final, serão calculados no momento que forem solicitados. Ver mais sobre isso nas tarefas #1 e #2 descritas no item 1.1.7.4.


Dos seus atributos:

- tittle: Este será o nome de identificação da transação. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá possuir entre 3 e 50 caracteres;
    - Não será permitido caracteres especiais (exceto: $);
- transaction_date: Este atributo salvará a data em que a transação foi efetuada. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Alteração permitida somente se suas parcelas (entidade "installment") não pertencerem à faturas (entidade "credit_card_dates") já fechadas (atributo "paid" marcado como "true");
    - Deverá respeitar o formato yyyy-mm-dd. Ex.: 2023-01-15.
- processing_date: Este atributo salvará a data em que a transação foi processada. Será útil para os casos de compras no cartão, onde nem sempre a transação é processada no mesmo dia da transação. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Por padrão, será o mesmo valor do atributo "transaction_date", porém, pode ser informado um valor diferente;
    - O valor deste atributo deve ser sempre igual ou maior que o valor do atributo "transaction_date";
    - Alteração permitida somente se suas parcelas (entidade "installment") não pertencerem à faturas (entidade "credit_card_dates") já fechadas (atributo "paid" marcado como "true");
    - Deverá respeitar o formato yyyy-mm-dd. Ex.: 2023-01-15.
- transaction_type: Salva o tipo de transação que o registro representa. Se relaciona com a entidade "transaction_type". Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade.
- gross_value: Registra o valor total da transação, no momento que esta é efetuada (não considera descontos ou arredondamentos). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Alteração permitida somente se suas parcelas (entidade "installment") não pertencerem à faturas (entidade "credit_card_dates") já fechadas (atributo "paid" marcado como "true");
    - Em caso de alteração, respeitar a tarefa #4 (item 1.1.7.4);
    - Deverá respeitar o formato 00000.00. Ex.: 25.75.
- discount_value: Registra o valor de desconto dado a transação, no momento que esta é efetuada (não considera descontos aplicados posteriormente nas parcelas). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Alteração permitida somente se suas parcelas (entidade "installment") não pertencerem à faturas (entidade "credit_card_dates") já fechadas (atributo "paid" marcado como "true");
    - Em caso de alteração, respeitar a tarefa #4 (item 1.1.7.4);
    - Deverá respeitar o formato 00000.00. Ex.: 25.75.
- relevance: Define a relevancia da transação ao qual esse registro é relacionado. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Por padrão, replicará o valor da entidade "transaction type" relacionada a transação, porém, será possível a escolha de um valor diferente;
    - Deverá aceitar somente os valores "0", "1" ou "2".
- description: Atributo utilizado para que seja possível salvar uma pequena descrição sobre o registro. Particularidades:
    - Deverá possuir no máximo 255 caracteres;
    - Não será permitido caracteres especiais (exceto: ).


##### 1.1.7.3. Banco de dados

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 6;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- tittle: Referente ao atributo "tittle". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 50;
    - não permite valor nulo.
- transaction_date: Referente ao atributo "transaction_date". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- processing_date: Referente ao atributo "processing_date". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- transaction_type: Referente ao atributo "transaction_type". Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - não permite valor nulo.
- gross_value: Referente ao atributo "gross_value". Terá as seguintes características:
    - tipo: double;
    - tamanho: 7 sendo 2 casas decimais;
    - não permite valor nulo.
- discount_value: Referente ao atributo "discount_value". Terá as seguintes características:
    - tipo: double;
    - tamanho: 7 sendo 2 casas decimais;
    - não permite valor nulo.
- relevance: Referente ao atributo "relevance". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
- description: Referente ao atributo "description". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 255.

- chave primária: 
    - id
- chave estrangeira: 
    - transaction_type faz referência ao atributo "id" da entidade "transaction type"


##### 1.1.7.4. Tarefas

Tarefa #1: O valor líquido da transação é calculado subtraindo o valor do atributo 'discount_value' do valor do atributo 'gross_value'.

Tarefa #2: O valor final da transação é calculado somando os valores finais de todas as suas parcelas (entidades "installment").

Tarefa #3: O valor bruto das parcelas (entidades "installment") é calculado somando o valor bruto (atributo "gross_value") de cada uma das parcelas de uma transação.

Tarefa #4: Deve confirmar se o valor da transação (Tarefa #1) confere com o valor das parcelas (Tarefa #3). Essa verificação deve ser feita sempre que um cadastro novo for inserido, assim como quando houver alterações nos valores das parcelas ou da transação







create table finance_api.installment (
	transaction int(6) not null, 
	installment_number int(2) not null, 
	due_date date not null, 

	gross_value double(7,2) not null, 
	discount_value double(7,2) not null, 
	interest_value double(7,2) not null, 
	rounding_value double(7,2) not null, 

	destination_wallet int(4) not null, 
	source_wallet int(4) default null, 
	payment_method int(3) default null, 
	payment_date date default null, 
    card
	primary key (transaction, installment_number), 
	constraint fk_installment_transaction foreign key (transaction) references transaction (id) on delete cascade, 
	constraint fk_installment_payment_method foreign key (payment_method) references payment_method (id), 
	constraint fk_installment_source_wallet foreign key (source_wallet) references wallet (id), 
	constraint fk_installment_destination_wallet foreign key (destination_wallet) references wallet (id) 
);

#### 1.1.8. Installment


##### 1.1.8.1. Descrição

A entidade "installment" é utilizada para representar as parcelas das diversas transações salvas no sistema.


##### 1.1.8.2. Propriedades

Da entidade:

- Apesar de ser tratada como uma "parcela", a entidade "installment" também é utilizada para vendas no débito e a vista. A diferença será a quantidade de registros de "installments" lançados. Para vendas no crédito, será um registro para cada parcela, enquanto nos demais casos será somente um. Em todos os casos somente existirá um registro "transaction" referenciado.
- Não será permitido a exclusão das parcelas de uma venda. Caso seja necessário alguma alteração, será preciso excluir a venda inteira (transaction e installment) e relança-la. Mesmo nesse caso, será preciso respeitar as regras para exclusão de uma "transaction";


Dos seus atributos:
- transaction: Referencia a transação a qual a parcela pertence. Se relaciona com a entidade "transaction". Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade.
- installment_number: Identificação da parcela. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá respeitar a ordem do vencimento das parcelas (a primeira parcela deve ter valor 1, a segunda 2, a terceira 3, etc...).
- due_date: Este atributo salvará a data de vencimento da fatura. Particularidades:
    - O valor deste atributo deve ser sempre maior que o valor do atributo "transaction_date" da entidade "transaction";
    - Deverá respeitar o formato yyyy-mm-dd. Ex.: 2023-01-15;
    - Deverá ser gerado com o mesmo dia da data da venda (atributo "transaction_date" da entidade "transaction"), porém, nos meses sequentes. Ex.: Uma venda no dia 25/05/2023 terá parcelas lancadas nos dias 25/05/2023, 25/06/2023, 25/07/2023... No entanto, quando uma nova fatura for gerada, a data poderá ser alterada seguindo as regras descritas na tarefa #1 no item 1.1.8.4.


- gross_value: Registra o valor inicial da parcela (não considera descontos, arredondamentos, juros, etc). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Alteração permitida somente se suas parcelas (entidade "installment") não pertencerem à faturas (entidade "credit_card_dates") já fechadas (atributo "paid" marcado como "true");
    - Em caso de alteração, respeitar a tarefa #1 (item 1.1.7.4);
    - Deverá respeitar o formato 00000.00. Ex.: 25.75.
- discount_value: Registra o valor de desconto dado a transação, no momento que esta é efetuada (não considera descontos aplicados posteriormente nas parcelas). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Alteração permitida somente se suas parcelas (entidade "installment") não pertencerem à faturas (entidade "credit_card_dates") já fechadas (atributo "paid" marcado como "true");
    - Em caso de alteração, respeitar a tarefa #1 (item 1.1.7.4);
    - Deverá respeitar o formato 00000.00. Ex.: 25.75.


##### 1.1.8.3. Banco de dados
- transaction: Referente ao atributo "transaction". Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - não permite valor nulo;
    - chave primaria.
- installment_number: Referente ao atributo "transaction". Terá as seguintes características:
    - tipo: int;
    - tamanho: 2;
    - não permite valor nulo;
    - chave primaria.
- due_date: Referente ao atributo "due_date". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.


- gross_value: Referente ao atributo "gross_value". Terá as seguintes características:
    - tipo: double;
    - tamanho: 7 sendo 2 casas decimais;
    - não permite valor nulo.
- discount_value: Referente ao atributo "discount_value". Terá as seguintes características:
    - tipo: double;
    - tamanho: 7 sendo 2 casas decimais;
    - não permite valor nulo.


- chave primária: 
    - id, installment_number
- chave estrangeira: 
    - transaction_type faz referência ao atributo "id" da entidade "transaction type"


##### 1.1.8.4. Tarefas

Tarefa #1: Quando uma fatura nova é gerada, as parcelas que pertencem a essa fatura terão suas datas de vencimento (due_date) alteradas para o primeiro dia da fatura. Ex.: Considere uma parcela que vence dia 25/06/2023. Considere também a fatura entre os dias 05/06/2023 e 04/07/2023. Nesse caso, quando a fatura for criada, a data de vencimento deve ser alterada de 25/06/2023 para 05/06/2023 (primeiro dia da fatura). Obs.: Essa regra só vale para os casos onde a fatura ainda não foi criada. Quando uma parcela é lançada com uma data de vencimento que percente a uma fatura já criada (caso da primeira parcela de uma venda), a data de vencimento deve ser mantida. Ex.: Consideranto a fatura em aberto ser entre os dias 05/06/2023 e 04/07/2023. Considerando também que o dia em questão é 25/06/2023. Caso uma venda seja lançada, a data de vencimento da primeira parcela será 25/06/2023 (não sofrendo a alteração).










installments
-quando informado um card, a payment_mathod ja deve ser informada e não pode ser alterada














1.1.1.1.1.1 Formatações especiais
- transaction_type: Salva o tipo de transação que o registro representa. Se relaciona com a entidade "transaction_type"
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - O formato do valor deve ser 00000.00. Até cinco dígitos a esquerda da vírgula, e sempre com duas casas decimais. O separador de casas decimais deve ser ponto (".") ao invés da vírgula (",");
    - Serão permitidos valores com até duas casas decimais.








Revisar


Alterações de funções

considerar a possibilidade de permitir a alteração do nomes dos registros (como o nome dos cartões), porém manter salvos os nomes antigos. COmo uma forma de garantir que se tenha o registro dos nomes antigos, evitando que alterações no nome causem erros. Por exemplo, trocar o nome de um cartão repetidas vezes, misturando assim os movimentos.

no caso da transaction, uma solução poderia ser um outro campo, para um "segundo titulo". Quando uma transação é criada, os dois campos são preenchidos da mesma forma, mas um deles permite a alteração. Assim, mesmo com um deles alterado, sempre existirá o registro do nome original


Alterações no doc

Chamar as entidades pelo onme "traduzido" e não pelo "original". Por exemplo informar "1.1.8. Parcela (Installment)" ao invés de "1.1.8. Installment". Ficando mais simples as descrições pois não precisaria ficar usando o nome em inglês