/* Create */

drop database if exists finance_api;

create database finance_api character set utf8 collate utf8_general_ci;
-- drop database finance_api

create table finance_api.owner (
	id int(3) not null auto_increment, 
	name varchar(30) not null, 
	active char(1) not null, 
	primary key (id), 
	unique(name) 
);
-- select * from finance_api.owner;
-- drop table finance_api.owner;

create table finance_api.wallet (
	id int(4) not null auto_increment, 
	name varchar(30) not null,
	owner_id int(3) not null, 
	main_wallet char(1) not null, 
	active char(1) not null, 
	description varchar(255) default null, 
	primary key (id), 
	constraint fk_wallet_owner foreign key (owner_id) references owner (id) 
);
-- select * from finance_api.wallet;
-- drop table finance_api.wallet;

create table finance_api.card (
	id int(3) not null auto_increment, 
	wallet_id int(4) not null, 
	name varchar(20) not null, 
	allow_credit char(1) not null, 
	first_day_month int(2) default null, 
	days_to_expiration int(2) default null, 
	active char(1) not null, 
	primary key (id), 
	unique(name), 
	constraint fk_card_wallet foreign key (wallet_id) references wallet (id) 
);
-- select * from finance_api.card;
-- drop table finance_api.card;

create table finance_api.card_date (
	card_id int(3) not null, 
	start_date date not null, 
	end_date date not null, 
	value double(7,2) default null, 
	primary key (card_id, start_date, end_date), 
	constraint fk_card_date_card foreign key (card_id) references card (id) 
);
-- select * from finance_api.card_date;
-- drop table finance_api.card_date;

create table finance_api.payment_method (
	id int(3) not null auto_increment, 
	name varchar(30) not null, 
	active char(1) not null, 
	primary key (id), 
	unique(name) 
);
-- select * from finance_api.payment_method;
-- drop table finance_api.payment_method;

create table finance_api.transaction_type (
	id int(4) not null auto_increment, 
	name varchar(45) not null, 
	relevance char(1) not null, 
	active char(1) not null, 
	primary key (id), 
	unique(name) 
);
-- select * from finance_api.transaction_type;
-- drop table finance_api.transaction_type;

create table finance_api.transaction (
	id int(6) not null auto_increment, 
	tittle varchar(50) not null, 
	transaction_date date not null, 
	processing_date date not null, 
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
	due_date date not null, 
	gross_value double(7,2) not null, 
	discount_value double(7,2) not null, 
	interest_value double(7,2) not null, 
	rounding_value double(7,2) not null, 
	destination_wallet int(4) not null, 
	source_wallet int(4) default null, 
	payment_method int(3) default null, 
	payment_date date default null, 
	primary key (transaction, installment_number), 
	constraint fk_installment_transaction foreign key (transaction) references transaction (id) on delete cascade, 
	constraint fk_installment_payment_method foreign key (payment_method) references payment_method (id), 
	constraint fk_installment_source_wallet foreign key (source_wallet) references wallet (id), 
	constraint fk_installment_destination_wallet foreign key (destination_wallet) references wallet (id) 
);
-- select * from finance_api.installment;
-- drop table finance_api.installment;

/** Procedures */

DROP PROCEDURE IF EXISTS finance_api.sum_wallets_by_period;
DROP PROCEDURE IF EXISTS finance_api.sum_wallets_by_months;
DROP PROCEDURE IF EXISTS finance_api.sum_wallets_by_days;
DROP PROCEDURE IF EXISTS finance_api.confirm_card_date;
DROP PROCEDURE IF EXISTS finance_api.create_card_date;

DELIMITER //

CREATE PROCEDURE finance_api.sum_wallets_by_period (start_date date, end_date date, owner_id int)
BEGIN

	select  
		w.id as wallet_id, w.name as wallet_name, 
		sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_in, 
		sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_out, 
		(
			sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) - 
			sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) 
		) as values_total
	from 
		transaction t 
			left join installment i on i.transaction = t.id 
			left join wallet w on w.id = i.source_wallet or w.id = i.destination_wallet
	where 
		w.owner_id = owner_id and i.due_date between start_date and end_date
	group by 
		w.id 
	order by 
		w.id;

END;

//

