Olá

Meu nome é Rafael, sou formado em Ciência da Computação desde 2015 e trabalho com desenvolvimento desde 2018, e esse é meu projeto de apresentação (um deles).

Comecei esse projeto já a tanto tempo que nem lembro mais, e nesse meio tempo modifiquei ele outras tantas vezes pelos mais diversos motivos, treinar linguagens de programações novas, frameworks novos, mudanças de funcionalidades... quase sempre começando um novo projeto do zero, por isso meu github possui tantos projetos com o mesmo propósito (mas espero que quando estiver lendo isso, eu já tenha me organizado quanto a eles). Por fim, optei por consolidar meus conhecimentos em PHP, por isso não utilizei nenhum framework nesse projeto, assim como também optei por criá-lo como uma API, e desta forma não sendo necessário utilizar tecnologias de front-end. 

## 1. Sobre o projeto

A ideia principal do sistema é criar uma API que seja capaz de gerenciar as finanças pessoais de uma determinada pessoa. Cadastro de compras, salário, empréstimos, geração de relatório de dívidas, previsão de gastos e entradas de valores, etc... A seguir, detalharei melhor cada função.

Obs.: Optei por utilizar os nomes de entidades, atributos e funções em inglês, pois notei que esse é o padrão utilizado na maioria dos projetos. Por esse motivo, nomeei as entidades como "owner", "wallet" e "transaction" ao invés de "pessoa", "carteira" e "transação". No entanto, para essa documentação, escolhi também manter alguns nomes em português, pois acredito que isso facilitará a compreensão do funcionamento do sistema. Por exemplo, dessa forma posso descrever algo como "O título da transação identificará a mesma" ao invés de "O valor do atributo 'tittle' da entidade 'transaction' identificará a mesma". Outra questão sobre a decisão de manter os nomes em inglês é que pode levar a algumas complicações, como não encontrar uma tradução, ou achar, mas não ser precisa como na versão em português. Como por exemplo "boleto", que não encontrei uma tradução, e "fatura", que devido a dúvidas na precisão da tradução, optei por dar um nome mais genérico baseado em características da entidade (credit_card_dates).


### 1.1. Entidades


#### 1.1.1. Pessoa \ Pessoa Responsável (Owner)


#### 1.1.1.1. Descrição

A entidade *Pessoa* (internamente ao sistema, identificada como "owner") é a entidade que representa cada pessoa (física ou jurídica) a qual será atribuída a propriedade de determinadas transações, assim como dos valores dessas transações. Por exemplo, caso o usuário de nome "Rafael" opte por cadastrar uma transação de depósito referente a um pagamento dele para outra pessoa, de nome "Marcos", este usuário deverá possuir dois cadastros de *Pessoa*, um para ele próprio (o qual será criado junto a conta no sistema) e outro para o destinatário do valor. Dessa forma, o sistema saberá que o valor foi transferido de uma pessoa para outra, e poderá calcular os novos valores após a transação.


