# Contacts Test Projec
Proyecto de prueba tecnica

## Configuración de la base de datos

En el archivo config/database.php
```sh
const DB_HOST = "localhost";
const DB_PORT = 3306;
const DB_DATABASE = 'contacts';
const DB_USERNAME = 'homestead';
const DB_PASSWORD = 'secret';
```

## Definición de las tablas

```sh
create table contact_numbers
(
    id         bigint auto_increment
        primary key,
    contact_id bigint                             null,
    number     varchar(20)                        null,
    created_at datetime default CURRENT_TIMESTAMP null,
    updated_at datetime default CURRENT_TIMESTAMP null,
    deleted_at datetime                           null
);
```

```sh
create table contacts
(
    id         bigint auto_increment
        primary key,
    first_name varchar(100)                       null,
    last_name  varchar(100)                       null,
    email      varchar(150)                       null,
    created_at datetime default CURRENT_TIMESTAMP null,
    updated_at datetime default CURRENT_TIMESTAMP null,
    deleted_at datetime                           null,
    constraint contacts_email_uindex
        unique (email)
);

```