CREATE PROCEDURE finance_api.sum_wallets_by_months (start_date date, end_date date, month_start_day int, owner_id int)
BEGIN

	set @month_start_day = (select CASE WHEN month_start_day < 1 THEN "01" WHEN month_start_day < 10 THEN CONCAT("0", month_start_day) ELSE month_start_day END);
	set @start_date = CONCAT(DATE_FORMAT(start_date, '%Y-%m-'), @month_start_day);
	set @end_date = DATE_ADD(DATE_SUB(CONCAT(DATE_FORMAT(end_date, '%Y-%m-'), @month_start_day), INTERVAL 1 DAY), INTERVAL 1 MONTH);

	select  
		CONCAT(
			DATE_FORMAT(
				CASE 
					WHEN DATE_FORMAT(i.due_date, '%d') >= @month_start_day THEN i.due_date 
					ELSE DATE_SUB(i.due_date, INTERVAL 1 MONTH) 
				END, '%Y-%m-'
			), @month_start_day
		) as start_at, 
		w.id as wallet_id, w.name as wallet_name, 
		sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_in, 
		sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_out, 
		(
			sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) - 
			sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) 
		) as values_total
	from 
		transaction t 
			left join installment i on i.transaction = t.id 
			left join wallet w on w.id = i.source_wallet or w.id = i.destination_wallet
	where 
		w.owner_id = owner_id and i.due_date between @start_date and @end_date
	group by 
		start_at, w.id 
	order by 
		start_at, w.id;

END;

//

CREATE PROCEDURE finance_api.sum_wallets_by_days (start_date date, end_date date, owner_id int)
BEGIN

	select  
		i.due_date, w.id as wallet_id, w.name as wallet_name, 
		sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_in, 
		sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) values_out, 
		(
			sum(case when w.id = i.destination_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) - 
			sum(case when w.id = i.source_wallet then (((i.gross_value + i.interest_value) - i.discount_value) + i.rounding_value) else 0 end) 
		) as values_total
	from 
		transaction t 
			left join installment i on i.transaction = t.id 
			left join wallet w on w.id = i.source_wallet or w.id = i.destination_wallet
	where 
		w.owner_id = owner_id and i.due_date between start_date and end_date
	group by 
		i.due_date, w.id 
	order by 
		i.due_date, w.id;

END;

//

CREATE PROCEDURE finance_api.confirm_card_date ()
BEGIN

	set @card_id = (select min(id) from card where active = true and allow_credit = true);
	set @max_id = (select max(id) from card where active = true and allow_credit = true);

	WHILE @card_id <= @max_id DO
		set @is_credit_card = (select allow_credit from card where id = @card_id);
		set @has_date = (select count(*) from card_date cd where cd.card_id = @card_id and cd.start_date <= now() and cd.end_date >= now());

		IF @is_credit_card AND @has_date = 0 THEN
			CALL finance_api.create_card_date(@card_id);
		END IF;
		SET @card_id = @card_id + 1;
	END WHILE;
END;

//

CREATE PROCEDURE finance_api.create_card_date (card_id int)
BEGIN
	
	set @used_card = (select count(*) from card_date cd where cd.card_id = card_id);
 	set @first_day_month = (select c.first_day_month from card c where c.id = card_id);
 	set @end_date_next_month = (DATE_FORMAT(NOW(), '%d') > @first_day_month);

	IF @end_date_next_month THEN
		set @end_date = DATE_ADD(LAST_DAY(now()), INTERVAL @first_day_month DAY);
	ELSE
		set @end_date = DATE_ADD(DATE_SUB(DATE_ADD(LAST_DAY(now()), INTERVAL 1 DAY), INTERVAL 1 MONTH), INTERVAL (@first_day_month -1) DAY);
	END IF;

	IF @used_card THEN
		set @last_end_date = (select end_date from card_date cd where cd.card_id = card_id order by end_date desc limit 1);
		set @start_date = DATE_ADD(@last_end_date, INTERVAL 1 DAY);
	ELSE
		set @start_date = DATE_SUB(@end_date, INTERVAL 1 MONTH);
	END IF;

	INSERT INTO card_date values (card_id, @start_date, @end_date);
END;

//

DELIMITER ;

DROP EVENT IF EXISTS finance_api.create_card_dates;

CREATE EVENT finance_api.create_card_dates ON SCHEDULE 
	AT '2023-01-01 00:00:00.000' + INTERVAL 1 DAY
    	DO call finance_api.confirm_card_date();

