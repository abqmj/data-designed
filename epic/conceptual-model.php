<!Doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Conceptual Model Autographsy</title>
	</head>
	<body>
		<h1>Entities & Attributes Autographsy.com Conceptual Model</h1>
		<h2>Buyer and Seller Profiles</h2>
		<ul>
			<li>fansId</li>
			<li>fansUserId</li>
			<li>fansHomeTeam</li>
			<li>fansFavoriteAthlete</li>
			<li>fansSocialMedia</li>
			<li>fansWebsite</li>
		</ul>
		<h2>Products</h2>
		<ul>
			<li>productId</li>
			<li>productSeller</li>
			<li>productPSA</li>
			<!--autographs and signatures are typically verified by a 3rd party service unless seller is well known.-->
			<li>productCategory</li>
			<li>productPrice</li>
			<li>productAddToCart</li>
		</ul>
		<h2>Liked products for future purchasing</h2>
		<!--Phase 0 is just liking but I had originally started doing the header as Cart and list as
		purhcaseProductId
		purchasePrice
		purchaseCartTotal-->
		<ul>
			<li>likeProductId</li>
			<li>likeSeller</li>
			<li>likeAuth</li>
			<li>likePrice</li>
		</ul>
		<h1>Conceptual Model Diagram</h1>
		<img src="images/conceptual-model.svg" alt="Conceptual Model Autographsy">
		<!--assuming phase 1 will go here-->
	</body>
</html>