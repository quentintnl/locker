const closeLocker = document.getElementById("btn-close-locker");
const id = document.getElementById("id");
const passwordInput = document.getElementById("password-input");
const paveNumerique = document.querySelectorAll(".pave-numerique");
const submitButton = document.getElementById("btn-envoyer");
const data = [];

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
  var input = document.getElementById("passwordInput");
  if (input.type === "number") {
    input.type = "password";
  } else {
    input.type = "number";
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

closeLocker.addEventListener("click", function () {
  const request = new XMLHttpRequest();
  request.open("POST", "locker/src/index.php?id=" + id.value + "&closeOrOpen=close", true);
  request.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

  const body = JSON.stringify({
    id: id.value,
    closeOrOpen: "close",
  });
  request.onload = () => {
    if (request.readyState == 4 && request.status == 201) {
      console.log(JSON.parse(request.responseText));
    } else {
      console.log(`Error: ${request.status}`);
    }
  };
  request.send(body);
});

// Caractères à retirer :
// `
// ¨
// ^
