// Подключение класса для работы со страницей отображения медиа продукта
const { default: MediaProductForFollower } = require('./MediaProductForFollower');
require('./bootstrap');

MediaProductForFollower.prototype.index();

window.onTelegramAuth = function(user) {
    window.axios({
        method: 'post',
        url: '/profile/assign/telegram',
        data: {
            user: user
        }
    }).then((resp) => {
        console.log(
            resp
        )
        document.location.reload();
    });
};

window.setLocale = function(select){
    window.axios({
        method: 'get',
        url: '/setlocale/' + select.value,
    }).then((resp) => {
        window.location.href = resp.data;
    });
}
