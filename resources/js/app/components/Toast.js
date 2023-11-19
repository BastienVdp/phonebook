import gsap from "gsap";
export default class Toast
{
	constructor()
	{
		this.element = document.querySelector('.toast');
		this.element.addEventListener('click', () => {
			this.hide();
		});
	}

	show({type, message})
	{
		gsap.fromTo(this.element, {
			y: 100,
			opacity: 0,
		}, {
			y: 0,
			opacity: 1,
			duration: 0.3,
			ease: 'power2.out',
		});
		
		if(type === 'error') {
			this.element.classList.add('toast--error');
		} else {
			this.element.classList.add('toast--success');
		}
		this.element.innerHTML = message;

		setTimeout(() => {
			this.hide();
		}, 3000);
	}

	hide()
	{
		this.element.classList.remove(['toast--error', 'toast--success'])
		gsap.to(this.element, {
			y: 100,
			opacity: 0,
			duration: 0.3,
			ease: 'power2.out',
		});
	}
}