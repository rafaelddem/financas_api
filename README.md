Olá

Meu nome é Rafael, sou formado em Ciência da Computação desde 2015 e trabalho com desenvolvimento desde 2018, e esse é meu projeto de apresentação (um deles).

Comecei esse projeto já a tanto tempo que nem lembro mais, e nesse meio tempo modifiquei ele outras tantas vezes pelos mais diversos motivos, treinar linguagens de programações novas, frameworks novos, mudanças de funcionalidades... quase sempre começando um novo projeto do zero, por isso meu github possui tantos projetos com o mesmo propósito (mas espero que quando estiver lendo isso, eu já tenha me organizado quanto a eles). Por fim, optei por consolidar meus conhecimentos em PHP, por isso não utilizei nenhum framework nesse projeto, assim como também optei por cria-lo como uma API, e desta forma não sendo necessário utilizar de tecnologias de front-end. 

## 1. Sobre o projeto

A ideia principal do sistema é criar uma API que seja capaz de gerenciar as finanças pessoais de uma determinada pessoa. Cadastro de compras, salário, empréstimos, geração de relatório de dívidas, previsão de gastos e entradas de valores, etc... A seguir, detalharei melhor cada função.

Obs.: Optei por utilizar os nomes de entidades, atributos e funções em inglês, pois notei que esse é o padrão utilizado na maioria dos projetos. Por esse motivo nomeei as entidades como "owner", "wallet" e "transaction" ao invés de "pessoa", "carteira" e "transação". No entanto, para essa documentação, escolhi também manter alguns nomes em português, pois acredito que isso facilitará a compreenção do funcionamento do sistema. Por exemplo, desssa forma posso descrever algo como "O título da transação identificará a mesma" ao invés de "O valor do atributo 'tittle' da entidade 'transaction' identificará a mesma". Outra questão sobre a decisão de manter os nomes em inglês é que pode levar a algumas complicações, como não encontrar uma tradução, ou achar, mas não ser precisa como na versão em português. Como por exemplo "boleto", que não encontrei uma tradução, e "fatura", que devido a dúvidas na precisão da tradução, optei por dar um nome mais genérico baseado em características da entidade (credit_card_dates).


### 1.1. Entidades


#### 1.1.1. Pessoa \ Pessoa Responsável (Owner)


##### 1.1.1.1. Descrição

A entidade Pessoa (internamente ao sistema, identificada como "owner") é a entidade que representa cada pessoa (fisica ou jurídica) a qual será atribuída a propriedade de determinadas transações, assim como dos valores dessas transações. Por exemplo, caso o usuário de nome "Rafael" opte por cadastrar uma transação de depósito referente a um pagamento dele para outra pessoa, de nome "Marcos", este usuário deverá possuir dois cadastros de Pessoa, um para ele próprio (o qual será criado junto a conta no sistema) e outro para o destinatário do valor. Dessa forma, o sistema saberá que o valor foi transferido de uma pessoa para outra, e poderá calcular os novos valores após a transação.


##### 1.1.1.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #3).


##### 1.1.1.3. Banco de dados

Nome da tabela: owner.

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
    - valor único.
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

Tarefa #1: Validar se exitem "pendências" para uma determinada Pessoa.
> Buscar por todas as transações que estão em aberto para esta Pessoa, como débitos e emprestimos não devolvidos.

Tarefa #2: Garantir que toda Pessoa possuia pelo menos uma Carteira (ver o item 1.1.2 para mais detalhes).
> Criar uma Carteira automaticamente quando uma Pessoa é criada. A Carteira deve ser marcada como de posse da Pessoa em questão.

Tarefa #3: Identificar a carteira principal de uma Pessoa.
> Buscar a carteira relacionada a Pessoa em questão, que esteja marcada como a principal.


#### 1.1.2. Carteira (wallet)


##### 1.1.2.1. Descrição

A entidade Carteira (internamente ao sistema, identificada como "wallet") é a entidade que representa os locais onde os valores estão armazenados, como contas em bancos ou mesmo a carteira pessoal do usuário. Será possível que uma Pessoa tenha mais de uma Carteira. Por exemplo, o usuário "Rafael" poderá cadastrar três Carteiras, de nomes "Conta Corrente", "Carteira" e "Poupança", e dessa forma ele poderá separar os valores que estão em sua conta corrente dos valores que estão em sua poupança e do dinheiro que ele possui em sua carteira pessoal.


