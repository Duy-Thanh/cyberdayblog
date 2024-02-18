document.addEventListener("DOMContentLoaded", function() {
    var currentYear = new Date().getFullYear();
    var copyrightElement = document.getElementById("copyright");
    if (copyrightElement) copyrightElement.innerHTML = "Copyright &copy; 2016 - " + currentYear + "  CyberDay Studios. All right reserved. This website using <a href=\"https://www.w3schools.com/w3css/default.asp\" target=\"_blank\">w3.css</a> to render style.";
});
