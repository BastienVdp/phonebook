import Home from './pages/home';
import Profile from './pages/profile';
import Login from './pages/login';
import Register from './pages/register';
import AddContact from './pages/addContact';
import ShowContact from './pages/showContact';
import ForgotPassword from './pages/forgotPassword';

import Header from './components/Header';
import Transition from './components/Transition';
import Toast from './components/Toast';

import axiosClient from './utils/axios';

export default class Application
{
	static instance;

	/**
	 * The constructor function initializes the Application object by setting up various properties and
	 * event listeners.
	 * @returns The constructor is returning the instance of the Application class.
	 */
	constructor()
	{
		if(Application.instance) {
			return Application.instance;
		}

		Application.instance = this;
		
		this.content = document.querySelector('.content');
		this.template = this.content.getAttribute('data-template');
		this.path = null;
		this.createTransition();
		this.createHeader();
		this.initPages();
		this.initLinks();
		this.initToast();

		window.onpopstate = () => {
			this.onChange({
				url: window.location.pathname,
				push: false,
			});
		}
	}

	/**
	 * The function initializes a new instance of the Toast class.
	 */
	initToast()
	{
		this.toast = new Toast();
	}

	/**
	 * The function creates a new Transition object.
	 */
	createTransition()
	{
		this.transition = new Transition();
	}

	/**
	 * The function creates a new instance of the Header class and assigns it to the "header" property of
	 * the current object.
	 */
	createHeader()
	{
		this.header = new Header();
	}

	/**
	 * The function `initPages()` initializes different pages and sets the current page based on the
	 * template, then creates and shows the current page.
	 */
	initPages()
	{
		this.home = new Home();
		this.profile = new Profile();
		this.login = new Login();
		this.register = new Register();
		this.addContact = new AddContact();
		this.showContact = new ShowContact();
		this.forgotPassword = new ForgotPassword();

		this.pages = {
			home: this.home,
			profile: this.profile,
			login: this.login,
			forgotPassword: this.forgotPassword,
			register: this.register,
			addContact: this.addContact,
			showContact: this.showContact
		};

		this.page = this.pages[this.template];
		this.page.create();
		this.page.show();
	}

	/**
	 * The `initLinks()` function is used to initialize links on a webpage by adding event listeners and
	 * modifying their behavior based on certain conditions.
	 */
	initLinks()
	{
		const links = document.querySelectorAll('a');
		
		links.forEach(link => {
			const isLocal = link.href.indexOf(window.location.origin) > -1;
			const isAnchor = link.href.indexOf('#') > -1;

			const isNotEmail = link.href.indexOf('mailto') === -1;
			const isNotPhone = link.href.indexOf('tel') === -1;

			if (isLocal) {
				link.onclick = event => {
					event.preventDefault();
					if (!isAnchor) {
						this.onChange({
							url: link.href,
						});
					}
				};
			} else if (isNotEmail && isNotPhone) {
				link.rel = 'noopener';
				link.target = '_blank';
			}
		});
		
	}

	/**
	 * The `onChange` function is an asynchronous function that handles the change of content on a web
	 * page, including making an HTTP request to retrieve the new content, updating the page with the new
	 * content, and handling transitions and history push state.
	 */
	async onChange({ url, push = true, transition = true })
	{
		if(url === this.path) {
			return;
		}
		
		this.setPath(url);

		if(transition) {
			await this.transition.show()
		}
		
		this.page.hide();
		
		await axiosClient.get(url)
			.then(response => {
				const html = response.data;
	
				if(push) {
					window.history.pushState({}, '', url);
				}
	
				const div = document.createElement('div');
				div.innerHTML = html;
	
				const content = div.querySelector('.content');
				this.template = content.getAttribute('data-template');
				
				
				this.content.setAttribute('data-template', this.template);
				this.content.innerHTML = content.innerHTML;
	

				this.page = this.pages[this.template];
				this.page.create();
				this.header.toggleButton(this.template);

				this.page.show();

				if(transition) {
					this.transition.hide();
				}
	
				this.initLinks();
			})
			.catch(e => {
				console.log(e);
			})
	}

	setPath(path)
	{
		this.path = path.replace(/\/$/, '');
	}
	/**
	 * The function checks if the user is authenticated and redirects to the homepage if not.
	 * @returns nothing (undefined).
	 */
	checkAuth()
	{
		if(!this.isConnected()) {
			window.location.href = '/';
			return;
		}
	}

	/**
	 * The function "checkGuest" checks if the user is connected and if so, triggers an "onChange" event
	 * with a specified URL.
	 * @returns If the `isConnected()` method returns `true`, then the `onChange()` method is called with
	 * an object containing the `url` property set to '/me'. If `isConnected()` returns `false`, then
	 * nothing is returned.
	 */
	checkGuest()
	{
		if(this.isConnected()) {
			this.onChange({
				url: '/me'
			});
			return;
		}
	}

	/**
	 * The function checks if a token is stored in the local storage and returns true if it exists,
	 * otherwise it returns false.
	 * @returns The function `isConnected()` returns the value of `localStorage.getItem('token')` if it
	 * exists, otherwise it returns `false`.
	 */
	isConnected()
	{
		return localStorage.getItem('token') ?? false;
	}

	/**
	 * The setToken function stores a token in the browser's local storage.
	 * @param token - The token parameter is a string that represents a token value.
	 */
	setToken(token)
	{
		localStorage.setItem('token', token);
	}

	/**
	 * The logout function removes the token from local storage and redirects the user to the home page.
	 * @returns nothing (undefined).
	 */
	logout()
	{
		localStorage.removeItem('token');
		
		this.onChange({
			url: '/'
		})
		return;
	}
}