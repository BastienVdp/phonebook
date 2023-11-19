import Application from "../application";
import Page from "../core/Page";
import axiosClient from "../utils/axios";

export default class Register extends Page 
{
	constructor()
	{
		super({
			element: '.register',
			elements: {
				form: '.form',
				username: '.form__username',
				name: '.form__name',
				surname: '.form__surname',
				email: '.form__email',
				password: '.form__password',
				password_confirmation: '.form__password_confirmation',
				button: '.form__submit',
				errorUsername: '.form__username__error',
				errorName: '.form__name__error',
				errorSurname: '.form__surname__error',
				errorEmail: '.form__email__error',
				errorPassword: '.form__password__error',
				errorPassword_confirmation: '.form__password_confirmation__error',
			}
		});
	}


	create()
	{
		super.create();
		this.initForm();
	}

	submit()
	{
		axiosClient.post('/api/register', {
			username: this.elements.username.value,
			name: this.elements.name.value,
			surname: this.elements.surname.value,
			email: this.elements.email.value,
			password: this.elements.password.value,
			password_confirmation: this.elements.password_confirmation.value,
		}).then(response => {
			this.app.setToken(response.data.token);
			this.app.onChange({
				url: '/me'
			})
		}).catch(error => {
			this.toggleButton();
			if(error.response && error.response.data.errors) {
				this.setErrors(error.response.data.errors);
			}
		});
	}
}