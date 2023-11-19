<div class="content" data-template="login">
	<div class="login">
		<div class="card">
			<h1>Phonebook</h1>
			<form class="form form-login">
				<div>
					<label for="email">Adresse e-mail</label>
					<input 
						type="email" 
						id="email"
						name="email" 
						class="form__email" 
						placeholder="Adresse e-mail" 
					/>
					<p class="form__email__error"></p>
				</div>
				<div>
					<label for="password">
						Mot de passe
						<a href="/forgot-password">Oublié ?</a>
					</label>
					<input 
						type="password" 
						id="password" 
						name="password" 
						class="form__password" 
						placeholder="********" 
					/>
					<p class="form__password__error"></p>
				</div>
				<button type="submit" class="form__submit">Se connecter</button>
			</form>
			<p>
				Pas encore de compte ? <br />
				<a href="/register">Inscrivez-vous</a>
			</p>
		</div>
	</div>
</div>