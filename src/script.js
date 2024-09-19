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
      data.push(buttonValue);
      passwordInput.value = data.join("");
    }
  });
});

function hidePassword() {
  var input = document.getElementById("passwordInput");
  if (input.type === "text") {
    input.type = "password";
  } else {
    input.type = "text";
  }
}
