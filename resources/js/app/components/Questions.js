import Application from "../application";
import Component from "../core/Component";
import axiosClient from "../utils/axios";

export default class Questions extends Component
{
	constructor()
	{
		super({
			element: '.questions',
			elements: {
				addQuestion: '.addQuestion',
				removeQuestion: '.removeQuestion',
				form: '.form.questions',
				button: '.form.questions .form__submit',
				questions__list: '.questions__list',
			}
		});

		this.questions = 0;
		this.addListener();
		this.addQuestion();

		this.app = new Application();
	}

	getInputs()
	{
		const inputsQuestions = document.querySelectorAll('input[class^="question-"]');
		const inputsReponses = document.querySelectorAll('input[class^="reponse-"]');
		
		axiosClient.post('/api/profile/questions', {
			questions: this.getValues(inputsQuestions),
			reponses: this.getValues(inputsReponses)
		}).then(response => {
			this.app.toast.show({
				type: 'success',
				message: response.data.message
			})
		}).catch(error => {
			if(error && error.response.data.errors) {
				this.resetErrors();
				this.renderErrors(error.response.data.errors);
			}
		});
	}

	renderErrors(errors) 
	{
		for(const key in errors) {
			const errorElement = document.querySelector(`.form__${key}__error`);
			errorElement.innerHTML = errors[key];
		}
	}

	resetErrors() 
	{
		const errorsElement = document.querySelectorAll('[class$="__error"]');
		errorsElement.forEach(errorElement => {
			errorElement.innerHTML = '';
		});
	}
	getValues(inputs)
	{
		const values = [];
		inputs.forEach(input => {
			values.push(input.value);
		});

		return values;
	}

	addQuestion()
	{
		this.questions++;
		const question = this.createQuestion(this.questions);
		this.elements.questions__list.appendChild(question);
		this.elements.form.prepend(this.elements.questions__list);
		
		if(this.questions > 1) {
			this.showButton();
		}
	}

	createQuestion(questions)
	{
		const liElement = document.createElement('li');
		liElement.classList.add('questions__list__item');

		liElement.innerHTML = `
			<div class="group">
				<div>
					<label for="question-${questions}">Question ${questions}</label>
					<input type="text" class="question-${questions}" id="question-${questions}">
					<p class="form__question-${questions}__error"></p>
				</div>
				<div>
					<label for="reponse-${questions}">Réponse ${questions}</label>
					<input type="text" class="reponse-${questions}" id="reponse-${questions}">
					<p class="form__reponse-${questions}__error"></p>
				</div>	
			</div>
		`;

		return liElement;
	}

	removeQuestion()
	{
		this.questions--;
		const liElement = this.elements.questions__list.lastElementChild;
		this.elements.questions__list.removeChild(liElement);
		if(this.questions === 1) {
			this.elements.removeQuestion.classList.add('hidden');
		}
	}

	addListener()
	{
		this.elements.addQuestion.addEventListener("click", e => {
			e.preventDefault();
			if(this.questions < 3) {
				this.addQuestion();
			} else {
				this.app.toast.show({
					type: 'error',
					message: 'Tu peux seulement ajouter 3 questions'
				})
			}
		})

		this.elements.removeQuestion.addEventListener("click", e => {
			e.preventDefault();
			if(this.questions > 1) {
				this.removeQuestion();
			}
		})

		this.elements.form.addEventListener("submit", e => {
			e.preventDefault();
			this.getInputs();
		});
	}

	showButton()
	{
		this.elements.removeQuestion.classList.remove('hidden');
	}
	
}


