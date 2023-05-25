Olá

Meu nome é Rafael, sou formado em Ciência da Computação desde 2015 e trabalho com desenvolvimento desde 2018, e esse é meu projeto de apresentação (um deles)

Comecei esse projeto já a tanto tempo que nem lembro mais, e nesse meio tempo modifiquei ele outras tantas vezes pelos mais diversos motivos, treinar linguagens de programações novas, frameworks novos, mudanças de funcionalidades... quase sempre começando um novo projeto do zero, por isso meu github possui tantos projetos com o mesmo propósito (mas espero que quando estiver lendo isso, eu já tenha me organizado quanto a eles). Por fim, optei por consolidar meus conhecimentos em PHP, por isso não utilizei nenhum framework nesse projeto, assim como também optei por cria-lo como uma API, e desta forma não sendo necessário utilizar de tecnologias de front-end. 

## 1. Sobre o projeto:

A ideia principal do sistema é criar uma API que seja capaz de gerenciar as finanças pessoais de uma determinada pessoa. Cadastro de compras, salário, empréstimos, geração de relatório de dívidas, previsão de gastos e entradas de valores, etc... A seguir, detalharei melhor cada função.


### 1.1. Entidades


#### 1.1.1. Owner


##### 1.1.1.1. Descrição

A entidade "owner" é a entidade que representa cada pessoa (fisica ou jurídica) a qual será atribuída a propriedade de determinadas transações, assim como dos valores dessas transaçãoes. Por exemplo, caso o usuário de nome "Rafael" opte por cadastrar uma transação de depósito referente a um pagamento dele para outra pessoa de nome "Marcos", este usuário deverá possuir dois cadastros de "owner", um para ele próprio (o qual será criado junto a conta no sistema) e outro para o destinatário do valor. Dessa forma, o sistema saberá que o valor foi transferido de uma pessoa para outra, e poderá calcular os novos valores pós transação.


##### 1.1.1.2. Propriedades

Da entidade:

- Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


Dos seus atributos:

- name: Este será o nome de identificação da entidade. Particularidades:
    - Deverá ser informado no momento do cadastro da entidade;
    - Não será possível efetuar a alteração desta propriedade;
    - O valor informado deverá ser único, não sendo possível que duas entidades "owner" possuam o mesmo "name".
- active: Define se a entidade "owner" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.


##### 1.1.1.3. Banco de dados

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
    - valor único;
- active: Referente ao atributo "active". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária:
    - id


##### 1.1.1.4. Tarefas

Tarefa #1: Quando uma entidade "owner" é criada, uma entidade "wallet" (ver o item 1.1.2 para mais detalhes) deve ser criada automaticamente, e seu atributo "main_wallet" marcado como "true".


#### 1.1.2. Wallet


##### 1.1.2.1. Descrição

A entidade "wallet" (chamaremos de Carteira) é a entidade que representa os locais onde os valores estão armazenados, como contas em bancos ou mesmo a carteira pessoal do usuário. Será possível que um usuário (owner) tenha mais uma Carteira. Por exemplo, o usuário "Rafael" poderá cadastrar três Carteiras, de nomes "Conta Corrente", "Carteira" e "Poupança", e dessa forma ele poderá separar os valores que estão em sua conta corrente dos valores que estão em sua poupança e do dinheiro que ele possui em sua carteira pessoal.


##### 1.1.2.2. Propriedades

Da entidade:

- Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


Dos seus atributos:

- name: Este será o nome de identificação da entidade. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá possuir entre 3 e 30 caracteres;
    - Não será permitido caracteres especiais (exceto: );
    - Não será possível efetuar a alteração desta propriedade.
- owner_id: Este atributo manterá a identificação do dono (owner) desta entidade Particularidades:
    - Preenchimento obrigatório;
    - Não será possível efetuar a alteração desta propriedade.
- main_wallet: Este atributo definirá se a entidade "wallet" é a principal para aquele "owner". Particularidades:
    - Caso não seja informado no momento do cadastro da entidade, o valor padrão deverá ser "false";
    - Caso seja informado o valor "true" no momento do cadastro, deverá ser chamada a tarefa #2 (item 1.1.2.4);
    - A alteração do valor desse atributo é permitida respeitando as regras da tarefa #3 (item 1.1.2.4).
- description: Atributo utilizado para que seja possível salvar uma pequena descrição sobre o registro. Particularidades:
    - Deverá possuir no máximo 255 caracteres;
    - Não será permitido caracteres especiais (exceto: ).
- active: Define se a entidade "wallet" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.


##### 1.1.2.3. Banco de dados

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- name: Referente ao atributo "name". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 30;
    - não permite valor nulo;
