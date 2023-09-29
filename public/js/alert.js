function show_alert_div() {
    document.getElementById('alert_div').style.display = "block";
}

function hide_alert_div() {
    document.getElementById('alert_div').style.display = "none";
}

var isShown = false;

function show_hide() {
    if (isShown) {
        hide_alert_div();
        isShown = false;
    } else {
        show_alert_div();
        isShown = true;
    }
}