document.addEventListener("DOMContentLoaded", function () {
    const logoButton = document.querySelector(".navbar-toggler");
    const userPanelModal = document.getElementById("userPanelModal");

    logoButton.addEventListener("click", function () {
      if (userPanelModal.classList.contains("show")) {
        // If the user panel is already open, close it
        userPanelModal.classList.remove("show");
      } else {
        // If the user panel is closed, open it
        userPanelModal.classList.add("show");
      }
    });
  });
  // Include jQuery
var scriptJQuery = document.createElement('script');
scriptJQuery.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
scriptJQuery.integrity = 'sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK';
scriptJQuery.crossOrigin = 'anonymous';
document.head.appendChild(scriptJQuery);

// Include Bootstrap JavaScript
var scriptBootstrap = document.createElement('script');
scriptBootstrap.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js';
scriptBootstrap.integrity = 'sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy';
scriptBootstrap.crossOrigin = 'anonymous';
document.head.appendChild(scriptBootstrap);