drop table if exists producteurs;
create table producteurs (
       code		varchar(20) primary key,
       nom  	 	text,
       adr1		text,
       adr2		text,
       adr3		text,
       cp		char(5),
       ville		text,
       telephone	text,
       fax		text,
       mobile		text,
       no_exploitant	text,
       email		text,
       code_structure	text);

drop table if exists parcelles;
create table parcelles (
       id_parcelle	varchar(20) primary key,
       nom_parcelle	text,
       surface		text,
       no_exploitant	text,
       date_plantation	text,
       ref_cadaste	text,
       code_parcelle	text,
       code_variete	text,
       code_producteur	text,
       fiche_bloquee	int,
       annee		int,
       type_plant	text,
       nb_plant		text,
       densite		text,
       type_abri	text,
       dummy		text,
       type_chauffage	text,
       itineraire	text,
       departement	int,
       volume_pm	text,
       precocite_pm	text,
       region		text,
       fin_plantation	text);

load data local infile 'producteurs' into table producteurs;
load data local infile 'parcelles' into table parcelles;

create table keys_table (uid text, _key text, encrypted_key text);
insert into keys_table values ('0', 'zob', '36f3b2748cea3f5714d849889bb4a0c7');

-- roles_table assigne un utilisateur à un role et une structure
drop table if exists roles_table;
create table roles_table (uid text, _role text, sid text, description text);
insert into roles_table values ('0', 'ADMIN/0', '0', 'Administrateur principal');

create table cookies_table (uid text, cookie text, encrypted_cookie text);

drop table if exists structures;
create table structures_table (sid text, _type text, description text);
insert into structures_table values ('0', '0', 'AIFLG');