##### 1.1.2.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- dono (owner_id):
    - objetivo:             Manter o código de identificação do dono (Pessoa) desta entidade;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do código);
    - alteração:            Não permitida.
- carteira principal (main_wallet):
    - obejtivo:             Definir se dentre todas as Carteiras de uma Pessoa, esta é a principal delas;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #3).
- descrição (description): 
    - objetivo:             Salvar uma pequena descrição sobre o registro;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              Entre 0 e 255 caracteres;
    - alteração:            Permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #4 e #5).


##### 1.1.2.3. Banco de dados

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
    - não permite valor nulo.
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

Tarefa #1: Garantir a existência de pelo menos uma carteira principal para cada Pessoa.
> Buscar todas as Carteiras relacionadas a Pessoa em questão, caso não existe nenhuma, a Carteira que estiver sendo salva será marcada como sendo a principal.

Tarefa #2: Garantir a existência de uma única carteira principal para cada Pessoa.
> Buscar todas as Carteiras relacionadas a Pessoa em questão, que estejam marcadas como principal. Caso seja encontrado alguma Carteira, a mesma será desmarcada como principal.

Tarefa #3: Buscar o valor total presente em uma determinada Carteira.
> Buscar por todas as transações (já quitadas) relacionadas a uma Carteira, e efetuar o somatório destes valores (valores de entradas menos valores de saída).

Tarefa #4: Verificar a existencia de valores pendentes (crédito, emprestimos e transações agendadas) para uma determinada Carteira.
> Buscar por todas as transações pendentes (crédito, emprestimos e transações agendadas...) relacionadas a uma Carteira.

Tarefa #5: Garantir que uma Carteira não seja ativada quando seu usuário estiver desativado.
> Confirmar se o dono (Pessoa) da carteira em questão está ativo.


#### 1.1.3. Cartão (Card)


##### 1.1.3.1. Descrição

A entidade Cartão (internamente ao sistema, identificada como "card") é a entidade que representa os cartões de pagamento. Será possível criar cartões do tipo crétido ou débito, não sendo permitido cartão do tipo débito e crédito. O Cartão sempre será relacionada a uma entidade Carteira, de onde os valores movimentados pelo Cartão serão subtraídos. Ex.: Considere um Cartão de nome "NuBank débito", e que está relacionado a Carteira "NuBank". Considere também uma compra feita de R$ 10,00, e que foi paga com esse cartão. Nesse caso, a Carteira que será relacionada a venda, e portanto, de onde será subtraído o valor da transação, será a de nome "NuBank".


##### 1.1.3.2. Atributos da entidade

- carteira (wallet_id):
    - objetivo:             Manter o código de identificação da Carteira a qual o cartão pertence;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
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
    - tipo dado:            Booleano;
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
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #2 e #3).


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
    - não permite valor nulo.
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

- Caracteristica #4: Não é permitido que dois Cartões possuam o mesmo nome;

- Caracteristica #5: Quando uma entidade Cartão é criada, sua primeira Fatura (mais sobre a entidade Fatura no item 1.1.4) é criada automaticamente. Além de ser adicionado (o Cartão) a Rotina diária de fechamento/criação de faturas.


#### 1.1.4. Fatura (Credit Card Dates)


##### 1.1.4.1. Descrição

A entidade Fatura (internamente ao sistema, identificada como "credit_card_dates") é a entidade que representa a fatura dos cartões de crédito. Ela mantém os períodos de início e fim de cada fatura, assim como a data de vencimento e o seu valor.


##### 1.1.4.2. Atributos da entidade

- cartão (card_id):
    - objetivo:             Manter o código de identificação do Cartão a qual a fatura pertence;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do código);
    - alteração:            Não permitida.
- data de início (start_date): 
    - objetivo:             Mantém o primeiro dia da fatura;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Não permitida.
- data final (end_date): 
    - objetivo:             Mantém o último dia da fatura;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Não permitida.
- data de vencimento (due_date): 
    - objetivo:             Mantém a data de vencimento da fatura;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #10).
- valor (value): 
    - objetivo:             Registra o valor total da fatura;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              000000.00;
    - alteração:            Permitida.
- pago (paid):
    - objetivo:             Define se a Fatura em questão está quitada ou não;
    - obrigatório:          Não;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #5).
