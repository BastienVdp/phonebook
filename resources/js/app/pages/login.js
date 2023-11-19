import Page from "../core/Page";
import axiosClient from "../utils/axios";

export default class Login extends Page
{
	constructor()
	{
		super({
			element: '.login',
			elements: {
				form: '.form.form-login',
				email: '.form__email',
				password: '.form__password',
				button: '.form__submit',
				errorEmail: '.form__email__error',
				errorPassword: '.form__password__error',
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
		await axiosClient.post('/api/login', {
			email: this.elements.email.value,
			password: this.elements.password.value
		}).then((response) => {
			this.app.setToken(response.data.token);
			this.app.onChange({
				url: '/me'
			})
		}).catch((error) => {
			this.toggleButton();
			if(error.response && error.response.status === 422) {
				this.setErrors(error.response.data.errors);
			}
		});
	}

}