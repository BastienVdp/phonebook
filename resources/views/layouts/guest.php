<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="/assets/css/index.css?=<?= time() ?>">
</head>
<body>
	<div class="app">
		{{ content }}
	</div>	
	<script type="module" src="/assets/js/index.js?=<?= time() ?>" defer></script>
</body>
</html>