- estado (status):
    - objetivo:             Definir o estado de uma fatura (Aberta, Fechada, Quitada ou Vencida);
    - obrigatório:          Sim;
    - tipo de dado:         Alfanumérico;
    - valores aceitos:      "Aberta", "Fechada", "Quitada" ou "Vencida";
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #3).


##### 1.1.4.3. Banco de dados

Nome da tabela: credit_card_dates

- card_id: Referente ao atributo "cartão". Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - não permite valor nulo.
- start_date: Referente ao atributo "data de início". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- end_date: Referente ao atributo "data final". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- due_date: Referente ao atributo "data de vencimento". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- value: Referente ao atributo "valor". Terá as seguintes características:
    - tipo: double;
    - tamanho: 8 sendo 2 casas decimais.
- paid: Referente ao atributo "pago". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - card_id faz referência ao atributo "id" da entidade "card"


##### 1.1.4.4. Características da entidade

- Caracteristica #1: Os registros das faturas serão gerados exclusivamente pelo sistema. Uma rotina diária verificará a necessidade de fechamento e criação de faturas;

- Caracteristica #2: Somente registros das faturas antigas e da atual serão mantidos, faturas futuras não devem ser salvas, uma vez que não a garantia sobre suas datas;

- Caracteristica #3: As faturas terão quatro "estados": Aberta, Fechada, Quitada e Vencida. Mais detalhes sobre estes estados na Tarefa #1 (item 1.1.4.5);

- Caracteristica #4: Não é permitido a exclusão de uma fatura, ou a alteração de seus dados (exceções: características #5, #10 e #11);

- Caracteristica #5: Somente faturas marcadas como "Fechada" serão liberadas para pagamento;

- Caracteristica #6: O valor da data de vencimento da fatura deve ser maior que a sua data final, assim como a data final da fatura deve ser maior que a sua data de início;

- Caracteristica #7: Quanto ao valor da data de início de uma fatura, caso não hajam faturas anteriores, deve-se calcular a mesma considerando o valor do atributo "primeiro dia do mês" do cartão relacionado (mais detalhes na tarefa #2, item 1.1.4.5). Caso já existam faturas anteriores, então a data de início da fatura (que está sendo criada) deve ser o dia seguinte a data final da última fatura informada;

- Caracteristica #8: Quanto ao valor da data final de uma fatura, seu valor deve ser o dia anterior ao dia de início da próxima fatura. Ver a tarefa #3 (item 1.1.4.5) para mais detalhes;

- Caracteristica #9: O valor padrão da data de vencimento será calculada somando o atributo "dias para o vencimento" do cartão relacionado, a data final da fatura. Um exemplo foi apresentado na Tarefa #3 (item 1.1.4.5);

- Caracteristica #10: É permitido a alteração da data de vencimento pelo usuário, mas somente se a fatura estiver marcada como "Aberta" ou "Fechada";

- Caracteristica #11: O valor da fatura será calculado pelo sistema, e será recalculado a cada inserção de uma transação relacionada a esta fatura. É vetado a alteração a valor da fatura pelo usuário;


##### 1.1.4.5. Tarefas

Tarefa #1: Definir o status da fatura.
> Caso a data atual for inferior a data final da fatura, a fatura será definida como "Aberta".
> Exemplo de fatura aberta: 
> - Data no momento da verificação: 25/05/2023;
> - Data de início da fatura: 05/05/2023;
> - Data final da fatura: 04/06/2023;
> - Data de vencimento da fatura: 12/06/2023.
> 
> Caso a data atual for superior a data final da fatura, porém, inferior a data de vencimento da fatura, ela será definida como "Fechada".
> Exemplo de fatura fechada: 
> - Data no momento da verificação: 07/06/2023;
> - Data de início da fatura: 05/05/2023;
> - Data final da fatura: 04/06/2023;
> - Data de vencimento da fatura: 12/06/2023.
> 
> Caso a data atual for superior a data de vencimento da fatura, e a mesma não estiver sido pagar, ela será definida como "Vencida".
> Exemplo de fatura vencida: 
> - Data no momento da verificação: 17/06/2023;
> - Data de início da fatura: 05/05/2023;
> - Data final da fatura: 04/06/2023;
> - Data de vencimento da fatura: 12/06/2023;
> - Fatura não paga.
> 
> Caso a data atual for superior a data final da fatura, a mesma poderá ser paga, e então, será definida como "Quitada".
> Exemplo de fatura quitada: 
> - Data no momento da verificação: 10/06/2023;
> - Data de início da fatura: 05/05/2023;
> - Data final da fatura: 04/06/2023;
> - Data de vencimento da fatura: 12/06/2023;
> - Fatura paga.
> 

