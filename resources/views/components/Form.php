	<div class="split">
		<div>
			<label for="name">Nom (requis)</label>
			<input 
				type="text" 
				id="name"
				name="name" 
				class="form__name" 
				placeholder="Nom de famille"
				 
			/>
			<p class="form__name__error"></p>
		</div>
		<div>
			<label for="surname">Prénom (requis)</label>
			<input 
				type="text" 
				id="surname"
				name="surname" 
				class="form__surname" 
				placeholder="Nom de famille"
				
			/>
			<p class="form__surname__error"></p>
		</div>
	</div>
	<div>
		<label for="email">Adresse e-mail (requis)</label>
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
		<label for="phone">Numéro de téléphone (requis)</label>
		<input 
			type="text" 
			id="phone"
			name="phone" 
			class="form__phone" 
			placeholder="Numéro de téléphone" 
			
		/>
		<p class="form__phone__error"></p>
	</div>
	<div>
		<label for="image">Photo (optionel)</label>
		<div class="file-input">
			<span>Uploader une image</span>
			<input 
				type="file" 
				name="image" 
				id="image" 
				class="form__image" 
			/>
		</div>
		<p class="form__image__error"></p>
	</div>
	<div>
		<label for="category">Catégorie (requis)</label>
		<select name="category" id="category" class="form__category">
			<option value="">Choisir une catégorie</option>
			<option value="Famille">Famille</option>
			<option value="Amis">Amis</option>
			<option value="Travail">Travail</option>
		</select>
		<p class="form__category__error"></p>
	</div>
	<div>
		<label for="favorite">Favoris (requis)</label>
		<div>
			<div class="switch">
				<input 
					id="favorite" 
					name="favorite" 
					class="check-toggle check-toggle-round-flat form__favorite" 
					type="checkbox"
				>
				<label for="favorite"></label>
				<span class="on">oui</span>
				<span class="off">non</span>
			</div>
		</div>
	</div>
	<button type="submit" class="form__submit">Ajouter</button>
