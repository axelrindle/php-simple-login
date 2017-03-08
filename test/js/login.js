$(document).ready(function () {
  $("#loginform").submit(function () { // when the user clicks the submit button in the form

    var user = $("#login__username").val(); // login form username field
    var pass = $("#login__password").val(); // login form password field

    // no check for empty fields because this can be done in HTML by adding 'required' attribute to an input element

    // request login
    $.post("login.php", { mode: "login", user: user, password: pass }, function (data) {
      if (data === "success") {
        window.location.href = "/";
      } else {
        alert(data); // should be removed. just for debugging
      }
    });

    return false; // return false to prevent the page from reloading
  });
});
