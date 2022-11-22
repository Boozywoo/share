if (typeof ymaps !== 'undefined') {
    ymaps.ready(init_from);
}

function init_from() {
    // Подключаем поисковые подсказки к полю ввода.
    var suggestView = new ymaps.SuggestView('suggest_to');
    var suggestView2 = new ymaps.SuggestView('suggest_from');

    function change () {
        this.value.indexOf(this.defaultValue) && (this.value = this.defaultValue);
    }
    $("#suggest_to").on("input", change);
    $("#suggest_from").on("input", change);
}
