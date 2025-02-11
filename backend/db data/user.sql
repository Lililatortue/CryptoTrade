create table user(
    id VARCHAR PRIMARY KEY,
    username VARCHAR not null,
    pays VARCHAR not null,
    age VARCHAR not null   
);
create table crypto(
    id VARCHAR PRIMARY KEY,
    quantite NUMBER not null,
    valeur NUMBER not null,
    image NUMBER not null
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

