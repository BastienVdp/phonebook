import Application from "../application";
import Component from "../core/Component";

export default class Header extends Component
{
	constructor()
	{
		super({
			element: '.header',
			elements: {
				back: '.header__button.back',
				create: '.header__button.create',
				logout: '.logout'
			}
		});

		this.app = new Application();

		this.addListeners();
	}

	addListeners()
	{
		this.elements.logout.addEventListener('click', (e) => {
			this.app.logout();
		});
	}

	async hide()
	{
		if(this.element) {
			this.element.classList.add('hidden');
		}
	}

	async show()
	{
		if(this.element) {
			this.element.classList.remove('hidden');
		}
	}

	toggleButton(template)
	{
		if(template === 'home') {
			this.element.classList.remove('hidden');
			this.elements.create.classList.remove('hidden');
			this.elements.back.classList.add('hidden');
		} else {
			if(this.elements.create) {
				this.elements.create.classList.add('hidden');
			}
			if(this.elements.back) {
				this.elements.back.classList.remove('hidden');
			}
		}
	}
	
}


