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

drop table if exists structures_table;
create table structures_table (sid text, label text, _type text, description text);
insert into structures_table values ('0', 'AIFLG', '0', 'AIFLG');
  insert into structures_table values ('-1','FRUITIERSDAUPH','1','FRUITIERSDAUPH');    
 insert into structures_table values ('-1','ALPESCO','1','ALPESCO');                  
 insert into structures_table values ('-1','CADRANSOL','1','CADRANSOL');              
 insert into structures_table values ('-1','GRVDL','1','GRVDL');                      
 insert into structures_table values ('-1','PERIGORD1','1','PERIGORD1');              
 insert into structures_table values ('-1','CADRALBRET','1','CADRALBRET');            
 insert into structures_table values ('-1','SOCAVE','1','SOCAVE');                    
 insert into structures_table values ('-1','COMAFEL','1','COMAFEL');                  
 insert into structures_table values ('-1','VDL','1','VDL');                          
 insert into structures_table values ('-1','VALPRIM','1','VALPRIM');                  
 insert into structures_table values ('-1','PERRINOT','1','PERRINOT');                
 insert into structures_table values ('-1','FRVELAY','1','FRVELAY');                  
 insert into structures_table values ('-1','QUERCYPRIM','1','QUERCYPRIM');            
 insert into structures_table values ('-1','GRANLOT','1','GRANLOT');                  
 insert into structures_table values ('-1','PERILOT','1','PERILOT');                  
 insert into structures_table values ('-1','PERIGFRUITS1','1','PERIGFRUITS1');        
 insert into structures_table values ('-1','SCAAFEL','1','SCAAFEL');                  
 insert into structures_table values ('-1','SACFEL','1','SACFEL');                    
 insert into structures_table values ('-1','FRAISNE','1','FRAISNE');                  
 insert into structures_table values ('-1','FLEURONANJOU','1','FLEURONANJOU');        
 insert into structures_table values ('-1','HAUTESTERRES','1','HAUTESTERRES');        
 insert into structures_table values ('-1','AGRISUD','1','AGRISUD');                  

drop table if exists producers_table;
create table producers_table as (select A.*, B.sid from producteurs A join structures_table B on A.code_structure = B.label);

