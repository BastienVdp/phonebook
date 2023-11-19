import Component from "../core/Component";

export default class Contacts extends Component
{
	constructor()
	{
		super({
			element: '.search',
			elements: {
				searchInput: '.search__input',
				searchButton: '.search__button',
			}
		});

		this.addEventListeners();
	}

	addEventListeners()
	{
		this.elements.searchButton.addEventListener('click', () => this.search());
		this.elements.searchInput.addEventListener('keyup', (event) => {
			if(event.keyCode === 13) {
				this.search();
			} else {
				clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.search();
                }, 300); 
			}
		});
	}

	search()
	{
		const query = this.elements.searchInput.value;
		this.emit('search:change', query);
	}

	removeAllListeners()
	{
		this.elements.searchButton.removeEventListener('click', () => this.search());
		this.elements.searchInput.removeEventListener('keyup', () => this.search());
	}

}

