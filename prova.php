<?php
include "./functions.php";

?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
  * {
    box-sizing: border-box;
  }

  body {
    background-color: #f1f1f1;
  }

  #segnalaForm {
    background-color: #ffffff;
    margin: 100px auto;
    font-family: Raleway;
    padding: 40px;
    width: 70%;
    min-width: 300px;
    display: grid;
  }

  #segnalaForm h1 {
    text-align: center;
  }

  #segnalaForm input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
  }

  #segnalaForm textarea {
    font-size: 17px;
    padding: 10px;
    font-family: Raleway;
    width: 100%;
    resize: none;
  }

  #segnalaForm .sez-gr {
    display: grid;
    column-gap: 50px;
    grid-template-columns: auto auto auto;
  }

  /* Mark input boxes that gets an error on validation: */
  input.invalid {
    background-color: #ffdddd;
  }

  /* Hide all steps by default: */
  .tab {
    display: none;
  }

  button {
    background-color: #04AA6D;
    color: #ffffff;
    border: none;
    padding: 10px 20px;
    font-size: 17px;
    font-family: Raleway;
    cursor: pointer;
  }

  button:hover {
    opacity: 0.8;
  }

  #prevBtn {
    background-color: #bbbbbb;
  }

  /* Make circles that indicate the steps of the form: */
  .step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
  }

  .step.active {
    opacity: 1;
  }

  /* Mark the steps that are finished and valid: */
  .step.finish {
    background-color: #04AA6D;
  }

  .custom-select {
    width: 100%;
    position: relative;
  }

  .custom-select select {
    appearance: none;
    /*  safari  */
    -webkit-appearance: none;
    /*  other styles for aesthetics */
    width: 100%;
    font-size: 1.15rem;
    padding: 0.675em 6em 0.675em 1em;
    background-color: #fff;
    border: 1px solid #caced1;
    border-radius: 0.25rem;
    cursor: pointer;
  }

  .custom-select::before,
  .custom-select::after {
    --size: 0.3rem;
    content: "";
    position: absolute;
    right: 1rem;
    pointer-events: none;
  }

  .custom-select::before {
    border-left: var(--size) solid transparent;
    border-right: var(--size) solid transparent;
    border-bottom: var(--size) solid black;
    top: 40%;
  }

  .custom-select::after {
    border-left: var(--size) solid transparent;
    border-right: var(--size) solid transparent;
    border-top: var(--size) solid black;
    top: 55%;
  }
</style>

