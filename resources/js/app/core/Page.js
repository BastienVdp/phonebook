import EventEmitter from "events";
import { each } from "lodash";
import Application from "../application";
import gsap from "gsap";

export default class Page extends EventEmitter
{
	constructor({ 
		element, 
		elements
	}) {
		super();
		this.selector = element;
		this.selectorChildrens = { ...elements};

		this.app = new Application();
	}

	toggleButton()
	{
		this.elements.button.disabled = !this.elements.button.disabled;
	}

	initForm()
	{
		this.elements.form.addEventListener('submit', (e) => {
			e.preventDefault();
			this.toggleButton();
			this.submit();
		});
	
	}

	checkBeforeCreate()
	{
		if(
			this.app.template === 'login' || 
			this.app.template === 'register' || 
			this.app.template === 'forgotPassword'
		) {
			this.app.checkGuest();
			this.app.header.hide();
		} else {
			this.app.checkAuth();
			this.app.header.toggleButton(this.app.template);
			this.app.header.show();
		}
	}
	
	setErrors(errors)
	{
		Object.entries(errors).forEach(([key, value]) => {
			const inputElement = this.elements[key];
			const errorElement = this.elements[`error${key.charAt(0).toUpperCase() + key.slice(1)}`];

			if (inputElement && errorElement) {
				errorElement.innerHTML = value;
				inputElement.classList.add('error');
				
				inputElement.addEventListener('input', () => {
					inputElement.classList.remove('error');
					errorElement.innerHTML = '';
				});
			}
		});
	}

	async encodeImageToBase64(file) 
	{
		return new Promise((resolve, reject) => {
		  const reader = new FileReader();
	  
		  reader.onloadend = () => {
			resolve(reader.result);
		  };
	  
		  reader.onerror = reject;
	  
		  reader.readAsDataURL(file);
		});
	}
	
	create()
	{
		this.checkBeforeCreate();

		this.element = document.querySelector(this.selector);
		this.elements = {};

		each(this.selectorChildrens, (entry, key) => {
			if (
			  entry instanceof window.HTMLElement ||
			  entry instanceof window.NodeList ||
			  Array.isArray(entry)
			) {
			  this.elements[key] = entry;
			} else {
			  this.elements[key] = document.querySelectorAll(entry);
	  
			  if (this.elements[key].length === 0) {
				this.elements[key] = null;
			  } else if (this.elements[key].length === 1) {
				this.elements[key] = document.querySelector(entry);
			  }
			}
		});
	}
	
	show()
	{	
		gsap.to(this.element, {
			duration: 0.3,
			opacity: 1,
			onComplete: () => {
				return Promise.resolve();
			}
		});
	}

	hide()
	{
		this.removeAllListeners();

		gsap.to(this.element, {
			duration: 0.3,
			opacity: 0,
			onComplete: () => {
				return Promise.resolve();
			}
		});
	}
}