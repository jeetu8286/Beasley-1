Create Table member_query_results(
	site_id           int            not null,
	member_query_id   int            not null,
	user_id           varchar(252)   not null,
	email             varchar(254)   not null
) Engine=InnoDB;
