CREATE DATABASE IF NOT EXISTS cryptotradedb;

create table utilisateur(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) not null,
    email VARCHAR(150) unique not null,
    pays VARCHAR(50) not null,
    age INT not null,
    password VARCHAR(150) not null,
    salt BINARY(16) not null,
    date_created  DATETIME DEFAULT CURRENT_TIMESTAMP
);

create table crypto(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    symbole VARCHAR(10) NOT NULL,
    price_usd DECIMAL NOT NULL ,
    volatility DECIMAL(5, 2), -- Volatilité de la cryptomonnaie
    quantity DECIMAL not null,
    total_supply BIGINT ,
    market_cap BIGINT,
    date_created DATETIME DEFAULT CURRENT_TIMESTAMP

);
-- table qui sert pour le graph. historique de crypto
CREATE TABLE historique(
    crypto_id INT, 
    update_time DATETIME,  -- temps que la prix a ete créé : chaque 20 min, une valeur s'ajoute
    closing_price DECIMAL(15, 2) NOT NULL, 
    PRIMARY KEY (crypto_id, update_time), -- composite key (duo)
    FOREIGN KEY (crypto_id) REFERENCES crypto(id)
);

-- junction table
create table portefeuille(
    user_id INT PRIMARY KEY,
    crypto_id INT, 
    quantite DECIMAL null,
    achat_data DECIMAL null,
    unique(user_id,crypto_id),
    constraint FK_user_id    FOREIGN KEY (user_id) REFERENCES utilisateur(id),
    constraint FK_crypto_id  FOREIGN KEY (crypto_id) REFERENCES crypto(id)
);

create table transaction(
    id int AUTO_INCREMENT primary key,
    user_id INT,
    crypto_id INT,
    quantite DECIMAL NULL,
    transaction_type VARCHAR(20) NULL,
    transaction_date DATE DEFAULT CURRENT_TIMESTAMP,
    unique(user_id,crypto_id),
    constraint FK_trans_user_id    FOREIGN KEY (user_id) REFERENCES utilisateur(id),
    constraint FK_trans_crypto_id  FOREIGN KEY (crypto_id) REFERENCES crypto(id)
);

INSERT INTO crypto (symbole, name, price_usd, quantity, market_cap, total_supply, date_created)
VALUES 
('BTC', 'Bitcoin', 65000, 0.35, 120000000000, 21000000, '2023-10-01 12:00:00'),
('ETH', 'Ethereum', 3500, 5.2, 50000000000, 120000000, '2023-10-02 13:30:00'),
('USDT', 'Tether', 1.00, 1500, 83000000000, 85000000000, '2023-10-03 09:45:00');


-- simulation de BTC
INSERT INTO crypto (name, symbole, price_usd, quantity, total_supply, market_cap, date_created)
 VALUES ('Bitcoin', 'BTC', 3000.00, 21000000, 21000000, 6300000000, NOW() );

-- insertion de donnees random
INSERT INTO historique (crypto_id, update_time, closing_price) VALUES
(1, '2025-04-13 05:00:00', 3000.43),
(1, '2025-04-13 05:20:00', 3142.9),
(1, '2025-04-13 05:40:00', 3155.7),
(1, '2025-04-13 06:00:00', 3118.37),
(1, '2025-04-13 06:20:00', 3069.12),
(1, '2025-04-13 06:40:00', 2945.7),
(1, '2025-04-13 07:00:00', 2822.16),
(1, '2025-04-13 07:20:00', 2813.23),
(1, '2025-04-13 07:40:00', 2805.18),
(1, '2025-04-13 08:00:00', 2908.1),
(1, '2025-04-13 08:20:00', 2820.35),
(1, '2025-04-13 08:40:00', 2858.16),
(1, '2025-04-13 09:00:00', 2795.73),
(1, '2025-04-13 09:20:00', 2825.24),
(1, '2025-04-13 09:40:00', 2690.08),
(1, '2025-04-13 10:00:00', 2571.85),
(1, '2025-04-13 10:20:00', 2504.85),
(1, '2025-04-13 10:40:00', 2588.63),
(1, '2025-04-13 11:00:00', 2523.38),
(1, '2025-04-13 11:20:00', 2593.36),
(1, '2025-04-13 11:40:00', 2649.8),
(1, '2025-04-13 12:00:00', 2611.02),
(1, '2025-04-13 12:20:00', 2649.24),
(1, '2025-04-13 12:40:00', 2754.4),
(1, '2025-04-13 13:00:00', 2810.59),
(1, '2025-04-13 13:20:00', 2909.02),
(1, '2025-04-13 13:40:00', 2992.02),
(1, '2025-04-13 14:00:00', 2984.68),
(1, '2025-04-13 14:20:00', 3044.8),
(1, '2025-04-13 14:40:00', 3160.11),
(1, '2025-04-13 15:00:00', 3079.87),
(1, '2025-04-13 15:20:00', 3088.81),
(1, '2025-04-13 15:40:00', 3072.21),
(1, '2025-04-13 16:00:00', 3038.25),
(1, '2025-04-13 16:20:00', 2892.8),
(1, '2025-04-13 16:40:00', 2947.65),
(1, '2025-04-13 17:00:00', 2832.45);

--update du prix de la crypto dans 'crypto' table
UPDATE crypto
SET price_usd = (
    SELECT closing_price
    FROM historique
    WHERE crypto_id = 'BTC'
    ORDER BY candle_time DESC
    LIMIT 1
)
WHERE id = 'BTC';
