##create the user table
create table if not exists UserInfo  (
	id int not null auto_increment primary key,
	fbuid varchar(30)  not null,
	name varchar (100) not null,
	created_at DATETIME 
	)engine=InnoDB;


create table if not exists UserAppInfo (
	id int not null auto_increment primary key,
	fbuid varchar(30) not null,
	apple_id varchar(50),
	app_external_id varchar(50) not null,
	app_name varchar (200) not null,
	purchased_date DATETIME not null,
	created_at DATETIME
	)engine = InnoDB;


