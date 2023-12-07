<div class="content" data-template="register">
	<div class="register">
		<div class="card">
			<h1>Phonebook</h1>
			<form class="form">
				<div>
					<label for="username">Nom d'utilisateur</label>
					<input 
						type="text" 
						id="username"
						name="username" 
						class="form__username" 
						placeholder="Nom d'utilisateur" 
					/>
					<p class="form__username__error"></p>
				</div>
				<div class="split">
					<div>
						<label for="name">Prénom</label>
						<input 
							type="text" 
							id="name"
							name="name" 
							class="form__name" 
							placeholder="Prénom"
						/>
						<p class="form__name__error"></p>
					</div>
					<div>
						<label for="surname">Nom</label>
						<input 
							type="text" 
							id="surname"
							name="surname" 
							class="form__surname" 
							placeholder="Nom"
						/>
						<p class="form__surname__error"></p>

					</div>
				</div>
				<div>
					<label for="email">Adresse e-mail</label>
					<input 
						type="text" 
						id="email"
						name="email" 
						class="form__email" 
						placeholder="Adresse e-mail" 
					/>
					<p class="form__email__error"></p>
				</div>
				<div>
					<label for="password">Mot de passe</label>
					<input 
						type="password" 
						id="password" 
						name="password" 
						class="form__password" 
						placeholder="********" 
					/>
					<p class="form__password__error"></p>
				</div>						
				<div>
					<label for="password_confirmation">Confirmation du mot de passe</label>
					<input 
						type="password" 
						id="password_confirmation" 
						name="password_confirmation" 
						class="form__password_confirmation" 
						placeholder="********" 
					/>
					<p class="form__password_confirmation__error"></p>
				</div>
				
				<button type="submit" class="form__submit">S'inscrire</button>
			</form>
			<p>
				Déjà inscrit ? <a href="/login">Connectez-vous</a>
			</p>
		</div>
	</div>
</div>