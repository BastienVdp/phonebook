import Questions from "../components/Questions";
import Page from "../core/Page";
import axiosClient from "../utils/axios";

export default class Profile extends Page 
{
	constructor()
	{
		super({
			element: '.profile',
			elements: {
				form: '.form.informations',
				name: '.form__name',
				surname: '.form__surname',
				username: '.form__username',
				email: '.form__email',
				button: '.form__submit',
				errorName: '.form__name__error',
				errorSurname: '.form__surname__error',
				errorUsername: '.form__username__error',
				errorEmail: '.form__email__error',

				passwordForm: '.form.password',
				password: '.form__password',
				newPassword: '.form__newpassword',
				newPassword_confirmation: '.form__renewpassword',
				errorPassword: '.form__password__error',
				errorNewPassword: '.form__newpassword__error',
				errorNewPassword_confirmation: '.form__renewpassword__error',
			}
		});
	}

	create()
	{
		this.app.checkAuth();
		super.create();
		this.getUserInformations();
		this.initForm();
		this.initPasswordForm();
		
		this.questions = new Questions();
	}
	
	async getUserInformations()
	{
		await axiosClient.get('/api/profile').then(response => {
			this.elements.name.value = response.data.user.name;
			this.elements.surname.value = response.data.user.surname;
			this.elements.username.value = response.data.user.username;
			this.elements.email.value = response.data.user.email;
		});
	
	}

	initPasswordForm()
	{
		this.elements.passwordForm.addEventListener('submit', (e) => {
			e.preventDefault();
			this.toggleButton();
			this.submitPassword();
		});
	}

	submit()
	{
		axiosClient.post('/api/profile', {
			name: this.elements.name.value,
			surname: this.elements.surname.value,
			username: this.elements.username.value,
			email: this.elements.email.value,
		}).then(response => {
			if(response.data.token) {
				this.app.toast.show({
					type: 'success',
					message: response.data.message
				})
				this.app.setToken(response.data.token);
			}
			this.toggleButton();
		}).catch(error => {

			if(error.response.status === 422) {
				this.setErrors(error.response.data.errors);
			}
			this.toggleButton();
		});
	}

	submitPassword()
	{
		axiosClient.post('/api/profile/password', {
			password: this.elements.password.value,
			newPassword: this.elements.newPassword.value,
			newPassword_confirmation: this.elements.newPassword_confirmation.value,
		})
		.then(response => {
			if(response.data.success) {
				this.app.toast.show({
					type: 'success',
					message: response.data.message
				})
			}
		})
		.catch(error => {
			if(error.response && error.response.status === 422) {
				this.setErrors(error.response.data.errors);
			}
		});
	}

	removeAllListeners()
	{
		this.elements.passwordForm.addEventListener('submit', (e) => {
			e.preventDefault();
			this.toggleButton();
			this.submitPassword();
		});
	}
}