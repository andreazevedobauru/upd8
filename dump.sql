create table cache
(
    `key`      varchar(255) not null
        primary key,
    value      mediumtext   not null,
    expiration int          not null
)
    collate = utf8mb4_unicode_ci;

create table cache_locks
(
    `key`      varchar(255) not null
        primary key,
    owner      varchar(255) not null,
    expiration int          not null
)
    collate = utf8mb4_unicode_ci;

create table countries
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255) not null,
    code       varchar(255) not null,
    phonecode  int          not null,
    created_at timestamp    null,
    updated_at timestamp    null
)
    collate = utf8mb4_unicode_ci;

create table failed_jobs
(
    id         bigint unsigned auto_increment
        primary key,
    uuid       varchar(255)                          not null,
    connection text                                  not null,
    queue      text                                  not null,
    payload    longtext                              not null,
    exception  longtext                              not null,
    failed_at  timestamp default current_timestamp() not null,
    constraint failed_jobs_uuid_unique
        unique (uuid)
)
    collate = utf8mb4_unicode_ci;

create table job_batches
(
    id             varchar(255) not null
        primary key,
    name           varchar(255) not null,
    total_jobs     int          not null,
    pending_jobs   int          not null,
    failed_jobs    int          not null,
    failed_job_ids longtext     not null,
    options        mediumtext   null,
    cancelled_at   int          null,
    created_at     int          not null,
    finished_at    int          null
)
    collate = utf8mb4_unicode_ci;

create table jobs
(
    id           bigint unsigned auto_increment
        primary key,
    queue        varchar(255)     not null,
    payload      longtext         not null,
    attempts     tinyint unsigned not null,
    reserved_at  int unsigned     null,
    available_at int unsigned     not null,
    created_at   int unsigned     not null
)
    collate = utf8mb4_unicode_ci;

create index jobs_queue_index
    on jobs (queue);

create table migrations
(
    id        int unsigned auto_increment
        primary key,
    migration varchar(255) not null,
    batch     int          not null
)
    collate = utf8mb4_unicode_ci;

create table password_reset_tokens
(
    email      varchar(255) not null
        primary key,
    token      varchar(255) not null,
    created_at timestamp    null
)
    collate = utf8mb4_unicode_ci;

create table personal_access_tokens
(
    id             bigint unsigned auto_increment
        primary key,
    tokenable_type varchar(255)    not null,
    tokenable_id   bigint unsigned not null,
    name           varchar(255)    not null,
    token          varchar(64)     not null,
    abilities      text            null,
    last_used_at   timestamp       null,
    expires_at     timestamp       null,
    created_at     timestamp       null,
    updated_at     timestamp       null,
    constraint personal_access_tokens_token_unique
        unique (token)
)
    collate = utf8mb4_unicode_ci;

create index personal_access_tokens_tokenable_type_tokenable_id_index
    on personal_access_tokens (tokenable_type, tokenable_id);

create table sessions
(
    id            varchar(255)    not null
        primary key,
    user_id       bigint unsigned null,
    ip_address    varchar(45)     null,
    user_agent    text            null,
    payload       longtext        not null,
    last_activity int             not null
)
    collate = utf8mb4_unicode_ci;

create index sessions_last_activity_index
    on sessions (last_activity);

create index sessions_user_id_index
    on sessions (user_id);

create table states
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)    not null,
    country_id bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint states_country_id_foreign
        foreign key (country_id) references countries (id)
)
    collate = utf8mb4_unicode_ci;

create table cities
(
    city_id    bigint unsigned auto_increment
        primary key,
    name       varchar(255)    not null,
    ibge       int             null,
    state_id   bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint cities_state_id_foreign
        foreign key (state_id) references states (id)
)
    collate = utf8mb4_unicode_ci;

create index cities_state_id_index
    on cities (state_id);

create table clients
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)    not null,
    email      varchar(255)    not null,
    cpf        varchar(255)    not null,
    birth_date varchar(255)    not null,
    gender     varchar(255)    not null,
    address    varchar(255)    not null,
    city_id    bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint clients_cpf_unique
        unique (cpf),
    constraint clients_email_unique
        unique (email),
    constraint clients_city_id_foreign
        foreign key (city_id) references cities (city_id)
)
    collate = utf8mb4_unicode_ci;

create index clients_city_id_index
    on clients (city_id);

create table sellers
(
    id         bigint unsigned auto_increment
        primary key,
    name       varchar(255)    not null,
    city_id    bigint unsigned not null,
    created_at timestamp       null,
    updated_at timestamp       null,
    constraint sellers_city_id_foreign
        foreign key (city_id) references cities (city_id)
)
    collate = utf8mb4_unicode_ci;

create index sellers_city_id_index
    on sellers (city_id);

create index states_country_id_index
    on states (country_id);

create table users
(
    id                bigint unsigned auto_increment
        primary key,
    name              varchar(255) not null,
    email             varchar(255) not null,
    email_verified_at timestamp    null,
    password          varchar(255) not null,
    remember_token    varchar(100) null,
    created_at        timestamp    null,
    updated_at        timestamp    null,
    constraint users_email_unique
        unique (email)
)
    collate = utf8mb4_unicode_ci;

