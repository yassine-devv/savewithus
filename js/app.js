function verify_login() {
  let username = document.forms["form-log"]["username"].value;
  let password = document.forms["form-log"]["password"].value;
  
  if (username == "" || password == "") {
    alert("Rieampire i campi richiesti");
    return false;
  }
}

function verify_reg(){
  let nome = document.forms["form-reg"]["nome"].value;
  let cognome = document.forms["form-reg"]["cognome"].value;
  let email = document.forms["form-reg"]["email"].value;
  let tel = document.forms["form-reg"]["tel"].value;
  let username = document.forms["form-reg"]["username"].value;
  let password = document.forms["form-reg"]["password"].value;
  console.log(password.length);
  
  //return false;
  
  if(empty(nome) || empty(cognome) || empty(email) || empty(tel) || empty(username) || empty(password)){
    alert("Rieampire tutti i campi richiesti");
    return false;
  }else{
    if(!email.includes("@")){
      alert("Inserire un email valida");
      return false;
    }else{
      if(tel.length!==10){
        alert("Inserire un numero valido");
        return false;
      }else{
        if(password.length < 8){
          alert("La password deve essere lunga almeno 8 caratteri");
          return false;
        }
      }
    }
  }
  
}

function empty(stringa){
  if(stringa==""){
    return true;
  }
  return false;
}

function viewpass(){
  console.log("ciao");
  let input = document.getElementById('inp-pass');
  let btn = document.getElementById('btn-viewpass');
  //let icon = document.getElementById("icon-eye");
  console.log(input.type)

  if(input.type == "text"){
    input.type = "password";
    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16"><path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/></svg>';
  }else{
    input.type = "text";
    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" id="icon-eye" class="bi bi-eye-slash-fill" viewBox="0 0 16 16"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z" /><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z" /></svg>';
  }
}