const password = document.getElementById("password");
const pave_numerique = document.querySelectorAll(".pave-numerique");
const toastTrigger = document.getElementById("btn-envoyer");
const right_password = "1234";
const data = [];

pave_numerique.forEach(function (button) {
  button.addEventListener("click", function () {
    let buttonValue = button.value;
    if (buttonValue === "cancel") {
      password.value = data.pop();
      password.value = data.join("");
    } else {
      data.push(buttonValue);
      password.value = data.join("");
    }
  });
});

toastTrigger.addEventListener("click", function () {
  if (data.length === 0) {
    return null;
  } else if (right_password === password.value) {
    console.log("Le code est correct ! Veuillez récupérer votre colis.");
  } else if (right_password !== password.value) {
    console.log("Le code est incorrect ! Veuillez réessayer.");
  }
});

function hidePassword() {
  var input = document.getElementById("password");
  if (input.type === "text") {
    input.type = "password";
  } else {
    input.type = "text";
  }
}

const request = new XMLHttpRequest();
request.open("POST", "locker/index.php?password=" + password.value, true);
request.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

const body = JSON.stringify({
  id: 1,
  password: password.value,
  name: "locker1",
  status: false,
  created_at: new Date(),
});
request.onload = () => {
  if (request.readyState == 4 && request.status == 201) {
    console.log(JSON.parse(request.responseText));
  } else {
    console.log(`Error: ${request.status}`);
  }
};
request.send(body);
