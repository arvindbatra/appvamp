alter table AppLine add column till_date DATETIME after on_date;
update AppLine set till_date = on_date;
alter table AppLine add column app_price float default 0.0 after app_name;
alter table AppLine add column refund_price float default 0.0 after app_name;