<body>

  <form id="segnalaForm" action="/action_page.php">
    <h1>Segnala campagna</h1>
    <!-- One "tab" for each step in the form: -->
    <div class="tab">
      <label>Nome camapagna:</label>
      <p><input placeholder="Dai un nome alla tua campagna" oninput="this.className = ''" name="name-cmp"></p>
      <!-- <p><input placeholder="Last name..." oninput="this.className = ''" name="lname"></p> -->
      <label>Descrizione:</label>
      <p><textarea name="descrizione-camp" cols="70" rows="7" placeholder="Descrivi un po l'area"></textarea></p>
      <label>Data ritrovo:</label>
      <p>
      <div class="sez-gr">
        <div class="inp custom-select">
          <select onload="mod_mese(document.getElementById('slct_mese').value)" id="slct_day" name="m-data"></select>
          <!-- <input type="number" name="d-data"  placeholder="Data" max="31"> -->
        </div>
        <div class="inp custom-select">
          <select onchange="mod_mese(this.value)" id="slct_mese" name="m-data">
            <?php
            foreach ($mesi as $m) {
              ?>
              <option value="<?= array_search($m, $mesi) + 1 ?>"><?php echo $m ?></option>
              <?php
            }
            ?>
          </select>
          <!-- <input type="number" name="m-data"  placeholder="Mese" max="12"> -->
        </div>
        <div class="inp">
          <input type="number" id="inp-anno" onkeyup="verify_anno(this.value)" name="y-data" placeholder="Anno">
          <span id="invalid-y"></span>
        </div>
      </div>
      </p>
    </div>
    <div class="tab">
      <label>Scegli delle foto che rappresentano l'area che vuoi segnalare:</label><br>
      <p><input type="file" name="files[]" multiple></p>
    </div>
    <div class="tab">Birthday:
      <p><input placeholder="dd" oninput="this.className = ''" name="dd"></p>
      <p><input placeholder="mm" oninput="this.className = ''" name="nn"></p>
      <p><input placeholder="yyyy" oninput="this.className = ''" name="yyyy"></p>
    </div>
    <div style="overflow:auto;">
      <div style="float:right;">
        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Indietro</button>
        <button type="button" id="nextBtn" onclick="nextPrev(1)">Avanti</button>
      </div>
    </div>
    <!-- Circles which indicates the steps of the form: -->
    <div style="text-align:center;margin-top:40px;">
      <span class="step"></span>
      <span class="step"></span>
      <span class="step"></span>
    </div>
  </form>

  <script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
      // This function will display the specified tab of the form...
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "block";
      //... and fix the Previous/Next buttons:
      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Invia";
      } else {
        document.getElementById("nextBtn").innerHTML = "Avanti";
      }
      //... and run a function that will display the correct step indicator:
      fixStepIndicator(n)
    }

    function nextPrev(n) {
      // This function will figure out which tab to display
      var x = document.getElementsByClassName("tab");
      // Exit the function if any field in the current tab is invalid:
      if (n == 1 && !validateForm()) return false;
      // Hide the current tab:
      x[currentTab].style.display = "none";
      // Increase or decrease the current tab by 1:
      currentTab = currentTab + n;
      // if you have reached the end of the form...
      if (currentTab >= x.length) {
        // ... the form gets submitted:
        document.getElementById("segnalaForm").submit();
        return false;
      }
      // Otherwise, display the correct tab:
      showTab(currentTab);
    }

    function validateForm() {
      // This function deals with validation of the form fields
      var x, y, i, valid = true;
      x = document.getElementsByClassName("tab");
      y = x[currentTab].getElementsByTagName("input");
      // A loop that checks every input field in the current tab:
      for (i = 0; i < y.length; i++) {
        // If a field is empty...
        if (y[i].value == "") {
          // add an "invalid" class to the field:
          y[i].className += " invalid";
          // and set the current valid status to false
          valid = false;
        }
      }

      if (x[currentTab].getElementsByTagName("textarea").value == "") {
        valid = false;
      }
      // If the valid status is true, mark the step as finished and valid:
      if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
      }
      return valid; // return the valid status
    }

    function fixStepIndicator(n) {
      // This function removes the "active" class of all steps...
      var i, x = document.getElementsByClassName("step");
      for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
      }
      //... and adds the "active" class on the current step:
      x[n].className += " active";
    }

    const d = new Date();
    let y = d.getFullYear();
    let dd = d.getDay();
    let mm = d.getMonth();
    
    window.onload = mod_mese(document.getElementById('slct_mese').value);
    window.onload = document.getElementById('inp-anno').value = y;

    function mod_mese(m) {
      let giorni_mesi = { '1': '31', '2': '28', '3': '31', '4': '30', '5': '31', '6': '30', '7': '31', '8': '31', '9': '30', '10': '31', '11': '30', '12': '31' };

      document.getElementById('slct_day').innerHTML = "";

      for (let i = 1; i <= parseInt(giorni_mesi[m]); i++) {
        document.getElementById('slct_day').innerHTML += '<option value="' + i + '">' + i + '</option>';
      }

    }

    function verify_anno(anno) {
      const d = new Date();
      let y = d.getFullYear();
      if (anno < y) {
        document.getElementById('invalid-y').innerHTML = "Anno non valido.";
      } else {
        document.getElementById('invalid-y').innerHTML = "";

      }
    }
  </script>

</body>

</html>