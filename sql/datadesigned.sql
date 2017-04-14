DROP TABLE IF EXISTS 'favorite';
DROP TABLE IF EXISTS 'product';
DROP TABLE IF EXISTS 'profile';

CREATE TABLE profile(
profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
profileActivationToken CHAR(32),
profileUsername VARCHAR(32) NOT NULL,
profileEmail VARCHAR(128) NOT NULL,
profilePhone VARCHAR(32),
profileHash CHAR(128) NOT NULL,
profileSalt CHAR(64) NOT NULL,
UNIQUE(profileEmail),
UNIQUE(profilePhone),
PRIMARY KEY(profileId)
);

CREATE TABLE product (
productId INT UNSIGNED AUTO_INCREMENT NOT NULL,
productDescription VARCHAR(2000) UNIQUE NOT NULL,
productPrice CHAR(10) UNIQUE NOT NULL,
-- productFavorite not sure on this table dam jeopardy
INDEX(productId),
PRIMARY KEY(productId),
);

CREATE TABLE favorite(
favoriteProfileId INT UNSIGNED NOT NULL,
favoriteProductId INT UNSIGNED NOT NULL,
INDEX(favoriteProfileId),
INDEX(favoriteProductId),
FOREIGN KEY(favoriteProfileId) REFERENCES profile(profileId),
FOREIGN KEY(favoriteProductId) REFERENCES product(prodcutId),
PRIMARY KEY(favoriteProfileId, favoriteProductId)
);