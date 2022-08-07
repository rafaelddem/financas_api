/* Create */

drop database if exists finance_api;

create database finance_api character set utf8 collate utf8_general_ci;
-- drop database finance_api

create table finance_api.owner (
	id int(3) not null auto_increment, 
	name varchar(30) not null, 
	active char(1) not null, 
	primary key (id) 
);
-- select * from finance_api.owner;
-- drop table finance_api.owner;

create table finance_api.wallet (
	id int(4) not null auto_increment, 
	name varchar(30) not null,
	owner_id int(3) not null, 
	main_wallet char(1) not null, 
	active char(1) not null, 
	primary key (id), 
	constraint fk_wallet_owner foreign key (owner_id) references owner (id) 
);
-- select * from finance_api.wallet;
-- drop table finance_api.wallet;

create table finance_api.payment_method (
	id int(3) not null auto_increment, 
	name varchar(30) not null, 
	active char(1) not null, 
	primary key (id) 
);
-- select * from finance_api.payment_method;
-- drop table finance_api.payment_method;

create table finance_api.transaction_type (
	id int(4) not null auto_increment, 
	name varchar(45) not null, 
	relevance char(1) not null, 
	active char(1) not null, 
	primary key (id) 
);
-- select * from finance_api.transaction_type;
-- drop table finance_api.transaction_type;

create table finance_api.transaction (
	id int(6) not null auto_increment, 
	tittle varchar(50) not null, 
	transaction_date date not null, 
	transaction_type int(4) not null, 
	gross_value double(7,2) not null, 
	discount_value double(7,2) not null, 
	relevance char(1) not null, 
	description varchar(255) default null, 
	primary key (id), 
	constraint fk_transaction_transaction_type foreign key (transaction_type) references transaction_type (id) 
);
-- select * from finance_api.transaction;
-- drop table finance_api.transaction;

create table finance_api.installment (
	transaction int(6) not null, 
	installment_number int(2) not null, 
	duo_date date not null, 
	gross_value double(7,2) not null, 
	discount_value double(7,2) not null, 
	interest_value double(7,2) not null, 
	rounding_value double(7,2) not null, 
	destination_wallet int(4) not null, 
	source_wallet int(4) default null, 
	payment_method int(3) default null, 
	payment_date date default null, 
	primary key (transaction, installment_number), 
	constraint fk_transaction_payment_method foreign key (payment_method) references payment_method (id), 
	constraint fk_transaction_source_wallet foreign key (source_wallet) references wallet (id), 
	constraint fk_transaction_destination_wallet foreign key (destination_wallet) references wallet (id) 
);
-- select * from finance_api.installment;
-- drop table finance_api.installment;