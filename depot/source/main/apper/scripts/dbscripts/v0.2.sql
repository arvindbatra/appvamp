#create payout transaction table


create table if not exists UserPayoutTransactions (
	id int not null auto_increment primary key,
	user_id int not null,
	user_app_info_id int not null,
	price float not null,
	status int not null,
	created_at DATETIME ,
	FOREIGN KEY (user_id) REFERENCES UserInfo(id),
	FOREIGN KEY (user_app_info_id) REFERENCES UserAppInfo(id)
	)engine = InnoDB;