- owner_id: Referente ao atributo "owner_id". Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - não permite valor nulo.
- main_wallet: Referente ao atributo "main_wallet". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 0.
- description: Referente ao atributo "description". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 255.
- active: Referente ao atributo "active". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - owner_id faz referência ao atributo "id" da entidade "owner"


##### 1.1.2.4. Tarefas

Tarefa #1: Quando uma entidade "owner" é criada, uma entidade "wallet" deve ser criada junto. Nesse caso, o atributo "owner_id" deve ser preenchido como o valor do atributo "id" da entidade "owner" recém criada, e o atributo "main_wallet" deve ser preenchido como "true".
Tarefa #2: Quando uma nova entidade "wallet" é criada, e o valor do atributo "main_wallet" vier marcado como "true", deverá ser confirmado com o usuário se ele deseja realmente marcar a entidade desta forma. Caso seja confirmado, uma rotina deverá marcar o atributo "main_wallet" de todas as outras entidades "wallet" neste usuário (owner) como "false", e então efetuar o cadastro.
Tarefa #3: Somente será possível a alteração do valor do atributo "main_wallet" para "true". Caso seja necessário que alguma entidade tenha esse atributo marcada como "false", outra entidade deverá ter seu atributo "main_wallet" marcado como "true".


#### 1.1.3. Card


##### 1.1.3.1. Descrição

A entidade "card" (chamaremos de Cartão) é a entidade que representa os cartões de pagamento. Será possível criar cartões do tipo crétido OU débito, não sendo permitido cartão do tipo débito E crédito. O Cartão sempre será relacionada a uma entidade "wallet" (chamaremos de Carteira), de onde os valores movimentados pelo Cartão serão subtraídos. Ex.: Considere um Cartão de nome "NuBank débito", e que está relacionado a Carteira "NuBank". Considere também uma compra feita de R$ 10,00, e que foi paga com esse cartão. Nesse caso, a Carteira que será relacionada a venda, e portanto, de onde será subtraído o valor da transação, será a de nome "NuBank".


##### 1.1.3.2. Propriedades

Da entidade:

- Não será permitido a exclusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false";
- Uma vez inativado, não será permitido a re-ativação de um registro "card".


Dos seus atributos:

- wallet_id: Salva o código da Carteira (wallet) a qual esse Cartão pertence. Se relaciona com a entidade "wallet". Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Não será possível efetuar a alteração desta propriedade.
- name: Este será o nome de identificação da entidade. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá possuir entre 3 e 20 caracteres;
    - Não será permitido caracteres especiais (exceto: );
    - Não será possível efetuar a alteração desta propriedade.
- credit: Define se o Cartão é do tipo "crédito". Caso não seja, seŕá considerado como "débito". Particularidades:
    - Não será possível efetuar a alteração desta propriedade.
- first_day_month: Define o primeiro dia da fatura do cartão. Por exemplo: Caso definido com valor 5, toda fatura se iniciará dia 5 e será fechada dia 4 do mês seguinte. Particularidades:
    - Preenchimento obrigatório;
    - O valor informado deverá ser maior ou igual a 1 e menor ou igual a 28. Essa regra visa considerar somente os dias válidos do mês. Não será permitido dias 29, 30 e 31 pois ne todo mês tem essa quantidade de dias.
- days_to_expiration: Será utilizado para o calculo do vencimento da fatura. O valor informado aqui será acrecido (em dias) a data do fechamento da fatura. Por exemplo: Caso definido com valor 6, uma fatura que fechou dia 4 terá seu vencimento definido para o dia 10 do mesmo mês. Particularidades:
    - Preenchimento obrigatório;
    - O valor informado deverá ser maior ou igual a 1 e menor ou igual a 20.
- active: Define se a entidade "card" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado (mesmo marcado como "false", a faturas em aberto ainda serão cobradas).

##### 1.1.3.3. Banco de dados

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- wallet_id: Referente ao atributo "wallet_id". Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - não permite valor nulo.
- name: Referente ao atributo "name". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 20;
    - não permite valor nulo;
    - valor único.
- credit: Referente ao atributo "credit". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 0.
- first_day_month: Referente ao atributo "first_day_month". Terá as seguintes características:
    - tipo: int;
    - tamanho: 2;
    - não permite valor nulo;
- days_to_expiration: Referente ao atributo "days_to_expiration". Terá as seguintes características:
    - tipo: int;
    - tamanho: 2;
    - não permite valor nulo;
- active: Referente ao atributo "active". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - wallet_id faz referência ao atributo "id" da entidade "wallet"


##### 1.1.3.4. Tarefas

