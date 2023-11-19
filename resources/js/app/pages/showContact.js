import Page from "../core/Page";
import axiosClient from "../utils/axios";

export default class ShowContact extends Page
{
	constructor()
	{
		super({
			element: '.showContact',
			elements: {
				form: '.form',
				name: '.form__name',
				surname: '.form__surname',
				email: '.form__email',
				phone: '.form__phone',
				category: '.form__category',
				favorite: '.form__favorite',
				image: '.form__image',
				button: '.form__submit',
				errorName: '.form__name__error',
				errorSurname: '.form__surname__error',
				errorEmail: '.form__email__error',
				errorPhone: '.form__phone__error',
				errorCategory: '.form__category__error',
				deleteContact: '.delete'
			}
		});
	}

	create()
	{
		super.create();
		this.getContactInformations();
		this.initForm();
		
		this.addListener();
	}

	addListener()
	{
		this.elements.deleteContact.addEventListener('click', () => {
			this.deleteContact();
		});
	}

	async deleteContact()
	{
		await axiosClient.delete(`/api/contacts/${window.location.href.split('/').pop()}`)
			.then(response => {
				if(response.data.success) {
					this.app.toast.show({
						type: 'success',
						message: response.data.message
					});
					setTimeout(() => {
						this.app.onChange({
							url: '/me',
							transition: true
						})
					}, 1000);
				}
			})
			.catch(error => {
				if(error.response && error.response.status === 404) {
					this.app.toast.show({
						type: 'error',
						message: error.response.data.message
					});
					this.app.onChange({
						url: '/me',
						transition: false
					})
				}
			});
	}

	async getContactInformations()
	{
		await axiosClient.get(`/api/contacts/${window.location.href.split('/').pop()}`)
			.then(response => {
				if(response.data.contact) {
					this.elements.name.value = response.data.contact.name;
					this.elements.surname.value = response.data.contact.surname;
					this.elements.email.value = response.data.contact.email;
					this.elements.phone.value = response.data.contact.phone;
					this.elements.category.value = response.data.contact.category;
					this.elements.favorite.checked = response.data.contact.favorite === 1 ? false : true;
				}
			})
			.catch(error => {
				if(error.response && error.response.status === 404) {
					this.app.toast.show({
						type: 'error',
						message: error.response.data.message
					});
					this.app.onChange({
						url: '/me',
						transition: false
					})
				}
			})
	}

	initForm()
	{
		super.initForm();
		this.elements.button.innerHTML = 'Modifier';
	}

	async submit()
	{
		const imageFile = this.elements.image.files[0];
		
		await axiosClient.post(`/api/contacts/edit/${window.location.href.split('/').pop()}`, {
			name: this.elements.name.value,
			surname: this.elements.surname.value,
			email: this.elements.email.value,
			phone: this.elements.phone.value,
			category: this.elements.category.value,
			favorite: !this.elements.favorite.checked,
			image: imageFile ? await this.encodeImageToBase64(imageFile) : null,
		}).then((response) => {
			if(response.data.success) {
				this.toggleButton();
				this.app.toast.show({
					type: 'success',
					message: response.data.message
				})
				setTimeout(() => {
					this.app.onChange({
						url: '/me',
						transition: true
					})
				}, 1000);
			}
		}).catch((error) => {
			this.toggleButton();
			if(error.response && error.response.status === 422) {
				this.setErrors(error.response.data.errors);
			}
		});
	}
}