<div class="content" data-template="profile">
	<div class="profile">
		<div class="profile__title">
			<h2>Mon profil</h2>
			<p>
				Vous pouvez modifier vos informations personnelles ici.
			</p>
		</div>
		<form class="form informations">
			<div class="split">
				<div>
					<label for="name">Prénom</label>
					<input 
						type="text" 
						name="name" 
						id="name"
						value=""
						class="form__name"
					>
					<p class="form__name__error"></p>
				</div>
				<div>
					<label for="surname">Prénom</label>
					<input 
						type="text" 
						name="surname" 
						id="surname"
						value=""
						class="form__surname"
					>
					<p class="form__surname__error"></p>
				</div>
			</div>
			<div>
				<label for="username">Nom d'utilisateur</label>
				<input 
					type="text" 
					name="username" 
					id="username"
					value=""
					class="form__username"
				>
				<p class="form__username__error"></p>
			</div>
			<div>
				<label for="email">Adresse e-mail</label>
				<input 
					type="text" 
					name="email" 
					id="email"
					value=""
					class="form__email"
				>
				<p class="form__email__error"></p>
			</div>
			<button type="submit" class="form__submit">Modifier</button>
		</form>
		<div class="profile__title">
			<h2>Modifier mon mot de passe</h2>
			<p>
				Vous pouvez modifier votre mot de passe ici.
			</p>
		</div>
		<form class="form password">
			<div>
				<label for="password">Mot de passe actuel</label>
				<input 
					type="password" 
					name="password" 
					id="password"
					value=""
					class="form__password"
				>
				<p class="form__password__error"></p>
			</div>
			<div>
				<label for="newpassord">Nouveau mot de passe</label>
				<input 
					type="password" 
					name="newpassword" 
					id="newpassord"
					value=""
					class="form__newpassword"
				>
				<p class="form__newpassword__error"></p>
			</div>
			<div>
				<label for="renewpassord">Confirmation du nouveau mot de passe</label>
				<input 
					type="password" 
					name="renewpassword" 
					id="renewpassord"
					value=""
					class="form__renewpassword"
				>
				<p class="form__renewpassword__error"></p>
			</div>
			<button type="submit" class="form__submit">Modifier</button>
		</form>
	</div>
</div>