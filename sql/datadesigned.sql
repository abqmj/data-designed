DROP TABLE IF EXISTS favorite;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
profileActivationToken CHAR(32),
profileAtHandle VARCHAR(32) NOT NULL,
profileEmail VARCHAR(128) NOT NULL,
profilePhone VARCHAR(32),
profileHash CHAR(128) NOT NULL,
profileSalt CHAR(64) NOT NULL,
INDEX (profileId),
INDEX (profileAtHandle),
INDEX (profileEmail),
INDEX (profilePhone),
UNIQUE(profileEmail),
UNIQUE(profilePhone),
PRIMARY KEY(profileId)
);

CREATE TABLE product (
productId INT UNSIGNED AUTO_INCREMENT NOT NULL,
productDescription VARCHAR(2000) UNIQUE NOT NULL,
productPrice CHAR(10) UNIQUE NOT NULL,
-- productFavorite not sure on this if product is a tweet this would be likes but unsure how I would write this
INDEX(productId),
PRIMARY KEY(productId)
);

CREATE TABLE favorite (
favoriteProfileId INT UNSIGNED NOT NULL,
favoriteProductId INT UNSIGNED NOT NULL,
INDEX(favoriteProfileId),
INDEX(favoriteProductId),
FOREIGN KEY(favoriteProfileId) REFERENCES profile(profileId),
FOREIGN KEY(favoriteProductId) REFERENCES product(productId),
PRIMARY KEY(favoriteProfileId, favoriteProductId)
);