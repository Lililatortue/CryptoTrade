create table user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) not null,
    email VARCHAR(150) unique not null,
    pays VARCHAR(50) not null,
    age INT not null,
    password VARCHAR(150) not null,
    salt BINARY(16) not null
);

create table crypto(
    id INT AUTO_INCREMENT PRIMARY KEY,
    crypto_name Varchar(100) not null,
    quantite NUMBER(1000) not null,
    valeur NUMBER(1000) not null,
    image varchar(1000) not null
);

--junction table
create table portefeuille(
    user_id VARCHAR  primary key,
    crypto_id VARCHAR,
    quantite Number null,
    unique(user_id,crypto_id),
    constraint FK_user_id    Foreign key (user_id) REFERENCES user(id),
    constraint FK_crypto_id  Foreign key (crypto_id) REFERENCES crypto(id)
)

