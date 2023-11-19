import Page from "../core/Page";
import axiosClient from "../utils/axios";

export default class ForgotPassword extends Page 
{
	constructor()
	{
		super({
			element: '.forgotPassword',
			elements: {
				form: '.form.first',
				email: '.form__email',
				errorEmail: '.form__email__error',
				button: '.form__submit',
				stepOne: '.step-one',
				stepTwo: '.step-two',
				stepThree: '.step-three',
				formQuestion: '.form.second',
				questions: '.form__questions',
				reponse: '.form__reponse',
				errorReponse: '.form__reponse__error',
				formNewPassword: '.form.three',
				password: '.form__newpassword',
				errorPassword: '.form__newpassword__error',
				password_confirmation: '.form__newpassword_confirmation',
				errorPassword_confirmation: '.form__newpassword_confirmation__error',
			}
		});
	}

	create()
	{
		super.create();
		this.initForm();
	}

	changeStep(step)
	{
		if(step === this.elements.stepTwo) {
			this.elements.stepOne.classList.add("hidden");
			this.elements.formQuestion.addEventListener('submit', (e) => {
				e.preventDefault();
				this.submitQuestion();
			})
		}
		if(step === this.elements.stepThree) {
			this.elements.stepTwo.classList.add("hidden");
			this.elements.formNewPassword.addEventListener('submit', (e) => {
				e.preventDefault();
				this.submitPassword();
			})
		}
		step.classList.toggle("hidden");
	}

	submitPassword()
	{
		axiosClient.post('/api/reset-password', {
			newPassword: this.elements.password.value,
			newPassword_confirmation: this.elements.password_confirmation.value,
			email: this.elements.email.value,
			token: localStorage.getItem('reset_token')
		}).then((response) => {
			if(response.data.success) {
				localStorage.removeItem('reset_token');
				this.app.toast.show({
					type: 'success',
					message: response.data.message
				})
				setTimeout(() => {
					this.app.onChange({
						url: '/'
					}, 3000)
				})
			}
			console.log(response);
		}).catch((error) => {
			if(error.response.status === 422 && !error.response.data.token) {
				this.setErrors(error.response.data.errors);
			}
		
		})
	}

	submitQuestion()
	{
		axiosClient.post('/api/check-question', {
			question: this.elements.questions.value,
			reponse: this.elements.reponse.value,
			email: this.elements.email.value
		}).then((response) => {
			this.changeStep(this.elements.stepThree);
		}).catch((error) => {
			if(error.response && error.response.status === 422) {
				this.setErrors(error.response.data.errors);
			}
		})
	}

	createSelect(questions)
	{
		questions.forEach(question => {

			let option = document.createElement('option');

			option.value = question.id;
			option.text = question.question;
			this.elements.questions.appendChild(option);
		})
	}

	async submit()
	{
		await axiosClient.post('/api/check-email', {
			email: this.elements.email.value
		}).then((response) => {
			localStorage.setItem('reset_token', response.data.token);
			this.changeStep(this.elements.stepTwo);
			this.createSelect(response.data.questions);
		}).catch((error) => {
			this.toggleButton();
			if(error.response && error.response.status === 422) {
				this.setErrors(error.response.data.errors);
			}
		})
	}
}