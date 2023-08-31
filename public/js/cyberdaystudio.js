document.addEventListener("DOMContentLoaded", function() {
    var currentYear = new Date().getFullYear();
    var copyrightElement = document.getElementById("copyright");
    if (copyrightElement) copyrightElement.innerHTML = "Copyright &copy; 2016 - " + currentYear + " CyberDay Studio. All right reserved.";
});