Tarefa #2: Cálculo da data de início de uma fatura, quando não há faturas anteriores.
> Será pego o último "primeiro dia do mês" (atributo do cartão relacionado a fatura) anterior a data atual, e será definido como a data de início da fatura.
> Considere os seguintes dados para o primeiro exemplo:
> - Valor do atributo "primeiro dia do mês" do cartão relacionado: 5;
> - Data atual: 15/05/2023.
> Nesse caso, como o "primeiro dia do mês" (5) é menor que o dia da data atual (15), mantem-se o mês e o ano (05/2023) e altera-se o dia para o mesmo valor de "primeiro dia do mês" (05), logo, a data de início da nova fatura será 05/05/2023.
> 
> Considere os seguintes dados para o segundo exemplo:
> - Valor do atributo "primeiro dia do mês" do cartão relacionado: 5;
> - Data atual: 05/05/2023.
> Nesse caso, como o "primeiro dia do mês" (5) é igual ao dia da data atual (5), a data de início da nova fatura será o mesmo que a data atual, ou seja, dia 05/05/2023.
> 
> Para o terceiro exemplo, considere os seguintes dados:
> - Valor do atributo "primeiro dia do mês" do cartão relacionado: 15;
> - Data atual: 10/05/2023.
> Nesse caso, como o "primeiro dia do mês" (15) é maior que o dia da data atual (10), pega-se o mês anterior ao atual (04/2023) e altera-se o dia para o mesmo valor de "primeiro dia do mês" (05), logo, a data de início da nova fatura será 15/04/2023.
> 

Tarefa #3: Cálculo da data final de uma fatura.
> A data final da fatura será sempre o dia anterior ao da data de inicio da próxima fatura, que será calculado considerando o atributo "primeiro dia do mês" do cartão relacionado. Para o cálculo da data de início da próxima fatura, considere o próximo "primeiro dia do mês" (atributo do cartão relacionado a fatura) posterior a data atual.
> Considere os seguintes dados para o primeiro exemplo:
> - Valor do atributo "primeiro dia do mês" do cartão relacionado: 5;
> - Data atual: 15/05/2023.
> Nesse caso, como o "primeiro dia do mês" (5) é menor que o dia da data atual (15), pega-se o próximo mês (06/2023) e altera-se o dia para o mesmo valor de "primeiro dia do mês" (05), para se encontrar a data de início da próxima fatura, ou seja, dia 05/06/2023. Com essa data em mãos, basta calcular o dia anterior a ela para se econtrar a data final da fatura atual, ou seja, dia 04/06/2023.
> 
> Considere os seguintes dados para o segundo exemplo:
> - Valor do atributo "primeiro dia do mês" do cartão relacionado: 5;
> - Data atual: 05/05/2023.
> Nesse caso, como o "primeiro dia do mês" (5) é igual ao dia da data atual (5), soma-se um mês a data atual para encontrar a data de início da próxima fatura, que nesse exemplo será 05/06/2023. Com essa data em mãos, basta calcular o dia anterior a ela para se econtrar a data final da fatura atual, ou seja, dia 04/06/2023.
> 
> Para o terceiro exemplo, considere os seguintes dados:
> - Valor do atributo "primeiro dia do mês" do cartão relacionado: 15;
> - Data atual: 10/05/2023.
> Nesse caso, como o "primeiro dia do mês" (15) é maior que o dia da data atual (10), mantem-se o mês e o ano (05/2023) e altera-se o dia para o mesmo valor de "primeiro dia do mês" (15), logo, a data de início da próxima fatura será 15/05/2023. Com essa data em mãos, basta calcular o dia anterior a ela para se econtrar a data final da fatura atual, ou seja, dia 14/05/2023.
> 

