<!Doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Conceptual Model Autographsy</title>
	</head>
	<body>
		<h1>Entities & Attributes Autographsy.com Conceptual Model</h1>
		<h2>Profile</h2>
		<ul>
			<li>profileId</li>
			<li>profileActivationToken</li>
			<li>profileAtHandle</li>
			<li>profileHash</li>
			<li>profileSalt</li>
			<li>profileEmail</li>
			<li>profilePhone</li>
		</ul>
		<h2>Product</h2>
		<ul>
			<li>productId</li>
			<li>productDescription</li>
			<li>productPrice</li>
			<li>productFavorite</li>
		</ul>
		<h2>Favorite</h2>
		<ul>
			<li>favoriteProductId</li>
			<li>favoriteProfileId</li>
		</ul>
		<h2>Relation</h2>
		<ul>
			<li>Many profiles can favorite many Products</li>
			<li>Many products can have many favorites</li>
		</ul>
		<h1>Conceptual Model ERD</h1>
		<img src="images/autographsy.svg" alt="erd for autographsy">
		<!--assuming phase 1 will go here-->
	</body>
</html>