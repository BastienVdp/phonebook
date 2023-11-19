import Component from "../core/Component";

export default class Categories extends Component
{
	constructor()
	{
		super({
			element: '.categories',
		});

		this.data = [
			{
				id: 1,
				name: "Amis",
			},
			{
				id: 2,
				name: "Travail",
			},
			{
				id: 3,
				name: "Famille",
			},
		];

		this.renderComponent();
		this.addEventListeners();
	}


	renderComponent()
	{
		this.element.innerHTML = `
			<ul class="categories__component">
				<div class="categories__component__item active" data-category="all">
					Tout
				</div>
				<div class="categories__component__item" data-category="favoris">
					Favoris
				</div>
				${this.data.map((item, key) => {
					return `
						<div class="categories__component__item" data-category="${item.name}">
							${item.name}
						</div>
					`;
				}).join('')}
			</ul>
		`;
	}

	addEventListeners()
	{
		document.querySelectorAll('.categories__component__item')
			.forEach((item, key) => {
				item.addEventListener('click', () => {
					this.clickOnItem(item);
				});
			});
	}

	clickOnItem(item)
	{
		document.querySelector('.categories__component__item.active').classList.remove('active');
		item.classList.add('active');
		this.emit('categories:change', item.dataset.category);
	}

	removeAllListeners()
	{
		document.querySelectorAll('.categories__component__item')
			.forEach((item, key) => {
				item.removeEventListener('click', () => {
					this.clickOnItem(item);
				});
			});
	}


}

