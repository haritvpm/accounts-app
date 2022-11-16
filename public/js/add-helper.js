
function calcSum() {
    var sum = 0;
    $(".total_bill").each(function () {
        if (!isNaN(this.value) && this.value.length != 0) {
            sum += parseFloat(this.value);
        }
        $("#pwn").html('Total: <span style="color:blue">' + toIndianCurrency(sum) + '</span>');
    });
}

$(document).ready(function () {
    calcSum();
    $(".total_bill").each(function () {
        $(this).keyup(function () {
            calcSum();
        });
    });
    var other_input = document.getElementById("other");
});

function addOther() {
    var sumOther = 0;
    var other = $('#other').val().split('+')
    // alert($(this).val())

    for (var a in other) {
        var s = other[a]
        // alert(s)

        if (!isNaN(s) && s.length != 0) {
            sumOther += parseFloat(s);
        }
    }
    // alert(sumOther)
    $("#other").val(sumOther);
}

$('#other').keypress(function (event) {

    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == '13' || keycode == '61'/*= key*/) {

        event.preventDefault();
        addOther();
    }

});
$('#other').blur(function () {
    addOther();
});
function toIndianCurrency(num) {
    const curr = num.toLocaleString('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 0,
    });
    return curr.substr(1);;
};