import Application from "../application";
import Component from "../core/Component";
import axiosClient from "../utils/axios";

export default class Contacts extends Component
{
	constructor()
	{
		super({
			element: '.contacts',
		});

		this.app = new Application();

		this.data = [];

		this.createItems();
	}

	async createItems()
	{
		await this.getContacts();
	}

	search(query)
	{
		const filteredContacts = this.data.filter((item) => {
			return (
				this.isLike(item.name, query) ||
				this.isLike(item.surname, query) ||
				this.isLike(item.phone, query)
			);
			
		});
		this.renderComponent(filteredContacts);
	}

	isLike(item, query)
	{
		return item.toLowerCase().includes(query.toLowerCase());
	}

	async filter(category)
	{
		if(category === 'all') return this.renderComponent(this.data);
		if(category === 'favoris') return this.renderComponent(this.data.filter((item) => item.favorite === 1));
		const filteredContacts = this.data.filter((item) => item.category === category)
		this.renderComponent(filteredContacts);
	}
	
	async getContacts() 
	{
		await axiosClient.get('/api/contacts')
			.then((response) => {
				if(response.data.success) {
					if(typeof response.data.contacts === 'object' && !Array.isArray(response.data.contacts)) {
						response.data.contacts = [response.data.contacts];
					}
					this.data = response.data.contacts;
					this.renderComponent(this.data);
				}
			})
	}
	
	createBar(data)
	{
		const isPlural = data.length > 1 ? 's' : '';
		const bar = document.createElement('div');
		bar.className = 'contacts__bar';
		bar.innerHTML = `
			<div class="contacts__bar__count">
				<b>${data.length}</b> contact${isPlural} trouvé${isPlural}
			</div>
		`;

		this.element.appendChild(bar);
	}

	renderComponent(data)
	{
		this.element.innerHTML = '';
		this.createBar(data);

		const ulElement = document.createElement('ul');
		ulElement.className = 'contacts__component';
		
		data.forEach((item, key) => {
			const div = document.createElement('li');
			div.className = 'contacts__component__item';
			new ContactItem(item, div);
			ulElement.appendChild(div);
		});
	
		this.element.appendChild(ulElement);
	}

	removeAllListeners()
	{
		document.querySelectorAll('.contacts__component__item')
			.forEach(item => {
				item.removeEventListener('click', () => {
					this.clickOnItem(item);
				});
			});
	}
}

class ContactItem
{
	constructor(item, container)
	{
		this.item = item;
		this.container = container;
		this.app = new Application();

		this.create();
		this.addEventsListeners();
	}

	openContact()
	{
		this.app.onChange({
			url: '/contacts/' + this.item.id,
		})
	}

	addEventsListeners()
	{
		this.container.addEventListener('click', () => {
			this.openContact();
		});
	}

	create() 
	{
		console.log(this.item);
		return this.container.innerHTML = `
			<div class="contacts__component__item__image">
				<img src="/images/contacts/${this.item.image}" alt="${this.item.name}">
			</div>
			<div class="contacts__component__item__informations">
				<div class="contacts__component__item__informations__fullname">${this.item.name} ${this.item.surname}</div>
				<div class="contacts__component__item__informations__phone">${this.item.phone}</div>
				<div class="contacts__component__item__informations__category">${this.item.category}</div>
				${this.item.favorite === 1 ? `
				<div class="contacts__component__item__informations__favorite">
					<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M47.6 300.4L228.3 469.1c7.5 7 17.4 10.9 27.7 10.9s20.2-3.9 27.7-10.9L464.4 300.4c30.4-28.3 47.6-68 47.6-109.5v-5.8c0-69.9-50.5-129.5-119.4-141C347 36.5 300.6 51.4 268 84L256 96 244 84c-32.6-32.6-79-47.5-124.6-39.9C50.5 55.6 0 115.2 0 185.1v5.8c0 41.5 17.2 81.2 47.6 109.5z"/></svg>' 

				</div>` 
				: ''}

			</div>
		`;
	}
}

