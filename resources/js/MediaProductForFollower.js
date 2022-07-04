export default class MediaProductForFollower {
    index() {
        try {
            let btn = document.getElementById('open_panel_btn');
            btn.addEventListener("click", function () {
                let arrowLeftDisplay = document.getElementById('arrow_panel_left');
                let arrowRightDisplay = document.getElementById('arrow_panel_right');
                let display = document.getElementById('media_panel');
                display.classList.toggle('block');

                if (arrowLeftDisplay.style.display == '' || arrowLeftDisplay.style.display == 'block') {
                    arrowLeftDisplay.style.display = 'none';
                    arrowRightDisplay.style.display = 'block';
                } else {
                    arrowLeftDisplay.style.display = 'block';
                    arrowRightDisplay.style.display = 'none';
                }
                MediaProductForFollower.prototype.controls();
            });
        } catch (err) {
            console.log()
        }
    }

    controls() {
        try {
            let controls = document.getElementById('controls');
            if ('controls' in controls.attributes) {
                controls.removeAttribute('controls');
            } else {
                controls.setAttribute('controls', '');
            }
        } catch (err) {
            console.log()
        }
    }
}