Tarefa #1: Quando uma entidade "card" é criada, sua fatura (ver o item 1.1.4 para mais detalhes) deve começar a ser gerada automaticamente.


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
    - Deverá respeitar o formado yyy-mm-dd. Ex.: 2023-01-15.
- end_date: Este atributo salvará o primeiro dia da fatura. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Seu valor deve ser o dia anterior ao dia de início da próxima fatura. Ver a tarefa #2 (item 1.1.4.4) para mais detalhes;
    - O valor deste atributo deve ser sempre maior que o valor do atributo "start_date";
    - Deverá respeitar o formado yyy-mm-dd. Ex.: 2023-01-15.
- due_date: Este atributo salvará a data de vencimento da fatura. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - O valor deste atributo deve ser sempre maior que o valor do atributo "end_date";
    - O valor do atributo é calculado seguindo as regras apresentadas na tarefa #3 (item 1.1.4.4);
    - O valor pode ser alterado posteriormente, desde que a fatura não esteja quitada.
    - Deverá respeitar o formado yyy-mm-dd. Ex.: 2023-01-15.
- value: Registra o valor total da fatura. Particularidades:
    - O valor deste atributo deve ser preenchido somente quando a fatura em questão estiver quitada. Quando isto ocorrer, não será mais permitido a alteração de nenhum registro relacionado a esta fatura;
    - Deverá respeitar o formado 000000.00. Ex.: 1225.75.


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

- Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


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

- Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


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

A entidade "transaction" é a entidade (junto com a entidade "installment", item 1.1.6) que representa as diversas transações salvas no sistema.


##### 1.1.7.2. Propriedades

Da entidade:

- Será permitido a exclusão de um registro, exeto quando se tratar de uma venda no crédito, e a fatura referênte a essa transação já estiver quitada.


Dos seus atributos:

- tittle: Este será o nome de identificação da transação. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá possuir entre 3 e 50 caracteres;
    - Não será permitido caracteres especiais (exceto: $);
- transaction_date: Este atributo salvará a data em que a transação foi efetuada. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá respeitar o formado yyyy-mm-dd. Ex.: 2023-01-15.
- processing_date: Este atributo salvará a data em que a transação foi processada. Será útil para os casos de compras no cartão, onde nem sempre a transação é processada no mesmo dia da transação. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Por padrão, será o mesmo valor do atributo "transaction_date", porém, pode ser informado um valor diferente;
    - O valor deste atributo deve ser sempre igual ou maior que o valor do atributo "transaction_date";
    - Sua alteração será permitida somente se a fatura correspondente a transação não estiver fechada;
    - Deverá respeitar o formado yyy-mm-dd. Ex.: 2023-01-15.
- transaction_type: Salva o tipo de transação que o registro representa. Se relaciona com a entidade "transaction_type". Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade.
- gross_value: Registra o valor total da transação, no momento que esta é efetuada (não considera descontos ou arredondamentos). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá respeitar o formado 00000.00. Ex.: 25.75.
- discount_value: Registra o valor de desconto dado a transação, no momento que esta é efetuada (não considera descontos aplicados posteriormente nas parcelas). Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Deverá respeitar o formado 00000.00. Ex.: 25.75.
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
    - tamanho: 4;
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

Tarefa #1: .

-alterar data da transação, quando relativo a vendas no cartão, somente se fatura aberta
-alterar data da processamento, quando relativo a vendas no cartão, somente se fatura aberta
-atributos que são permitidos a alteração
-atributos que são permitidos a alteração somente se a fatura ainda não esiver fechada
-confirmar se o valor da transação confere com o valor das parcelas. O valor da transação deve ser calculada subtraindo o valor do atributo 'discount_value' do valor do atributo 'gross_value'. O valor das parcelas é econtrado osomando os valores de 'gross_value' de todas as parcelas






installments
-quando informado um card, a payment_mathod ja deve ser informada e não pode ser alterada







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






1.1.1.1.1.1 Formatações especiais
- transaction_type: Salva o tipo de transação que o registro representa. Se relaciona com a entidade "transaction_type"
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - O formato do valor deve ser 00000.00. Até cinco dígitos a esquerda da vírgula, e sempre com duas casas decimais. O separador de casas decimais deve ser ponto (".") ao invés da vírgula (",");
    - Serão permitidos valores com até duas casas decimais.













considerar a possibilidade de permitir a alteração do nomes dos registros (como o nome dos cartões), porém manter salvos os nomes antigos. COmo uma forma de garantir que se tenha o registro dos nomes antigos, evitando que alterações no nome causem erros. Por exemplo, trocar o nome de um cartão repetidas vezes, misturando assim os movimentos.