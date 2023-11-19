import Page from "../core/Page";
import Categories from "../components/Categories";
import Contacts from "../components/Contacts";
import Search from "../components/Search";

export default class Home extends Page 
{
	constructor()
	{
		super({
			element: '.home',
			elements: {
				searchInput: '.search__input',
				searchButton: '.search__button',
			}
		});
	}

	create()
	{
		super.create();
		this.createComponents();
	}	

	createComponents()
	{
		this.categories = new Categories();
		this.contacts = new Contacts();
		this.search = new Search();
	
		this.categories.on('categories:change', (category) => {
			this.contacts.filter(category);
		});

		this.search.on('search:change', (query) => {
			this.contacts.search(query);
		});
	}

	removeAllListeners()
	{
		this.categories.removeAllListeners();
		this.contacts.removeAllListeners();
		this.search.removeAllListeners();
	}


}