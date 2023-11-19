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
		<header class="header hidden">
			<div>
				<a href="/me">
					<h1 class="header__title">
						Phoneb<b>oo</b>k
					</h1>
				</a>
				<div>
					<a href="/profile">Mon profil</a>
					<button class="logout">Déconnexion</button>
				</div>
			</div>
			<div>
				<a href="/contacts/create" class="header__button create">
					<svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
						<g clip-path="url(#clip0_5_400)">
							<path d="M7.92864 2.34375C7.92864 1.8252 7.51369 1.40625 7.00007 1.40625C6.48645 1.40625 6.0715 1.8252 6.0715 2.34375V6.5625H1.89293C1.37931 6.5625 0.964355 6.98145 0.964355 7.5C0.964355 8.01855 1.37931 8.4375 1.89293 8.4375H6.0715V12.6562C6.0715 13.1748 6.48645 13.5938 7.00007 13.5938C7.51369 13.5938 7.92864 13.1748 7.92864 12.6562V8.4375H12.1072C12.6208 8.4375 13.0358 8.01855 13.0358 7.5C13.0358 6.98145 12.6208 6.5625 12.1072 6.5625H7.92864V2.34375Z" fill="black"/>
						</g>
						<defs>
							<clipPath id="clip0_5_400">
								<rect width="13" height="15" fill="white" transform="translate(0.5)"/>
							</clipPath>
						</defs>
					</svg>   
				</a>
				<a href="/me" class="header__button back">
					<svg xmlns="http://www.w3.org/2000/svg" height="14" viewBox="0 0 320 512"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>  
				</a>
			</div>
		</header>
		{{ content }}

		<div class="toast">
		</div>
	</div>	
	<script type="module" src="/assets/js/index.js?=<?= time() ?>" defer></script>
</body>
</html>