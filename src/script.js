const closeLocker = document.getElementById("btn-close-locker");
const id = document.getElementById("id");
const passwordInput = document.getElementById("password-input");
const paveNumerique = document.querySelectorAll(".pave-numerique");
const submitButton = document.getElementById("btn-envoyer");
const data = [];
const sleep = (delay) => new Promise((resolve) => setTimeout(resolve, delay));

paveNumerique.forEach(function (button) {
  button.addEventListener("click", function () {
    let buttonValue = button.value;
    if (buttonValue === "Effacer") {
      passwordInput.value = data.pop();
      passwordInput.value = data.join("");
    } else {
      let code = passwordInput.value + buttonValue;
      if (code >= 10000 || code.length >= 5) {
        passwordInput.value = code.substring(0, code.length - 1);
        error();
      } else {
        data.push(buttonValue);
        passwordInput.value = data.join("");
      }
    }
  });
});

function hidePassword() {
  if (passwordInput.type === "number") {
    passwordInput.type = "password";
  } else {
    passwordInput.type = "number";
  }
}

const error = async () => {
  document.getElementById("error-popup").style.display = "block";
  await sleep(10000);
  document.getElementById("error-popup").style.display = "none";
};

document.querySelector("#password-input").addEventListener("keypress", function (evt) {
  if ((evt.which != 8 && evt.which != 0 && evt.which < 48) || evt.which > 57) {
    evt.preventDefault();
  }
});

function checkDigit() {
  let code = passwordInput.value;
  if (code >= 10000 || code.length >= 5) {
    passwordInput.value = code.substring(0, code.length - 1);
    error();
  }
}
