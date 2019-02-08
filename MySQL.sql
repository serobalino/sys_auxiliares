/*==============================================================*/
/* DBMS name:      MySQL 5.0                                    */
/* Created on:     08/02/2019 13:57:39                          */
/*==============================================================*/


drop table if exists comprobantes;

drop table if exists contrasenas;

drop table if exists documentos;

drop table if exists notifications;

drop table if exists password_resets;

drop table if exists tiempo;

drop table if exists tipo_comprobantes;

drop table if exists tipo_contrasena;

drop table if exists tipo_documentos;

drop table if exists users;

/*==============================================================*/
/* Table: comprobantes                                          */
/*==============================================================*/
create table comprobantes
(
   acceso_co            char(49) not null,
   id_ingreso           bigint not null,
   numero_do            varchar(15) not null,
   codigo_tc            int not null,
   emision_co           date,
   creado_co            timestamp,
   json_co              text,
   xml_co               text,
   primary key (acceso_co)
);

/*==============================================================*/
/* Table: contrasenas                                           */
/*==============================================================*/
create table contrasenas
(
   id                   bigint not null,
   id_co                int not null,
   activa_co            bool default 1,
   creado_co            timestamp,
   detalle_co           varchar(50)
);

/*==============================================================*/
/* Table: documentos                                            */
/*==============================================================*/
create table documentos
(
   numero_do            varchar(15) not null,
   codigo_td            int not null,
   id_us                bigint not null,
   id_ti                int not null,
   rsocial_do           varchar(100),
   primary key (numero_do)
);

/*==============================================================*/
/* Table: notifications                                         */
/*==============================================================*/
create table notifications
(
   id                   char(36) not null,
   notifiable_id        bigint,
   type                 varchar(400),
   notifiable_type      varchar(400),
   data                 text,
   read_at              datetime,
   updated_at           timestamp,
   created_at           datetime default current_timestamp,
   primary key (id)
);

/*==============================================================*/
/* Table: password_resets                                       */
/*==============================================================*/
create table password_resets
(
   email                varchar(300),
   token                varchar(300),
   created_at           timestamp
);

/*==============================================================*/
/* Table: tiempo                                                */
/*==============================================================*/
create table tiempo
(
   id_ti                int not null,
   titulo_ti            varchar(20) not null,
   meses_ti             smallint not null,
   primary key (id_ti)
);

alter table tiempo comment 'semestrales
anuales';

/*==============================================================*/
/* Table: tipo_comprobantes                                     */
/*==============================================================*/
create table tipo_comprobantes
(
   codigo_tc            int not null,
   titulo_tc            varchar(100) not null,
   primary key (codigo_tc)
);

/*==============================================================*/
/* Table: tipo_contrasena                                       */
/*==============================================================*/
create table tipo_contrasena
(
   id_co                int not null auto_increment,
   detalle_co           varchar(60) not null,
   primary key (id_co)
);

/*==============================================================*/
/* Table: tipo_documentos                                       */
/*==============================================================*/
create table tipo_documentos
(
   codigo_td            int not null,
   titulo_td            varchar(20) not null,
   primary key (codigo_td)
);

/*==============================================================*/
/* Table: users                                                 */
/*==============================================================*/
create table users
(
   id                   bigint not null auto_increment,
   name                 varchar(300),
   password             char(61),
   remember_token       varchar(100),
   admin                boolean default 0,
   email                varchar(300),
   created_at           datetime default current_timestamp,
   updated_at           timestamp,
   email_verified_at    datetime default null,
   primary key (id)
);

alter table comprobantes add constraint fk_documentos_comprobantes foreign key (numero_do)
      references documentos (numero_do);

alter table comprobantes add constraint fk_tipo_comprobantes foreign key (codigo_tc)
      references tipo_comprobantes (codigo_tc);

alter table comprobantes add constraint fk_usuarios_comprobantes foreign key (id_ingreso)
      references users (id);

alter table contrasenas add constraint fk_relationship_9 foreign key (id_co)
      references tipo_contrasena (id_co);

alter table contrasenas add constraint fk_usuarios_contrasenas foreign key (id)
      references users (id);

alter table documentos add constraint fk_documentos foreign key (codigo_td)
      references tipo_documentos (codigo_td);

alter table documentos add constraint fk_documentos2 foreign key (id_us)
      references users (id);

alter table documentos add constraint fk_recordatorio foreign key (id_ti)
      references tiempo (id_ti);

alter table notifications add constraint fk_usuarios_notificaciones foreign key (notifiable_id)
      references users (id);

