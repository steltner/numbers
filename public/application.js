window.onload = function () {
    let video = document.getElementById('Video');
    let pause = document.getElementById('Pause');

    let defaultNumber = document.getElementById('DefaultNumber');
    let number = document.getElementById('Number');
    let dateNumber = document.getElementById('DateNumber');

    let form = document.getElementById('Form');

    let result = document.getElementById('Result');

    let radios = document.querySelectorAll('input[type=radio][name="type"]');

    video.playbackRate = 0.40;

    pause.addEventListener('click', function () {
        switchVideo();
    });

    window.addEventListener('keydown', function (event) {
        if (event.code === 'Pause') {
            event.preventDefault();

            switchVideo();
        }
    });

    radios.forEach(radio => radio.addEventListener('change', function () {
        switch (radio.value) {
            case 'date':
                defaultNumber.style.display = 'none';
                dateNumber.style.display = 'block';
                break;
            case 'year':
                number.placeholder = 'Random year';
                defaultNumber.style.display = 'block';
                dateNumber.style.display = 'none';
                break;
            default:
                number.placeholder = 'Random number';
                defaultNumber.style.display = 'block';
                dateNumber.style.display = 'none';
        }
    }));

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        let formData = new FormData(form);

        let url = formData.get('type');

        if (url === 'date') {
            let day = formData.get('day');
            let month = formData.get('month');

            if (day && month) {
                url += '/' + day + '/' + month;
            }
        } else {
            let number = formData.get('number');

            if (number) {
                url += '/' + number;
            }
        }

        let language = formData.get('language');

        if (language && language !== 'en') {
            url += '?language=' + language;
        }

        getFact('/' + url)
    });

    function switchVideo() {
        if (video.paused) {
            video.play();
            pause.innerHTML = '&#x23f8';
        } else {
            video.pause();
            pause.innerHTML = '&#x23f5';
        }
    }

    function getFact(url) {
        fetch(url)
            .then(response => response.json())
            .then(jsonResponse => {
                result.style.color = 'found' in jsonResponse && jsonResponse.found === true ? 'green' : 'red';
                result.innerHTML = jsonResponse.message;
                result.title = 'original' in jsonResponse ? jsonResponse.original : '';
            });
    }
};
