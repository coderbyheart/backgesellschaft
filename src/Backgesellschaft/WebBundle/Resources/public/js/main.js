var IndexSlider = function () {

    $('.carousel').carousel();

    return {};
};

var Product = function (p) {

    console.log(p);

    var amount = p.find('input.amount:first')[0]

    function getAmount() {
        var a = parseInt(amount.value);
        if (isNaN(a)) return 0;
        return a;
    }

    function onAmountChange() {
        if (getAmount() > 0) {
            p.addClass("selected");
        } else {
            p.removeClass("selected");
        }
    }

    $(amount).bind('change', onAmountChange);

    p.click(function (ev) {
        if (p.hasClass('selected')) return;
        amount.value = getAmount() + 1;
        onAmountChange();
    });

    return {}
}

var ProductSelector = function () {

    $('.product').each(function (index, el) {
        Product($(el));
    });

};

$(document).ready(function () {
    IndexSlider();
    ProductSelector();
});