Tarefa #4: Cálculo da data de vencimento de uma fatura.
> O cálculo da data de vencimento da fatura deve ser feito pegando a data final da fatura, e somando o valor do atributo "dias para o vencimento" do cartão relacionado. 
> Ex.: Caso a quantidade de dias até o vencimento seja 10, e a data final da fatura seja dia 15/05/2023, então a data de vencimento da mesma será dia 25/05/2023.
> 


#### 1.1.5. Método de Pagamento (Payment Method)


##### 1.1.5.1. Descrição

A entidade Método de Pagamento (internamente ao sistema, identificada como "payment method") é a entidade que representa os métodos de pagamento utilizados em cada transação, como por exemplo "Crédito", "Débito" e "Transferência".


##### 1.1.5.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- tipo (type):
    - objetivo:             Definir se a entidade se refe a transações feitas por cédulas, transações bancárias ou cartão (débito ou crédito);
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - valores aceitos:      0 (para cédulas), 1 (para transações bancárias) ou 2 (para cartões);
    - alteração:            Não permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #3). (se não hover transações marcadas como).


##### 1.1.5.3. Banco de dados

Nome da tabela: payment_method.

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
    - valor único.
- type: Referente ao atributo "tipo". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo.
- active: Referente ao atributo "ativo". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária:
    - id


##### 1.1.5.4. Características da entidade

- Caracteristica #1: Não é permitido que dois Métodos de Pagamento possuam o mesmo nome;

- Caracteristica #2: Não é permitido a alteração dos atributos da entidade, exceto o atributo ativo (ver caracteristica #3 para mais detalhe);

- Caracteristica #3: Não é permitido a exclusão de um registro de Método de Pagamento, apenas sua inativação. Sendo que a inativação de um registro só poderá ser feita se o mesmo não estiver relacionado a nenhum outro registro.

- Caracteristica #4: Caso seja necessário inativar um registro que esteja relacionado a alguma Transação (ver mais sobre a entidade Transação no item 1.1.7), será preciso "atualizar" as Transações que utilizam aquele Método de Pagamento, para um outro Método de Pagamento ativo, que tenha o mesmo valor para o atributo "tipo".


#### 1.1.6. Tipo de Transação (Transaction Type)


##### 1.1.6.1. Descrição

A entidade Tipo de Transação (internamente ao sistema, identificada como "transaction type") é a entidade que representa os tipos de transação. Será utilizado como forma de organizar as transações em grupos a critérios do usuário. Possíveis registros seriam "vendas", "compras", "empréstimos", "mensalidade", etc...


##### 1.1.6.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 45 caracteres;
    - alteração:            Não permitida.
- relevancia (relevance):
    - objetivo:             Definir a relevancia padrão da transação ao qual esse registro é relacionado;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - valores aceitos:      0 (não relevante), 1 (pouco relevante) ou 2 (relevante);
    - alteração:            Permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristica #3). (se não hover transações marcadas como).


##### 1.1.6.3. Banco de dados

Nome da tabela: transaction_type.

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - auto incremento;
    - não permite valor nulo;
    - chave primaria.
- name: Referente ao atributo "nome". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 45;
    - não permite valor nulo;
    - valor único.
- relevance: Referente ao atributo "relevancia". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo.
- active: Referente ao atributo "ativo". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária:
    - id


##### 1.1.6.4. Características da entidade

- Caracteristica #1: Não é permitido que dois Tipos de Transação possuam o mesmo nome;

- Caracteristica #2: Não é permitido a alteração dos atributos da entidade, exceto o atributo ativo (ver caracteristica #3 para mais detalhe);

- Caracteristica #3: Não é permitido a exclusão de um registro de Tipo de Transação, apenas sua inativação. Sendo que a inativação de um registro só poderá ser feita se o mesmo não estiver relacionado a nenhum outro registro.

- Caracteristica #4: Caso seja necessário inativar um registro que esteja relacionado a alguma Transação (ver mais sobre a entidade Transação no item 1.1.7), será preciso "atualizar" as Transações que utilizam aquele Tipo de Transação para um outro Tipo de Transação ativo.


#### 1.1.7. Transação (Transaction)


##### 1.1.7.1. Descrição

A entidade Transação (internamente ao sistema, identificada como "transaction") é utilizada (junto com a entidade "installment", item 1.1.8) para representar as diversas transações salvas no sistema.


##### 1.1.7.2. Atributos da entidade

- titulo (tittle):
    - objetivo:             Manter o nome pelo qual a Transação será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 50 caracteres;
    - alteração:            Não permitida.
