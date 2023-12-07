<div class="content" data-template="forgotPassword">
	<div class="forgotPassword">
		<div>
			<div class="step-one">
				<h1>Mot de passe oublié ?</h1>
				<form class="form first">
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
					<button class="form__submit">
						Envoyer
					</button>
				</form>
				<p>
					<a href="/login">Retour </a>
				</p>
			</div>
			<div class="step-two hidden">
				<h1>Répondez à la question</h1>
				<form class="form second">
					<div>
						<label for="question">Question de sécurité</label>
						<select name="question" id="question" class="form__questions">
						</select>
					</div>
					<div>
						<label for="reponse">Réponse</label>
						<input 
							type="text" 
							id="reponse"
							name="reponse" 
							class="form__reponse" 
							placeholder="Réponse" 
						/>
						<p class="form__reponse__error"></p>
					</div>
					<button class="form__submit">Vérifier</button>
				</form>
			</div>
			<div class="step-three hidden">
				<h1>Modifier votre mot de passe</h1>
				<form class="form three">
					<div>
						<label for="newpassword">Nouveau mot de passe</label>
						<input 
							type="password" 
							id="newpassword"
							class="form__newPassword" 
							placeholder="Nouveau mot de passe" 
						/>
						<p class="form__newPassword__error"></p>
					</div>
					<div>
						<label for="newpassword_confirmation">Confirmation du nouveau mot de passe</label>
						<input 
							type="password" 
							id="newpassword_confirmation"
							class="form__newPassword_confirmation" 
							placeholder="Confirmation du nouveau mot de passe" 
						/>
						<p class="form__newPassword_confirmation__error"></p>
					</div>
					<button class="form__submit">Modifier</button>
				</form>
			</div>
		</div>
	</div>
</div>