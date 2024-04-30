function verify_login() {
  let username = document.forms["form-log"]["username"].value;
  let password = document.forms["form-log"]["password"].value;

  if (username == "" || password == "") {
    alert("Rieampire i campi richiesti");
    return false;
  }
}
