Olá

Meu nome é Rafael, sou formado em Ciência da Computação desde 2015 e trabalho com desenvolvimento desde 2018, e esse é meu projeto de apresentação (um deles)

Comecei esse projeto já a tanto tempo que nem lembro mais, e nesse meio tempo modifiquei ele outras tantas vezes pelos mais diversos motivos, treinar linguagens de programações novas, frameworks novos, mudanças de funcionalidades... quase sempre começando um novo projeto do zero, por isso meu github possui tantos projetos com o mesmo propósito (mas espero que quando estiver lendo isso, eu já tenha me organizado quanto a eles). Por fim, optei por consolidar meus conhecimentos em PHP, por isso não utilizei nenhum framework nesse projeto, assim como também optei por cria-lo como uma API, e desta forma não sendo necessário utilizar de tecnologias de front-end. 

1. Sobre o projeto:

A ideia principal do sistema é criar uma API que seja capaz de gerenciar as finanças pessoais de uma determinada pessoa. Cadastro de compras, salário, empréstimos, geração de relatório de dívidas, previsão de gastos e entradas de valores, etc... A seguir, detalharei melhor cada função.

1.1 Entidades


1.1.1 Owner


1.1.1.1 Descrição

A entidade "owner" é a entidade que representa cada pessoa (fisica ou jurídica) a qual será atribuída a propriedade de determinadas transações, assim como dos valores dessas transaçãoes. Por exemplo, caso o usuário de nome "Rafael" opte por cadastrar uma transação de depósito referente a um pagamento dele para outra pessoa de nome "Marcos", este usuário deverá possuir dois cadastros de "owner", um para ele próprio (o qual será criado junto a conta no sistema) e outro para o destinatário do valor. Dessa forma, o sistema saberá que o valor foi transferido de uma pessoa para outra, e poderá calcular os novos valores pós transação.


1.1.1.2 Propriedades

- name: Este será o nome de identificação da entidade. Particularidades:
    - Deverá ser informado no momento do cadastro da entidade;
    - Não será possível efetuar a alteração desta propriedade;
    - O valor informado deverá ser único, não sendo possível que duas entidades "owner" possuam o mesmo "name".
- active: Define se a entidade "owner" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.


1.1.1.3 Banco de dados

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


1.1.1.4 Funções

Função #1: Quando uma entidade "owner" é criada, uma entidade "wallet" (ver o item 1.1.2 para mais detalhes) deve ser criada automaticamente, e seu atributo "main_wallet" marcado como "true".
Função #2: Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


1.1.2 Wallet


1.1.2.1 Descrição

A entidade "wallet" (chamaremos de Carteira) é a entidade que representa os locais onde os valores estão armazenados, como contas em bancos ou mesmo a carteira pessoal do usuário. Será possível que um usuário (owner) tenha mais uma Carteira. Por exemplo, o usuário "Rafael" poderá cadastrar três Carteiras, de nomes "Conta Corrente", "Carteira" e "Poupança", e dessa forma ele poderá separar os valores que estão em sua conta corrente dos valores que estão em sua poupança e do dinheiro que ele possui em sua carteira pessoal.


1.1.2.2 Propriedades

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
    - Caso seja informado o valor "true" no momento do cadastro, deverá ser chamada a função #2 (item 1.1.2.4);
    - A alteração do valor desse atributo é permitida respeitando as regras da função #3 (item 1.1.2.4).
- active: Define se a entidade "owner" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.
- description: Atributo utilizado para que seja possível salvar uma pequena descrição sobre o registro. Particularidades:
    - Deverá possuir no máximo 255 caracteres;
    - Não será permitido caracteres especiais (exceto: ).


1.1.2.3 Banco de dados

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
- active: Referente ao atributo "active". Terá as seguintes características:
    - tipo: char;
    - tamanho: 1;
    - não permite valor nulo;
    - valor padrão: 1.
- description: Referente ao atributo "description". Terá as seguintes características:
    - tipo: varchar;
    - tamanho: 255.

- chave primária: 
    - id
- chave estrangeira: 
    - owner_id faz referência ao atributo "id" da entidade "owner"


1.1.2.4 Funções

Função #1: Quando uma entidade "owner" é criada, uma entidade "wallet" deve ser criada junto. Nesse caso, o atributo "owner_id" deve ser preenchido como o valor do atributo "id" da entidade "owner" recém criada, e o atributo "main_wallet" deve ser preenchido como "true".
Função #2: Quando uma nova entidade "wallet" é criada, e o valor do atributo "main_wallet" vier marcado como "true", deverá ser confirmado com o usuário se ele deseja realmente marcar a entidade desta forma. Caso seja confirmado, uma rotina deverá marcar o atributo "main_wallet" de todas as outras entidades "wallet" neste usuário (owner) como "false", e então efetuar o cadastro.
Função #3: Somente será possível a alteração do valor do atributo "main_wallet" para "true". Caso seja necessário que alguma entidade tenha esse atributo marcada como "false", outra entidade deverá ter seu atributo "main_wallet" marcado como "true".
Função #4: Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


1.1.3 Payment Method


1.1.3.1 Descrição

A entidade "payment method" é a entidade que representa os métodos de pagamento utilizados em cada transação, como por exemplo "Crédito", "Débito" e "Transferência".


1.1.3.2 Propriedades

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
- active: Define se a entidade "owner" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.

1.1.3.3 Banco de dados

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


1.1.3.4 Funções

Função #1: Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".


1.1.4 Transaction Type


1.1.4.1 Descrição

A entidade "transaction type" é a entidade que representa os tipos de transação. Será utilizado como forma de organizar as transações em grupos a critérios do usuário. Possíveis registros seriam "vendas", "compras", "empréstimos", "mensalidade", etc...


1.1.4.2 Propriedades

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
- active: Define se a entidade "owner" em questão está ativa ou não. Particularidades:
    - Na criação da entidade, deverá vir pré marcada como "true", porém, será permitida a alteração antes de finalizar o cadastro;
    - Também será permitida a alteração do valor depois do cadastro efetuado.

1.1.4.3 Banco de dados

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


1.1.4.4 Funções

Função #1: Não será permitido a excusão de um registro. Caso seja solicitado a exclusão, o atributo "active" é marcado como "false".

























