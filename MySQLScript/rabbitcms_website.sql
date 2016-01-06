CREATE TABLE website
(
    id INT(11) PRIMARY KEY NOT NULL,
    `key` TEXT NOT NULL,
    value TEXT,
    `group` TEXT,
    status INT(11) DEFAULT '1',
    type TEXT NOT NULL
);
CREATE UNIQUE INDEX website_id_uindex ON website (id);

INSERT INTO rabbitcms.website (id, `key`, value, `group`, status, type) VALUES (1, 'website_name', null, 'information', 1, 'text');
INSERT INTO rabbitcms.website (id, `key`, value, `group`, status, type) VALUES (2, 'website_keyword', null, 'information', 1, 'text');
INSERT INTO rabbitcms.website (id, `key`, value, `group`, status, type) VALUES (3, 'website_description', null, 'information', 1, 'textarea');
INSERT INTO rabbitcms.website (id, `key`, value, `group`, status, type) VALUES (4, 'custom_navigation', '0', 'information', 1, 'switch');