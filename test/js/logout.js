$(document).ready(function () {
  $("#logout").click(function () { // when the user clicks the logout button

    // request logout
    $.post("auth.php", { mode: "logout" }, function (data) {
      if (data === "success") {
        window.location.href = "/";
      } else {
        alert(data); // should be removed. just for debugging
      }
    });

    return false; // return false to prevent the page from reloading
  });
});