- data da transação (transaction_date):
    - objetivo:             Manter a data quando a a Transação foi efetuada;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #2).
- data de processamento (processing_date):
    - objetivo:             Manter a data em que a transação foi processada. Será útil para os casos de compras no cartão, onde nem sempre a transação é processada no mesmo dia em que a transação foi efetuada;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #3).
- tipo da transação (transaction_type):
    - objetivo:             Manter o código de identificação do Tipo da Transação;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do código);
    - alteração:            Permitida.
- valor bruto (gross_value): 
    - objetivo:             Registrar o valor total da transação, no momento que esta é efetuada (não considera descontos ou arredondamentos);
    - obrigatório:          Sim;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #4).
- valor de desconto (discount_value): 
    - objetivo:             Registrar o valor do desconto dado a transação, no momento que ela é efetuada (não considera descontos aplicados posteriormente nas parcelas);
    - obrigatório:          Sim;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver caracteristicas #5).





- relevance: Define a relevancia da transação ao qual esse registro é relacionado. Particularidades:
    - Preenchimento obrigatório;
    - Deverá ser informado no momento do cadastro da entidade;
    - Por padrão, replicará o valor da entidade "transaction type" relacionada a transação, porém, será possível a escolha de um valor diferente;
    - Deverá aceitar somente os valores "0", "1" ou "2".
- description: Atributo utilizado para que seja possível salvar uma pequena descrição sobre o registro. Particularidades:
    - Deverá possuir no máximo 255 caracteres;
    - Não será permitido caracteres especiais (exceto: ).





##### 1.1.7.3. Banco de dados
##### 1.1.7.4. Características da entidade

- Caracteristica #1: Para a excluir uma Transação será necessário ver se tal ação é permitida através da Tarefa #1 (item 1.1.7.5);

- Caracteristica #2: Para alterar o valor do atributo "Data da Transação" de uma Transação, será preciso ver se tal ação é permitida através da Tarefa #1 (item 1.1.7.5);

- Caracteristica #3: Para alterar o valor do atributo "Data de Processamento" de uma Transação, será preciso ver se tal ação é permitida através da Tarefa #1 (item 1.1.7.5);

- Caracteristica #4: Para alterar o valor do atributo "Valor Bruto" de uma Transação, será preciso ver se tal ação é permitida através da Tarefa #1 (item 1.1.7.5);

- Caracteristica #5: Para alterar o valor do atributo "Valor de Desconto" de uma Transação, será preciso ver se tal ação é permitida através da Tarefa #1 (item 1.1.7.5);

- Caracteristica #6: Quando uma Transação for salva, ou algum de seus valores (Valor Bruto e/ou Valor de Desconto) for alterado, deverá ser confirmado se o total da Transação confere com o Total das Parcelas (Tarefa #2, item 1.1.7.5). Se houver inconsistências nos valores, e o usuário permitir, os valores das Parcelas devem ser recalculados;


##### 1.1.7.5. Tarefas

Tarefa #1: Definir se a Transação está liberada para exclusão ou alteração.
> Buscar todas as Parcelas da Transação em questão, e confirmar se nenhuma delas pertence a uma Fatura que esteja marcada como "Vencida" ou "Quitada".
> Para as alterações nos atritutos "Data da Transação" e "Data de Processamento", deve-se confirmar também se o novo valor não é pertencente a uma Fatura que esteja marcada como "Vencida" ou "Quitada", evitando assim que a Transação seja jogada para uma Fatura já finalizada
> 

Tarefa #2: Definir se o valor das Parcelas confere com o valor da Transação.
> Para a confirmação dessa informação, vamos considerar os seguintes valores:
> - Valor da Transação: Será calculado subtraindo o Valor do Desconto da Transação do Valor Bruto da Transação.
> - Valor das Parcelas: Será calculado somando os atributos Valor Bruto de cada Parcela pertencente a essa Transação.
> Se os dois valores (Valor da Transação e Valor das Parcelas) forem iguais, então os valores da transação estão corretos.
> 


Tarefa #4: Deve confirmar se o valor da transação (Tarefa #1) confere com o valor das parcelas (Tarefa #3). Essa verificação deve ser feita sempre que um cadastro novo for inserido, assim como quando houver alterações nos valores das parcelas ou da transação





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