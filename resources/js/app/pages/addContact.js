import Page from "../core/Page";
import axiosClient from "../utils/axios";

export default class AddContact extends Page 
{
	constructor()
	{
		super({
			element: '.addContact',
			elements: {
				form: '.form',
				name: '.form__name',
				surname: '.form__surname',
				email: '.form__email',
				phone: '.form__phone',
				category: '.form__category',
				image: '.form__image',
				favorite: '.form__favorite',
				button: '.form__submit',
				errorName: '.form__name__error',
				errorSurname: '.form__surname__error',
				errorEmail: '.form__email__error',
				errorPhone: '.form__phone__error',
				errorCategory: '.form__category__error',
				errorImage: '.form__image__error',
				errorFavorite: '.form__favorite__error',
			}
		});
	}

	create()
	{
		super.create();
		this.initForm();
	}

	async submit()
	{
		const imageFile = this.elements.image.files[0];
		await axiosClient.post('/api/contacts', {
			name: this.elements.name.value,
			surname: this.elements.surname.value,
			email: this.elements.email.value,
			phone: this.elements.phone.value,
			category: this.elements.category.value,
			favorite: !this.elements.favorite.checked,
			image: imageFile ? await this.encodeImageToBase64(imageFile) : null,
		}).then((response) => {
				if (response.data.success) {
					this.app.onChange({
						url: '/me',
					});
				}
			})
			.catch((error) => {
				this.toggleButton();
				if (error.response && error.response.status === 422) {
					this.setErrors(error.response.data.errors);
				}
			});
	}

	
}