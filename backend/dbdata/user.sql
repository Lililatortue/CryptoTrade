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
    crypto_name Varchar(255) not null,
    quantite INT(100) not null,
    valeur INT(100) not null,
    image INT(100) not null
);

--junction table
create table portefeuille(
    user_id INT  primary key,
    crypto_id VARCHAR, 
    quantite Number null,
    achat_data Number null,
    unique(user_id,crypto_id),
    constraint FK_user_id    Foreign key (user_id) REFERENCES user(id),
    constraint FK_crypto_id  Foreign key (crypto_id) REFERENCES crypto(id)
)

create table transaction(
    id int AUTO_INCREMENT primary key,
    user_id INT  primary key,
    crypto_id VARCHAR,
    quantite Number null,
    transaction_type Number null,
    transaction_date DATE DEFAULT CURRENT_TIMESTAMP,
    unique(user_id,crypto_id),
    constraint FK_user_id    Foreign key (user_id) REFERENCES user(id),
    constraint FK_crypto_id  Foreign key (crypto_id) REFERENCES crypto(id)
)

--vue contient le resultat des requete preparer--
create view v_transaction(
    SELECT  
        t.id AS trans_id,
        t.quantite,
        t.transaction_type,
        t.transaction_date,
        u.id as user_id,
        u.username,
        c.id AS crypto_id,
        c.crypto_name,
        c.valeur,
        p.id AS porte_id
        p.trans_id
    From  transaction t
    JOIN  user u         ON t.user_id = u.id;
    JOIN  crypto c       ON t.crypto_id = c.crypto_id;
    JOIN  portefeuille p ON t.trans_id = p.trans_id;
    
)

create procedure getUserTransaction ( p_user_id INT )
    BEGIN
        SELECT username,
               SUM(quantite * valeur) as total
        FROM v_transaction
       
        WHERE user_id = p_user_id;
    END 



create procedure getRendementMoyen ()
    BEGIN 
        SELECT username,
               AVG( CASE 
                        WHEN t.transaction_type = 'SELL' 
                        THEN ((t.quantite * t.valeur) - (p.quantite * p.achat_date)) / (p.quantite * p.achat_date) * 100
                        ELSE NULL
                    END
                ) as avg_total
        FROM v_transaction t, portefeuille p,
            WHERE t.user_id = p.user_id 
            AND t.crypto_id = p.crypto_id
        GROUP BY t.username
    END

    create procedure getRendementMoyenParPerformance ()
    BEGIN 
        SELECT username,
               AVG( CASE 
                        WHEN t.transaction_type = 'SELL' 
                        THEN ((t.quantite * t.valeur) - (p.quantite * p.achat_date)) / (p.quantite * p.achat_date) * 100
                        ELSE NULL
                    END
                ) as avg_total
        FROM v_transaction t, portefeuille p,
            WHERE t.user_id = p.user_id 
            AND t.crypto_id = p.crypto_id
        GROUP BY t.username,
        ORDER BY avg_total DESC
    END