#### 1.1.1.2. Atributos da entidade

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
    - alteração:            Permitida em algumas circunstâncias (ver característica #3).


#### 1.1.1.3. Banco de dados

Nome da tabela: owner.

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - auto incremento;
    - não permite valor nulo;
    - chave primária.
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


#### 1.1.1.4. Características da entidade

- Característica #1: Não é permitido a exclusão de um registro de *Pessoa*, apenas sua inativação;

- Característica #2: Não é permitido que duas *Pessoa*s possuam o mesmo nome;

- Característica #3: Não é permitido a inativação de uma *Pessoa* que possua pendências (Tarefa #1, item 1.1.1.5);

- Característica #4: É exigido a existência de pelo menos uma *Carteira* (mais sobre a entidade *Carteira* no item 1.1.2) para cada *Pessoa* (Tarefa #2, item 1.1.1.5).

- Característica #5: Quando uma *Pessoa* é inativada, suas *Carteira*s também deverão ser inativadas. Mesmo que uma *Pessoa* esteja inativa, os registros referentes a ela ainda serão mantidos


#### 1.1.1.5. Tarefas

Tarefa #1: Validar se existem "pendências" para uma determinada *Pessoa*.
> Buscar por todas as transações que estão em aberto para esta *Pessoa*, como débitos e empréstimos não devolvidos.

Tarefa #2: Garantir que toda *Pessoa* possua pelo menos uma *Carteira* (ver o item 1.1.2 para mais detalhes).
> Criar uma *Carteira* automaticamente quando uma *Pessoa* é criada. A *Carteira* deve ser marcada como de posse da *Pessoa* em questão.

Tarefa #3: Identificar a *Carteira* principal de uma *Pessoa*.
> Buscar a *Carteira* relacionada a *Pessoa* em questão, que esteja marcada como a principal.


#### 1.1.1.6. Valores pré cadastrados

Na implantação do sistema, os seguintes registros devem ser cadastrados nesta tabela (owner):

| Registro | id  | name              | active | Objetivo                                                                   |
| :------: | :-: | :---------------- | :----: | :------------------------------------------------------------------------- |
|       #1 |  1  | Sistema           |      1 | Será o "Dono" utilizado em movimentações de destino indefinido (ou origem) |
|       #2 |  2  | (Nome do usuário) |      1 | Será o "Dono" relacionado o usuário do sistema                             |

> Obs. 1: Como o atributo *id* é auto incrementado, cuidar para que na inserção dos valores, o valor aqui definido seja respeitado;

> Obs. 2: O valor do atributo *active* pode ser **1** ou **true**, dependendo do banco de dados utilizado.


#### 1.1.2. Carteira (wallet)


#### 1.1.2.1. Descrição

A entidade *Carteira* (internamente ao sistema, identificada como "wallet") é a entidade que representa os locais onde os valores estão armazenados, como contas em bancos ou mesmo a carteira pessoal do usuário. Será possível que uma *Pessoa* tenha mais de uma *Carteira*. Por exemplo, o usuário "Rafael" poderá cadastrar três *Carteira*s, de nomes "Conta Corrente", "Carteira" e "Poupança", e dessa forma ele poderá separar os valores que estão em sua conta corrente dos valores que estão em sua poupança e do dinheiro que ele possui em sua carteira pessoal.


#### 1.1.2.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- dono (owner_id):
    - objetivo:             Manter o código de identificação do dono (*Pessoa*) desta entidade;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Não permitida.
- carteira principal (main_wallet):
    - objetivo:             Definir se dentre todas as *Carteira*s de uma *Pessoa*, esta é a principal delas;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver característica #3).
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
    - alteração:            Permitida em algumas circunstâncias (ver características #4 e #5).


#### 1.1.2.3. Banco de dados

Nome da tabela: wallet

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primária.
- name: Referente ao atributo "nome". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 30;
    - não permite valor nulo.
- owner_id: Referente ao atributo "dono". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
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


#### 1.1.2.4. Características da entidade

- Característica #1: Não é permitida a exclusão de um registro de *Carteira*, apenas sua inativação;

- Característica #2: Quando for a única *Carteira* de uma *Pessoa*, ela será obrigatoriamente marcada como a carteira principal (Tarefa #1, item 1.1.2.5);

- Característica #3: Quando uma *Carteira* é marcada como principal, as demais *Carteira*s (da mesma *Pessoa*) são automaticamente desmarcadas como tal (Tarefa #2, item 1.1.2.5);

- Característica #4: Não é permitida a inativação de uma *Carteira* que esteja marcada como principal (como quando esta for a única), que tenha valores (Tarefa #3, item 1.1.2.5) ou que possua pendências (Tarefa #4, item 1.1.2.5);

- Característica #5: Não é permitida a reativação de uma *Carteira* cujo dono (*Pessoa*) estiver inativo (Tarefa #5, item 1.1.2.5).


#### 1.1.2.5. Tarefas

Tarefa #1: Garantir a existência de pelo menos uma *Carteira* principal para cada *Pessoa*.
> Buscar todas as *Carteira*s relacionadas a *Pessoa* em questão, caso não existe nenhuma, a *Carteira* que estiver sendo salva será marcada como sendo a principal.

Tarefa #2: Garantir a existência de uma única *Carteira* principal para cada *Pessoa*.
> Buscar todas as *Carteira*s relacionadas a *Pessoa* em questão, que estejam marcadas como principal. Caso seja encontrado alguma *Carteira*, a mesma será desmarcada como principal. Esse procedimento será executado antes de se salvar um registro novo de *Carteira*, ou ao marcar um registro antigo como principal.

Tarefa #3: Buscar o valor total presente em uma determinada *Carteira*.
> Buscar por todas as transações (já quitadas) relacionadas a uma *Carteira*, e efetuar o somatório destes valores (valores de entradas menos valores de saída).

Tarefa #4: Verificar a existência de valores pendentes (crédito, empréstimos e transações agendadas) para uma determinada *Carteira*.
> Buscar por todas as transações pendentes (crédito, empréstimos e transações agendadas...) relacionadas a uma *Carteira*.

Tarefa #5: Garantir que uma *Carteira* não seja ativada quando seu usuário estiver desativado.
> Confirmar se o dono (*Pessoa*) da *Carteira* em questão está ativo.


#### 1.1.2.6. Valores pré cadastrados

Na implantação do sistema, os seguintes registros devem ser cadastrados nesta tabela (wallet):

| Registro | id  | name                      | owner_id | main_wallet | description                                                                                                                                  | active | Objetivo                                                                                                                                     |
| :------: | :-: | :------------------------ | :------: | :---------: | :------------------------------------------------------------------------------------------------------------------------------------------- | :----: | :------------------------------------------------------------------------------------------------------------------------------------------- |
| #1       | 1   | Origem/Destino Indefinido | 1        | 1           | Carteira utilizada para movimentações de origem indefinida (como recebimento de salário) ou destino indefinido (como pagamento de uma venda) | 1      | Carteira utilizada para movimentações de origem indefinida (como recebimento de salário) ou destino indefinido (como pagamento de uma venda) |
| #2       | 2   | Casa                      | 2        | 1           | Carteira padrão do usuário                                                                                                                   | 1      | Carteira padrão do usuário. Poderá ser inativada posteriormente se novas carteiras forem criadas                                            |

> Obs. 1: Como o atributo *id* é auto incrementado, cuidar para que na inserção dos valores, o valor aqui definido seja respeitado;

> Obs. 2: O valor do atributo *active* pode ser **1** ou **true**, dependendo do banco de dados utilizado;

> Obs. 3: Como se trata de uma referência a uma entidade Dono, o valor do atributo *owner_id* do registro #1 deve ser o mesmo do atributo *id* do registro #1 na tabela owner.

> Obs. 4: Como se trata de uma referência a uma entidade Dono, o valor do atributo *owner_id* do registro #2 deve ser o mesmo do atributo *id* do registro #2 na tabela owner.


#### 1.1.3. Cartão (Card)


#### 1.1.3.1. Descrição

A entidade *Cartão* (internamente ao sistema, identificada como "card") é a entidade que representa os cartões de pagamento. Será possível criar um registro *Cartão* do tipo crédito ou débito, mas não será permitido um que possua as duas funções. O *Cartão* sempre será relacionada a uma entidade *Carteira*, de onde os valores movimentados pelo *Cartão* serão subtraídos. Ex.: Considere um *Cartão* de nome "NuBank débito", e que está relacionado a *Carteira* "NuBank". Considere também uma compra feita de R$ 10,00, e que foi paga com esse *Cartão*. Nesse caso, a *Carteira* que será relacionada à venda, e portanto, de onde será subtraído o valor da transação, será a de nome "NuBank".


#### 1.1.3.2. Atributos da entidade

- carteira (wallet_id):
    - objetivo:             Manter o código de identificação da *Carteira* a qual o cartão pertence;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Não permitida.
- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 20 caracteres;
    - alteração:            Não permitida.
- crédito (credit):
    - objetivo:             Define se o *Cartão* é do tipo "crédito";
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
    - objetivo:             Será utilizado para o cálculo do vencimento da fatura. O valor informado aqui será acrescido (em dias) a data do fechamento da fatura;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              Valores de 1 até 20;
    - alteração:            Permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver características #2 e #3).


#### 1.1.3.3. Banco de dados

Nome da tabela: card

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 4;
    - auto incremento;
    - não permite valor nulo;
    - chave primária.
- wallet_id: Referente ao atributo "carteira". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
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


#### 1.1.3.4. Características da entidade

- Característica #1: Não é permitida a exclusão de um registro de *Carteira*, apenas sua inativação;

- Característica #2: É permitida a inativação de um *Cartão*, porém, isso não afeta suas fatura, que permanecerão em aberto até que sejam quitadas;

- Característica #3: Não é permitida a reativação de um *Cartão*;

- Característica #4: Não é permitido que duas entidades *Cartão* possuam o mesmo nome;

- Característica #5: Quando uma entidade *Cartão* é criada, sua primeira *Fatura* (mais sobre a entidade *Fatura* no item 1.1.4) é criada automaticamente. Além de ser adicionado (o *Cartão*) a Rotina diária de fechamento/criação de faturas.


#### 1.1.4. Fatura (Credit Card Dates)


#### 1.1.4.1. Descrição

A entidade *Fatura* (internamente ao sistema, identificada como "credit_card_dates") é a entidade que representa a fatura dos cartões de crédito. Ela mantém os períodos de início e fim de cada fatura, assim como a data de vencimento e o seu valor.


#### 1.1.4.2. Atributos da entidade

- cartão (card_id):
    - objetivo:             Manter o código de identificação do *Cartão* a qual a fatura pertence;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
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
    - alteração:            Permitida em algumas circunstâncias (ver característica #10).
- valor (value): 
    - objetivo:             Registra o valor total da fatura;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              000000.00;
    - alteração:            Permitida.
- estado (status):
    - objetivo:             Definir o estado de uma fatura (Aberta, Fechada, Quitada ou Vencida);
    - obrigatório:          Sim;
    - tipo de dado:         Alfanumérico;
    - valores aceitos:      "Aberta", "Fechada", "Quitada" ou "Vencida";
    - alteração:            Permitida em algumas circunstâncias (ver características #3).


#### 1.1.4.3. Banco de dados

Nome da tabela: credit_card_dates

- card_id: Referente ao atributo "cartão". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
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
- status: Referente ao atributo "estado". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.

- chave primária: 
    - id
- chave estrangeira: 
    - card_id faz referência ao atributo "id" da tabela "card"


#### 1.1.4.4. Características da entidade

- Característica #1: Os registros das *Fatura*s serão gerados exclusivamente pelo sistema. Uma rotina diária verificará a necessidade de fechamento e criação de *Fatura*s;

- Característica #2: Somente registros das *Fatura*s antigas e da atual serão mantidos, *Fatura*s futuras não devem ser salvas, uma vez que não a garantia sobre suas datas;

- Característica #3: As *Fatura*s terão quatro "estados": Aberta, Fechada, Quitada e Vencida. Mais detalhes sobre estes estados na Tarefa #1 (item 1.1.4.5);

- Característica #4: Não é permitido a exclusão de uma *Fatura*. Sua alteração pode ocorrer somente nos casos descritos nas Características #10 e #11;

- Característica #5: Somente *Fatura*s marcadas como "Fechada" serão liberadas para pagamento;

- Característica #6: O valor da *data de vencimento* da *Fatura* deve ser maior que a sua *data final*, assim como a *data final* da *Fatura* deve ser maior que a sua *data de início*;

- Característica #7: Quanto ao valor da *data de início* de uma fatura, caso não hajam faturas anteriores, deve-se calcular a mesma considerando o valor do atributo *primeiro dia do mês* do *Cartão* relacionado (mais detalhes na tarefa #2, item 1.1.4.5). Caso já existam *Fatura*s anteriores, então a *data de início* da *Fatura* (que está sendo criada) deve ser o dia seguinte a *data final* da última *Fatura* informada;

- Característica #8: Quanto ao valor da *data final* de uma *Fatura*, seu valor deve ser sempre o dia anterior a *data de início* da próxima *Fatura*. Ver a tarefa #3 (item 1.1.4.5) para mais detalhes;

- Característica #9: O valor padrão da *data de vencimento* será calculada somando o atributo *dias para o vencimento* do *Cartão* relacionado, a *data final* da *Fatura*. Um exemplo foi apresentado na Tarefa #3 (item 1.1.4.5);

- Característica #10: É permitido a alteração da *data de vencimento* pelo usuário, mas somente se a *Fatura* estiver marcada como "Aberta" ou "Fechada";

- Característica #11: O valor da *Fatura* será calculado pelo sistema, e será recalculado a cada inserção de uma transação relacionada a esta *Fatura*. É vetado a alteração a valor da *Fatura* pelo usuário;

- Característica #12: Quando uma *Fatura* é criada, ela deve recalcular o atributo *data da parcela* (ver mais sobre Parcela no item 1.1.8) das *Parcela*s que passarem a pertencer a essa *Fatura* recém criada (ver Tarefa #5, item 1.1.4.5);

- Característica #13: Toda *Fatura* está relacionada uma única *Carteira*, já que toda *Fatura* pertence a um *Cartão*, e todo *Cartão* está relacionado a uma única *Carteira*. No entanto, uma *Carteira* pode possuir mais de uma *Cartão* e por isso, mais de uma *Fatura*.

- Característica #14: Para que o pagamento de uma *Fatura* seja permitida, no momento do seu pagamento a *Carteira* a qual ela pertence deverá possuir um valor igual ou maior ao *valor* da *Fatura*.


#### 1.1.4.5. Tarefas

Tarefa #1: Definir o status da *Fatura*.
> Caso a data atual for inferior a *data final* da *Fatura*, a *Fatura* será definida como "Aberta".
> Exemplo de *Fatura* aberta: 
> - Data no momento da verificação: 25/05/2023;
> - *Data de início* da *Fatura*: 05/05/2023;
> - *Data final* da *Fatura*: 04/06/2023;
> - *Data de vencimento* da *Fatura*: 12/06/2023.
> 
> Caso a data atual for superior a *data final* da *Fatura*, porém, inferior a *data de vencimento* da *Fatura*, ela será definida como "Fechada".
> Exemplo de *Fatura* fechada: 
> - Data no momento da verificação: 07/06/2023;
> - *Data de início* da *Fatura*: 05/05/2023;
> - *Data final* da *Fatura*: 04/06/2023;
> - *Data de vencimento* da *Fatura*: 12/06/2023.
> 
> Caso a data atual for superior a *data de vencimento* da *Fatura*, e a mesma não estiver paga, ela será definida como "Vencida".
> Exemplo de *Fatura* vencida: 
> - Data no momento da verificação: 17/06/2023;
> - *Data de início* da *Fatura*: 05/05/2023;
> - *Data final* da *Fatura*: 04/06/2023;
> - *Data de vencimento* da *Fatura*: 12/06/2023;
> - *Fatura* não paga.
> 
> Caso a data atual for superior a *data final* da *Fatura*, a mesma poderá ser paga, e então, será definida como "Quitada".
> Exemplo de *Fatura* quitada: 
> - Data no momento da verificação: 10/06/2023;
> - *Data de início* da *Fatura*: 05/05/2023;
> - *Data final* da *Fatura*: 04/06/2023;
> - *Data de vencimento* da *Fatura*: 12/06/2023;
> - *Fatura* paga.
> 

Tarefa #2: Cálculo da *data de início* de uma *Fatura*, quando não há *Fatura*s anteriores.
> Será pego o último *primeiro dia do mês* (atributo do *Cartão* relacionado a *Fatura*) anterior a data atual, e será definido como a *data de início* da *Fatura*.
> 
> Para o primeiro exemplo, considere os seguintes dados:
> - Valor do atributo *primeiro dia do mês* do *Cartão* relacionado: 5;
> - Data atual: 15/05/2023.
> 
> Nesse caso, como o *primeiro dia do mês* (5) é menor que o dia da data atual (15), mantém-se o mês e o ano (05/2023) e altera-se o dia para o mesmo valor de *primeiro dia do mês* (05), logo, a *data de início* da nova *Fatura* será 05/05/2023.
> 
> Para o segundo exemplo, considere os seguintes dados:
> - Valor do atributo *primeiro dia do mês* do *Cartão* relacionado: 5;
> - Data atual: 05/05/2023.
> 
> Nesse caso, como o *primeiro dia do mês* (5) é igual ao dia da data atual (5), a *data de início* da nova *Fatura* será o mesmo que a data atual, ou seja, dia 05/05/2023.
> 
> Para o terceiro exemplo, considere os seguintes dados:
> - Valor do atributo *primeiro dia do mês* do *Cartão* relacionado: 15;
> - Data atual: 10/05/2023.
> 
> Nesse caso, como o *primeiro dia do mês* (15) é maior que o dia da data atual (10), pega-se o mês anterior ao atual (04/2023) e altera-se o dia para o mesmo valor de *primeiro dia do mês* (05), logo, a *data de início* da nova *Fatura* será 15/04/2023.
> 

Tarefa #3: Cálculo da *data final* de uma *Fatura*.
> A *data final* da *Fatura* será sempre o dia anterior ao da *data de início* da próxima *Fatura*, que será calculado considerando o atributo *primeiro dia do mês* do *Cartão* relacionado. Para o cálculo da *data de início* da próxima *Fatura*, considere o próximo *primeiro dia do mês* (atributo do *Cartão* relacionado a *Fatura*) posterior a data atual.
> 
> Para o primeiro exemplo, considere os seguintes dados:
> - Valor do atributo *primeiro dia do mês* do *Cartão* relacionado: 5;
> - Data atual: 15/05/2023.
> 
> Nesse caso, como o *primeiro dia do mês* (5) é menor que o dia da data atual (15), pega-se o próximo mês (06/2023) e altera-se o dia para o mesmo valor de *primeiro dia do mês* (05), para se encontrar a *data de início* da próxima *Fatura*, ou seja, dia 05/06/2023. Com essa data em mãos, basta calcular o dia anterior a ela para se encontrar a *data final* da *Fatura* atual, ou seja, dia 04/06/2023.
> 
> Para o segundo exemplo, considere os seguintes dados:
> - Valor do atributo *primeiro dia do mês* do *Cartão* relacionado: 5;
> - Data atual: 05/05/2023.
> 
> Nesse caso, como o *primeiro dia do mês* (5) é igual ao dia da data atual (5), soma-se um mês a data atual para encontrar a *data de início* da próxima *Fatura*, que nesse exemplo será 05/06/2023. Com essa data em mãos, basta calcular o dia anterior a ela para se encontrar a *data final* da *Fatura* atual, ou seja, dia 04/06/2023.
> 
> Para o terceiro exemplo, considere os seguintes dados:
> - Valor do atributo *primeiro dia do mês* do *Cartão* relacionado: 15;
> - Data atual: 10/05/2023.
> 
> Nesse caso, como o *primeiro dia do mês* (15) é maior que o dia da data atual (10), mantem-se o mês e o ano (05/2023) e altera-se o dia para o mesmo valor de *primeiro dia do mês* (15), logo, a *data de início* da próxima *Fatura* será 15/05/2023. Com essa data em mãos, basta calcular o dia anterior a ela para se encontrar a *data final* da *Fatura* atual, ou seja, dia 14/05/2023.
> 

Tarefa #4: Cálculo da *data de vencimento* de uma *Fatura*.
> O cálculo da *data de vencimento* da *Fatura* deve ser feito pegando a *data final* da *Fatura*, e somando o valor do atributo *dias para o vencimento* do cartão relacionado. 
> <br>Ex.: Caso a quantidade de dias até o vencimento seja 10, e a *data final* da *Fatura* seja dia 15/05/2023, então a *data de vencimento* da mesma será dia 25/05/2023.
> 

Tarefa #5: Redefinir o valor do atributo *data da parcela* (ver mais sobre *Parcela* no item 1.1.8).
> Quando uma nova *Fatura* for criada, as *Parcela*s que passarem a pertencer a essa *Fatura* terão os valores de seus atributos *data da parcela* alterados para o primeiro dia da *Fatura* recém criada.
> <br>Ex.: Considere uma venda efetuada no dia 25/04/2023, e que 4 *Parcela*s foram geradas para essa venda, com os valores do atributo *data da parcela* salvos como "25/04/2023", "25/05/2023", "25/06/2023" e "25/07/2023". Considere também que estamos no dia 05/05/2023, e que uma nova *Fatura* foi criada, indo do dia 05/05/2023 até 04/06/2023.
Nesse caso, a primeira *Parcela* será mantida com o mesmo valor, pois se trata de uma *Fatura* antiga. O mesmo ocorrerá com as *Parcela*s 3 e 4, pois se trata de *Fatura*s ainda não lançadas. A segunda *Parcela*, no entanto, terá o valor de seu atributo *data da parcela* alterado para "05/05/2023", a mesma *data de início* da *Fatura* a qual ela agora pertence
>


#### 1.1.5. Método de Pagamento (Payment Method)


#### 1.1.5.1. Descrição

A entidade *Método de Pagamento* (internamente ao sistema, identificada como "payment method") é a entidade que representa os métodos de pagamento utilizados em cada transação, como por exemplo "Crédito", "Débito" e "Transferência".


#### 1.1.5.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 30 caracteres;
    - alteração:            Não permitida.
- tipo (type):
    - objetivo:             Definir se a entidade se refere a transações feitas por dinheiro físico (cédulas e/ou moedas), transações bancárias, débito ou crédito;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - valores aceitos:      0 (para cédulas e/ou moedas), 1 (para transações bancárias), 2 (para débito) ou 3 (para crédito);
    - alteração:            Não permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver característica #3). (se não houver transações marcadas como).


#### 1.1.5.3. Banco de dados

Nome da tabela: payment_method.

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - auto incremento;
    - não permite valor nulo;
    - chave primária.
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


#### 1.1.5.4. Características da entidade

- Característica #1: Não é permitido que duas entidades *Método de Pagamento* possuam o mesmo nome;

- Característica #2: Não é permitido a alteração dos atributos da entidade, exceto o atributo *ativo* (ver característica #3 para mais detalhe);

- Característica #3: Não é permitido a exclusão de um registro de *Método de Pagamento*, apenas sua inativação. Sendo que a inativação de um registro só poderá ser feita se o mesmo não estiver relacionado a nenhum outro registro.

- Característica #4: Caso seja necessário inativar um registro que esteja relacionado a alguma *Transação* (ver mais sobre a entidade *Transação* no item 1.1.7), será preciso "atualizar" os registros de *Transação* que utilizam aquele *Método de Pagamento*, para um outro *Método de Pagamento* ativo, que tenha o mesmo valor para o atributo "tipo".


#### 1.1.5.5. Valores pré cadastrados

Na implantação do sistema, os seguintes registros devem ser cadastrados nesta tabela (payment_method):

| Registro | id  | name            | type | active | Objetivo                                                                             |
| :------: | :-: | :-------------- | :--: | :----: | :----------------------------------------------------------------------------------- |
| #1       | 1   | Dinheiro físico | 0    | 1      | Método padrão para movimentações feitas em dinheiro físico, como cédulas e moedas    |
| #2       | 2   | Transação       | 1    | 1      | Método padrão para movimentações feitas com transações bancárias como PIX, TED e DOC |
| #3       | 3   | Cartão crédito  | 2    | 1      | Método padrão para movimentações pagas com cartão de crédito                         |
| #4       | 4   | Cartão débito   | 3    | 1      | Método padrão para movimentações pagas com cartão de débito                          |

> Obs. 1: Como o atributo *id* é auto incrementado, cuidar para que na inserção dos valores, o valor aqui definido seja respeitado;

> Obs. 2: O valor do atributo *active* pode ser **1** ou **true**, dependendo do banco de dados utilizado.


#### 1.1.6. Tipo de Transação (Transaction Type)


#### 1.1.6.1. Descrição

A entidade *Tipo de Transação* (internamente ao sistema, identificada como "transaction type") é a entidade que representa os tipos de transação. Será utilizado como forma de organizar as entidades *Transação* em grupos. Possíveis registros seriam "vendas", "compras", "empréstimos", "mensalidade", etc...


#### 1.1.6.2. Atributos da entidade

- nome (name):
    - objetivo:             Manter o nome pelo qual a entidade será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 45 caracteres;
    - alteração:            Não permitida.
- relevância (relevance):
    - objetivo:             Definir a relevância padrão da transação ao qual esse registro é relacionado;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - valores aceitos:      0 (não relevante), 1 (pouco relevante) ou 2 (relevante);
    - alteração:            Permitida.
- ativo (active):
    - objetivo:             Definir se o registro está ativo ou não;
    - obrigatório:          Sim;
    - tipo dado:            Booleano;
    - alteração:            Permitida em algumas circunstâncias (ver característica #3). (se não hover transações marcadas como).


#### 1.1.6.3. Banco de dados

Nome da tabela: transaction_type.

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 3;
    - auto incremento;
    - não permite valor nulo;
    - chave primária.
- name: Referente ao atributo "nome". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 45;
    - não permite valor nulo;
    - valor único.
- relevance: Referente ao atributo "relevância". Terá as seguintes características:
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


#### 1.1.6.4. Características da entidade

- Característica #1: Não é permitido que duas entidades *Tipo de Transação* possuam o mesmo nome;

- Característica #2: Não é permitido a alteração dos atributos da entidade, exceto o atributo *ativo* (ver característica #3 para mais detalhe);

- Característica #3: Registros inativos não estarão disponíveis para seleção nos cadastros de *Transação*, mas ainda aparecerão nos relatórios.

- Característica #4: Caso seja necessário a exclusão de algum *Tipo de Transação*, ela somente será permitida se o registro em questão não tiver referência em nenhuma *Transação*. Se for o caso, a *Transação* terá que ter seu *Tipo de Transação* alterado para outro registro ativo para liberar a exclusão do *Tipo de Transação* antigo (Tarefa #1, item 1.1.6.5).


#### 1.1.6.5. Tarefas

Tarefa #1: Alterar o *Tipo de Transação* de todas os registros de *Transação*.
> Deve-se informar um *Tipo de Transação* alvo, e então buscar todas os registros de *Transação* com tal *Tipo de Transação*.
> <br>Com a lista de registros encontrada, deve-se efetuar a troca do *Tipo de Transação* antigo para o novo (informado pelo usuário) em cada um dos registros de *Transação* encontrados.
> 


#### 1.1.6.6. Valores pré cadastrados

Na implantação do sistema, os seguintes registros devem ser cadastrados nesta tabela (transaction_type):

| Registro | id  | name                         | type | relevance | Objetivo                                                                                                                                                                  |
| :------: | :-: | :--------------------------- | :--: | :-------: | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| #1       | 1   | Entradas diversas            | 0    | 1         | Tipo de Transação padrão para movimentações que transferem valores de entrada, (considerando o usuário como recebedor), como recebimento de salário e empréstimos         |
| #2       | 2   | Saídas diversas              | 1    | 1         | Tipo de Transação padrão para movimentações que transferem valores de saída, (considerando o usuário como quem paga), como pagamento de contas e devolução de empréstimos |
| #3       | 3   | Movimentação entre carteiras | 1    | 1         | Tipo de Transação padrão para movimentações que transferem valores de uma carteira para outra, de um mesmo dono                                                           |

> Obs. 1: Como o atributo *id* é auto incrementado, cuidar para que na inserção dos valores, o valor aqui definido seja respeitado;

> Obs. 2: O valor do atributo *active* pode ser **1** ou **true**, dependendo do banco de dados utilizado.


#### 1.1.7. Transação (Transaction)


#### 1.1.7.1. Descrição

A entidade *Transação* (internamente ao sistema, identificada como "transaction") é utilizada (junto com a entidade Parcela, item 1.1.8) para representar as diversas transações salvas no sistema.


#### 1.1.7.2. Atributos da entidade

- título (tittle):
    - objetivo:             Manter o nome pelo qual a *Transação* será identificada;
    - obrigatório:          Sim;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              De 3 a 50 caracteres;
    - alteração:            Não permitida.
- data da transação (transaction_date):
    - objetivo:             Manter a data quando a a *Transação* foi efetuada;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver característica #1).
- data de processamento (processing_date):
    - objetivo:             Manter a data em que a *Transação* foi processada. Será útil para os casos de compras no cartão, onde nem sempre a *Transação* é processada no mesmo dia em que foi efetuada;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver características #1, #2 e #3).
- tipo da transação (transaction_type_id):
    - objetivo:             Manter o código de identificação do *Tipo da Transação*;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Permitida.
- valor bruto (gross_value): 
    - objetivo:             Registrar o valor total da *Transação*, no momento que esta é efetuada (não considera descontos ou arredondamentos);
    - obrigatório:          Sim;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver características #1 e #5).
- valor de desconto (discount_value): 
    - objetivo:             Registrar o valor do desconto dado a *Transação*, no momento que ela é efetuada (não considera descontos aplicados posteriormente nas parcelas);
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver características #1 e #5).
- valor líquido (net_value): 
    - objetivo:             Manter o valor líquido da Parcela. Não será informado pelo usuário, ao invés disso, será calculado como descrito em Característica #6;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Não Permitida.
- relevância (relevance):
    - objetivo:             Definir a relevância da *Transação* ao qual esse registro é relacionado;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - valores aceitos:      0 (não relevante), 1 (pouco relevante) ou 2 (relevante);
    - alteração:            Permitida.
- descrição (description): 
    - objetivo:             Salvar uma pequena descrição sobre o registro;
    - obrigatório:          Não;
    - tipo dado:            Alfanumérico (a-z, A-Z, 0-9 e espaços);
    - tamanho:              Entre 0 e 255 caracteres;
    - alteração:            Permitida.


#### 1.1.7.3. Banco de dados

Nome da tabela: transaction.

- id: Identificador da entidade. Terá as seguintes características:
    - tipo: int;
    - tamanho: 6;
    - auto incremento;
    - não permite valor nulo;
    - chave primária.
- tittle: Referente ao atributo "título". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 50;
    - não permite valor nulo.
- transaction_date: Referente ao atributo "data da transação". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- processing_date: Referente ao atributo "data de processamento". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- transaction_type_id: Referente ao atributo "tipo da transação". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
    - não permite valor nulo.
- gross_value: Referente ao atributo "valor bruto". Terá as seguintes características:
    - tipo: double;
    - tamanho: 5 sendo 2 casas decimais.
- discount_value: Referente ao atributo "valor de desconto". Terá as seguintes características:
    - tipo: double;
    - tamanho: 5 sendo 2 casas decimais;
    - não permite valor nulo (quando a entidade não possuir este valor, utilizar o valor padrão 0).
- relevance: Referente ao atributo "relevância". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo.
- description: Referente ao atributo "descrição". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 255.

- chave primária: 
    - id
- chave estrangeira: 
    - transaction_type_id faz referência ao atributo "id" da tabela "transaction_type"


#### 1.1.7.4. Características da entidade

- Característica #1: A exclusão de uma *Transação*, ou a alteração de seus atributos, está condicionada ao estado da(s) *Fatura*(s) a que sua(s) *Parcela*(s) pertence(m) (Tarefa #2, item 1.1.7.5);

- Característica #2: O valor informado nos atributos "data de processamento* e *data da transação*, por padrão, será igual. Caso sejam diferentes, o valor informado no atributo *data de processamento* deve ser maior que o valor informado no atributo *data da transação*, e a diferença entre os dois valores não deve ser maior que 2 dias.

- Característica #3: O valor informado no atributo *data de processamento* deve pertencer a uma *Fatura* marcada como "Aberta" ou "Fechada" (Tarefa #1, item 1.1.7.5).

- Característica #4: O valor da *Transação* deve ser igual a soma dos valores das suas *Parcela*s (Tarefa #3, item 1.1.7.5).

- Característica #5: Alterações nos valores de uma *Transação*, além de estarem condicionadas a Característica #1, sempre implicará no recálculo dos valores de suas *Parcela*s, e portanto, os novos valores devem ser validados (Tarefa #4, item 1.1.7.5).

- Característica #6: O *valor líquido* da *Transação* não será mantido no sistema, ao invés disso, será calculado no momento que for solicitado. Seu cálculo será feito somando o *valor líquido* de cada uma das *Parcela*s da *Transação* em questão.

- Característica #7: Por padrão, o valor do atributo *relevância* será igual ao valor do mesmo atributo da entidade *Tipo de Transação* selecionado, porém, esse valor pode ser alterado a qualquer momento.


#### 1.1.7.5. Tarefas

Tarefa #1: Definir se a *data de processamento* é válida.
> Registros de *Transação*, cujo *Método de Pagamento* não seja "crédito", não possuem restrições de valores para o atributo *data de processamento*.
> <br>Caso a *Transação* em questão tenha como *Método de Pagamento* um registro do tipo "crédito", o sistema deverá confirmar se o valor do atributo *data de processamento* não é pertencente a uma *Fatura* que esteja marcada como "Vencida" ou "Quitada", evitando assim, que suas *Parcela*s sejam adicionadas a uma *Fatura* já finalizada. Considerando que se a primeira *Parcela* possuir uma data válida as demais também terão, e que as datas da mesma serão sempre iguais a da *Transação*, basta conferir os dados da *Transação*.
> 

Tarefa #2: Definir se a *Transação* está liberada para exclusão ou alteração.
> Registros de *Transação*, cujo *Método de Pagamento* não seja "crédito" estão liberadas para alteração e exclusão.
> <br>Caso a *Transação* em questão tenha como *Método de Pagamento* um registro do tipo "crédito", o sistema deverá buscar todas as *Parcela*s da mesma, e confirmar se nenhuma das *Parcela*s pertencem a uma *Fatura* que esteja marcada como "Vencida" ou "Quitada".
> <br>Para as alterações no atributo *data de processamento*, deve-se validar novamente o seu valor (Tarefa #1).
> 

Tarefa #3: Definir se o valor das *Parcela*s confere com o valor da *Transação*.
> Para a confirmação dessa informação, vamos considerar os seguintes valores:
> - Valor da Transação: Será calculado subtraindo o *valor do desconto* da *Transação* do *valor bruto* da *Transação*.
> - Valor das Parcelas: Será calculado somando os atributos *valor bruto* de cada *Parcela* pertencente a essa *Transação*.
> 
> Se os dois valores (Valor da Transação e Valor das Parcelas) forem iguais, então os valores da *Transação* estão corretos.
> 


#### 1.1.8. Parcela (Installment)


#### 1.1.8.1. Descrição

A entidade *Parcela* (internamente ao sistema, identificada como "installment") é utilizada (junto com a entidade Transação, item 1.1.7) para representar as diversas transações salvas no sistema. Mas especificamente seus valores, origem e destino dos valores, data de vencimento (quando necessário) e pagamento e o método de pagamento. Quando a transação original for do tipo "Crédito", será possível que ela possua mais de uma *Parcela*, e portanto, mas de um registro relacionado a esta Transação. Nas demais situações (vendas no débito, transferências, movimentações...) existirá somente um registro por transação. Na prática, vendas a vista não possuem parcelas, no entanto, por uma questão de organização e padronização, sempre que se falar da entidade que mantém os valores de uma *Transação*, será utilizado o nome "Parcela", independente se o pagamento for à vista, débito ou crédito.


#### 1.1.8.2. Atributos da entidade

- transação (transaction_id):
    - objetivo:             Manter o código de identificação da *Transação* a qual esta *Parcela* pertence;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Não permitida.
- número da parcela (installment_number):
    - objetivo:             Manter o código de identificação da *Parcela* (necessário quando existe mais de uma *Parcela* por *Transação*);
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              2;
    - alteração:            Não permitida.
- data da parcela (installment_date):
    - objetivo:             Manter a data em que a *Parcela* foi (ou será, nos casos de vendas no crédito) registrada;
    - obrigatório:          Sim;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver características #2 e #3).
- valor bruto (gross_value): 
    - objetivo:             Registrar o valor da *Parcela*, no momento que ela é gerada (não considera descontos ou arredondamentos aplicados na *Parcela*);
    - obrigatório:          Sim;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Não permitida.
- valor de desconto (discount_value): 
    - objetivo:             Registrar o valor do desconto aplicado ao pagamento da *Parcela*;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver características #2).
- juros (interest_value): 
    - objetivo:             Registrar o valor do juro aplicado ao pagamento da *Parcela*;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver características #2).
- arredondamento (rounding_value): 
    - objetivo:             Registrar o valor do arredondamento aplicado ao pagamento da *Parcela*;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Permitida em algumas circunstâncias (ver característica #2).
- valor líquido (net_value): 
    - objetivo:             Manter o valor líquido da *Parcela*. Não será informado pelo usuário, ao invés disso, será calculado como descrito em Característica #6;
    - obrigatório:          Não;
    - tipo dado:            Decimal;
    - formato:              00000.00;
    - alteração:            Não Permitida.
- método de pagamento (payment_method):
    - objetivo:             Manter o código de identificação do *Método de Pagamento* utilizado no pagamento da *Parcela*;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Permitida em algumas circunstâncias (ver característica #2).
- cartão (card):
    - objetivo:             Manter o código de identificação do *Cartão* utilizado no pagamento da *Parcela*;
    - obrigatório:          Não (ver exceção em Característica #7);
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Permitida em algumas circunstâncias (ver características #2, #7).
- carteira origem (source_wallet):
    - objetivo:             Manter o código de identificação da *Carteira* de origem dos valores transacionados por essa *Parcela*;
    - obrigatório:          Não (ver exceção em Característica #8);
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Permitida em algumas circunstâncias (ver características #2, #7 e #8).
- carteira destino (destination_wallet):
    - objetivo:             Manter o código de identificação da *Carteira* de destino dos valores transacionados por essa *Parcela*;
    - obrigatório:          Sim;
    - tipo dado:            Numérico;
    - tamanho:              (condicionado ao tamanho do identificador da entidade referenciada);
    - alteração:            Permitida em algumas circunstâncias (ver característica #2).
- data de pagamento (payment_date):
    - objetivo:             Manter a data de pagamento da *Parcela*;
    - obrigatório:          Não;
    - tipo de dado:         Data;
    - formato:              yyyy-mm-dd;
    - alteração:            Permitida em algumas circunstâncias (ver características #9).


#### 1.1.8.3. Banco de dados

Nome da tabela: installment.

- transaction_id: Referente ao atributo "transação". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
    - não permite valor nulo.
- installment_number: Referente ao atributo "número da parcela". Terá as seguintes características:
    - tipo: int;
    - tamanho: 2;
    - não permite valor nulo.
- installment_date: Referente ao atributo "data da parcela". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.
- gross_value: Referente ao atributo "valor bruto". Terá as seguintes características:
    - tipo: double;
    - tamanho: 5 sendo 2 casas decimais.
- discount_value: Referente ao atributo "valor de desconto". Terá as seguintes características:
    - tipo: double;
    - tamanho: 5 sendo 2 casas decimais.
    - não permite valor nulo (quando a entidade não possuir este valor, utilizar o valor padrão 0).
- interest_value: Referente ao atributo "juros". Terá as seguintes características:
    - tipo: double;
    - tamanho: 5 sendo 2 casas decimais.
    - não permite valor nulo (quando a entidade não possuir este valor, utilizar o valor padrão 0).
- rounding_value: Referente ao atributo "arredondamento". Terá as seguintes características:
    - tipo: double;
    - tamanho: 5 sendo 2 casas decimais.
    - não permite valor nulo (quando a entidade não possuir este valor, utilizar o valor padrão 0).
- payment_method_id: Referente ao atributo "método de pagamento". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
    - não permite valor nulo.
- card_id: Referente ao atributo "cartão". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
    - não permite valor nulo.
- source_wallet_id: Referente ao atributo "carteira origem". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
    - não permite valor nulo.
- destination_wallet_id: Referente ao atributo "carteira destino". Terá as seguintes características:
    - tipo: int;
    - tamanho: (condicionado ao tamanho do identificador da entidade referenciada);
    - não permite valor nulo.
- payment_date: Referente ao atributo "data de pagamento". Terá as seguintes características:
    - tipo: date;
    - não permite valor nulo.

- chave primária: 
    - transaction
    - installment_number
- chave estrangeira: 
    - transaction_id faz referência ao atributo "id" da tabela "transaction"
    - payment_method_id faz referência ao atributo "id" da tabela "payment_method"
    - card_id faz referência ao atributo "id" da tabela "card"
    - source_wallet_id faz referência ao atributo "id" da tabela "wallet"
    - destination_wallet_id faz referência ao atributo "id" da tabela "wallet"


#### 1.1.8.4. Características da entidade

- Característica #1: Não é permitido a exclusão do registro de uma *Parcela*;

- Característica #2: Não é permitido a alteração dos valores dos atributos *transação*, *número da parcela*, *valor bruto* e *carteira destino*. Os demais atributos poderão ser alterados, desde que a *Parcela* não pertença a uma *Fatura*, ou que pertença a uma *Fatura* que esteja marcada como "Aberta" ou "Fechada" (ver demais características para regras adicionais sobre alterações).

- Característica #3: As *Parcela*s são criadas com suas *data da parcela* sempre com o mesmo dia em que foi efetuada a *Transação* (Tarefa #1, item 1.1.8.5), no entanto, quando uma nova *Fatura* é criada, as *Parcela*s que irão pertencer a ela terão suas datas atualizadas para o primeiro dia da *Fatura* (rever a Característica #12, descrita no item 1.1.4.4).

- Característica #4: Com exceção do atributo *arredondamento*, todos os atributos numéricos devem conter valores positivos. Não serão permitidos valores negativos;

- Característica #5: Nenhum dos atributos que mantém valores numéricos poderão conter um valor maior que o informado no atributo *valor bruto*.

- Característica #6: O *valor líquido* da *Parcela* não será informado pelo usuário, ao invés disso, será calculado sempre que for solicitado através do seguinte cálculo: *valor líquido* = *valor bruto* - *valor de desconto* - *juros* - *arredondamento*. O *valor líquido* nunca deverá ser maior que o *valor bruto*, se assim ocorrer, alguns dos valores (*valor de desconto*, *juros* e *arredondamento*) está incorreto, e deverá ser corrigido;

- Característica #7: Por padrão, o atributo *cartão* não é obrigatório, entretanto, caso seja informado um valor para o atributo *método de pagamento* que seja relativo a cartão (seja crédito ou débito), o atributo *cartão* passa a ser obrigatório. O *Método de Pagamento* selecionado também limita os registros de *Cartão* liberados para a venda, por exemplo, se o *Método de Pagamento* selecionado for do tipo crédito, então somente registro de *Cartão* do tipo "crédito" serão permitidos na *Transação*.

- Característica #8: Por padrão, o atributo *carteira origem* não é obrigatório, e pode ser alterado posteriormente, entretanto, caso seja informado um *Cartão*, o atributo *carteira origem* passa a ser obrigatório, e sua alteração pelo usuário não será mais permitida. Nesse caso, a *carteira de origem* deverá ser obrigatoriamente a mesma *Carteira* qual o *Cartão* selecionado pertence.

- Característica #9: Quando uma *Transação* for marcada com um *Método de Pagamento* do tipo "crédito", obrigatoriamente suas *Parcela*s deverão ter os mesmos valores em seus atributos *cartão" e *carteira origem*.

- Característica #10: Quando uma *Transação* for referentes a venda no crédito, suas *Parcela*s terão seu atributo *data de pagamento* preenchidos automaticamente no momento que *Fatura* for paga, com o valor da data em questão.


#### 1.1.8.5. Tarefas

Tarefa #1: Definir o valor do atributo *data da parcela*.
> Registros de *Transação* que não forem referentes a vendas no crédito, manterão o atributo *data da parcela* de sua *Parcela* igual ao valor informado no atributo *data da transação* da *Transação*;
> <br>Para registros de *Transação* que forem referentes a vendas no crédito, seguir a seguinte regra:
> - A primeira *Parcela* terá o valor do seu atributo *data da parcela* salvo com o mesmo valor informado no atributo *data da transação* da *Transação*;
> - As demais *Parcela*s terão o valor dos seus respectivos atributos *data da parcela* salvos com o mesmo dia informado no atributo *data da transação* da *Transação*, porém, com os meses seguintes.
> 
> Ex.: Considere que a *Fatura* atual vai do dia 05/05/2023 até 04/06/2023. Considere também que uma venda foi efetuada no dia 25/05/2023, e que 5 *Parcela*s foram geradas para essa venda. Nesse caso, as *Parcela*s devem ter seus respectivos atributos *data da parcela* marcados como "25/05/2023", "25/06/2023", "25/07/2023", "25/08/2023" e "25/05/2023".
> 








**********************************

Possíveis alterações no projeto:

Bloqueei a alterações dos nomes ṕor que creio que isso cria a possibilidade de que o usuário fique trocando o nome de uma determinada entidade, e depois de algum tempo o histórico de movimentos da entidade tenha valores misturados. Por exemplo: Nomear um Cartão como "Banco A" e depois renomear para "Banco B". Tal alteração faria com que os movimentos relativos ao primeiro cartão "se misturassem" com os movimentos do segundo, já que para o sistema, os dois cartões sempre foram o mesmo cartão, apenas com nomes diferentes. No entanto, isso cria um cenário onde o usuário não pode corrigir erros de digitação, depois de a entidade salva, sendo necessário (para a correção) a exclusão e recriação da entidade. Uma alternativa de correção seria criar uma tabela adicional, com o registro dos nomes das entidades, antigos e o atual. Nesse caso, a tabela não teria o registro do nome da entidade, mas sim, uma referência a um registro na tabela "Nomes".

Mantive um padrão de formatação para os valores de duas casas decimais (000.00), mas tenho receio de que isso crie problemas de arredondamento em algum cálculo. Uma possível alternativa seria manter o valor, mas ignorar a marcação de casas decimais, por exemplo, nesse caso o número "123.45" se tornaria "12345". Preciso ver se isso realmente soluciona o problema, uma vez que adiciona mais uma camada de tratamento dos valores.

Rever o bloqueio na alteração dos valores das parcelas. Uma alternativa seria distribuir a diferença no valor entre as parcelas ainda não quitadas

Considerar manter Transação e Parcela como uma entidade só, mas manter elas separadas no banco. Poderia ser mais fácil de manusear os dados no sistema, garantindo sua integridade, mas devo considerar o aumento no tamanho dos dados