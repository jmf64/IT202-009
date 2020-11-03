CREATE TABLE Accounts
(
    id             int auto_increment,
    account_number varchar(12) NOT NULL UNIQUE,
    user_id        int,
    account_type   varchar(20),
    opened_date    TIMESTAMP       default CURRENT_TIMESTAMP,
    last_updated TIMESTAMP default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    balance        decimal(12, 2) default 0.00,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES Users (id)
)
