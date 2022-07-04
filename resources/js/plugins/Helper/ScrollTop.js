export class ScrollTop {
    constructor() {
        this.scrollBtn = document.querySelector('.scroll-top');

        window.addEventListener('scroll', () => { this.toggleVisibilityScrollBtn() });
    }

    toggleVisibilityScrollBtn() {
        if (window.scrollY > 400) {
            //this.scrollBtn.style.display = 'block';
            this.scrollBtn.classList.remove('hide');
        } else {
            //this.scrollBtn.style.display = 'none';
            this.scrollBtn.classList.add('hide');
        }
    }

    toScrollTop() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    }
}
