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
			<li>profileUsername</li>
			<li>profileHash</li>
			<li>profileSalt</li>
			<li>profileEmail</li>
			<li>profilePhone</li>
		</ul>
		<h2>Product</h2>
		<ul>
			<li>productId</li>
			<li>productSellerId</li>
			<li>productPSA</li>
			<!--autographs and signatures are typically verified by a 3rd party service unless seller is well known this is known as a PSA.-->
			<li>productPrice</li>
		</ul>
		<h2>Favorites</h2>
		<ul>
			<li>favoriteProductId</li>
			<li>favoriteSellerId</li>
		</ul>
		<h2>Relation</h2>
		<ul>
			<li>One profile can buy many products</li>
			<li>Many profiles can favorite many Products and Sellers</li>
			<li>Many products and sellers can have many favorites</li>
		</ul>
		<h1>Conceptual Model Diagram</h1>
		<img src="images/conceptual-model.svg" alt="Conceptual Model Autographsy">
		<!--assuming phase 1 will go here-->
	</body>
</html>