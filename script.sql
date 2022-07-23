/* Create */

drop database if exists api_finance;

create database api_finance character set utf8 collate utf8_general_ci;
-- drop database financas

create table api_finance.owner (
	id int(3) not null auto_increment, 
	name varchar(30) not null, 
	active char(1) not null, 
	primary key (id) 
);
-- select * from api_finance.owner;
-- drop table api_finance.owner;

create table financas.tbfi_tipoMovimento (
	int_codigo int(5) not null auto_increment, 
	str_nome varchar(45) not null, 
	chr_tipo char(1) not null, 							/* 1-positivo, 2-negativo, 3-transferencia*/
	int_indispensavel int(1) default 0, 
	str_descricao varchar(255) default null, 
	chr_ativo char(1) not null,						/* 0-inativa, 1-ativa */
	primary key (int_codigo)
);
-- select * from financas.tipoMovimento;
-- drop table financas.tipoMovimento;

create table financas.tbfi_formaPagamento (
	int_codigo int(2) not null auto_increment, 
	str_nome varchar(30) not null, 
	chr_ativo char(1) not null,						/* 0-inativa, 1-ativa */
	primary key (int_codigo)
);
-- select * from financas.formaPagamento;
-- drop table financas.formaPagamento;

create table financas.tbfi_movimento (
	int_codigo int(11) not null auto_increment, 
	int_parcela int(3) not null, 
	int_tipoMovimento int(5) not null, 
	dat_dataMovimento date not null, 
	dat_dataPagamento date default null, 
	dub_valorInicial double(7,2) not null, 
	dub_desconto double(7,2) not null, 
	dub_tributacao double(7,2) not null, 
	dub_juros double(7,2) not null, 
	dub_arredondamento double(7,2) not null, 
	dub_valorFinal double(7,2) not null, 
	int_formaPagamento int(2) not null, 
	int_carteiraOrigem int(3) not null, 
	int_carteiraDestino int(3) not null, 
	int_indispensavel int(1) default 0, 
	str_descricao varchar(255) default null, 
	primary key (int_codigo, int_parcela, int_carteiraOrigem, int_carteiraDestino), 
	constraint fk_movimento_tipo_movimento foreign key (int_tipoMovimento) references tbfi_tipoMovimento (int_codigo), 
	constraint fk_movimento_forma_pagamento foreign key (int_formaPagamento) references tbfi_formaPagamento (int_codigo), 
	constraint fk_movimento_carteira_origem foreign key (int_carteiraOrigem) references tbfi_carteira (int_codigo), 
	constraint fk_movimento_carteira_destino foreign key (int_carteiraDestino) references tbfi_carteira (int_codigo) 
);
-- select * from financas.movimento;
-- drop table financas.movimento;

