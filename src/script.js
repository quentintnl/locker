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

// Jeu de Ping Pong d'Issa

document.addEventListener("DOMContentLoaded", () => {
  // Déclarations et initialisation des variables
  const raquetteGauche = document.getElementById("pong-paddle-left");
  const raquetteDroite = document.getElementById("pong-paddle-right");
  const balle = document.getElementById("pong-ball");
  const scoreJoueurElement = document.getElementById("pong-player-score");
  const scoreBotElement = document.getElementById("pong-bot-score");
  const boutonRestart = document.getElementById("pong-restart-button");

  let scoreJoueur = 0;
  let scoreBot = 0;

  const vitesseBalleInitiale = 4;
  let vitesseBalle = vitesseBalleInitiale;
  let posXballe = 290;
  let posYballe = 190;
  let directionXBalle = 1;
  let directionYBalle = 1;

  const vitesseRaquette = 28;
  let posYraquetteGauche = 168;
  let posYraquetteDroite = 168;

  let intervalleJeu;
  let partieDemarree = false;
  let partieTerminee = false;

  // Création de l'objet bot pour contrôler la raquette de droite
  const bot = {
    update: function () {
      deplacerRaquetteBot();
    },
  };

  boutonRestart.addEventListener("click", () => {
    if (!partieDemarree || partieTerminee) {
      demarrerPartie();
      partieDemarree = true;
      partieTerminee = false;
      boutonRestart.style.display = "none";
    } else {
      reinitialiserPartie();
      demarrerPartie();
    }
  });

  function reinitialiserPartie() {
    reinitialiserBalle();
    reinitialiserRaquettes();
  }

  // Écouteur d'événement pour la touche enfoncée
  document.addEventListener("keydown", (event) => {
    if (event.code === "ArrowDown") {
      event.preventDefault();
      deplacerRaquetteBas();
    } else if (event.code === "ArrowUp") {
      event.preventDefault();
      deplacerRaquetteHaut();
    }
  });
  // Écouteur d'événement pour la touche relâchée
  document.addEventListener("keyup", (event) => {
    if (event.code === "ArrowDown" || event.code === "ArrowUp") {
      event.preventDefault();
      arreterRaquette();
    }
  });

  // Fonction pour démarrer la partie
  function demarrerPartie() {
    reinitialiserBalle();
    reinitialiserRaquettes();
    boutonRestart.style.display = "none";
    intervalleJeu = setInterval(mettreAJourJeu, 20);
    partieDemarree = true;
    partieTerminee = false;
  }

  // Fonction pour mettre à jour l'état du jeu
  function mettreAJourJeu() {
    deplacerBalle();
    deplacerRaquettes();
    verifierCollision();
    verifierFinManche();
    bot.update();
  }

  // Fonction pour déplacer la balle
  function deplacerBalle() {
    posXballe += vitesseBalle * directionXBalle;
    posYballe += vitesseBalle * directionYBalle;
    balle.style.left = posXballe + "px";
    balle.style.top = posYballe + "px";
  }

  // Fonction pour déplacer les raquettes
  function deplacerRaquettes() {
    raquetteGauche.style.top = posYraquetteGauche + "px";
    raquetteDroite.style.top = posYraquetteDroite + "px";
  }

  // Vitesse de déplacement réduite pour une raquette plus fluide
  const vitesseRaquetteBot = 14;

  function deplacerRaquetteBot() {
    // Ajustement correct de la raquette en fonction de la position estimée de la balle
    if (posYraquetteDroite + 32 > posYballe + 35 && posYraquetteDroite > 0) {
      posYraquetteDroite -= vitesseRaquetteBot;
    } else if (posYraquetteDroite + 32 < posYballe - 35 && posYraquetteDroite + 64 < 336) {
      posYraquetteDroite += vitesseRaquetteBot;
    }

    // Appeler la fonction de déplacement du bot à nouveau pour créer une boucle continue
    requestAnimationFrame(deplacerRaquetteBot);
  }

  // Appeler la fonction de déplacement du bot pour la première fois
  requestAnimationFrame(deplacerRaquetteBot);

  // Fonction pour vérifier la collision de la balle avec les raquettes et les murs
  function verifierCollision() {
    // Collision avec les raquettes
    if (posXballe <= 30 && posYballe + 10 >= posYraquetteGauche && posYballe <= posYraquetteGauche + 64) {
      directionXBalle = 1;
      augmenterVitesseBalle();
    } else if (posXballe >= 560 && posYballe + 10 >= posYraquetteDroite && posYballe <= posYraquetteDroite + 64) {
      directionXBalle = -1;
      augmenterVitesseBalle();
    }

    // Collision avec les murs supérieur et inférieur
    if (posYballe <= 0 || posYballe >= 380) {
      directionYBalle *= -1;
    }
  }

  // Fonction pour augmenter la vitesse de la balle
  function augmenterVitesseBalle() {
    if (vitesseBalle < 12) {
      vitesseBalle += 0.25;
    }
  }

  // Fonction pour mettre à jour le tableau des scores
  function mettreAJourTableauScores() {
    scoreJoueurElement.textContent = scoreJoueur;
    scoreBotElement.textContent = scoreBot;
  }

  // Fonction pour marquer un point et mettre à jour le score
  function marquerPoint(scoreElement) {
    const score = parseInt(scoreElement.textContent);
    scoreElement.textContent = score + 1;
  }

  // Fonction pour vérifier la fin de la manche
  function verifierFinManche() {
    if (posXballe <= 0) {
      marquerPoint(scoreBotElement);
      afficherAlerte("Le bot a marqué un point !");
      stopperPartie();
      reinitialiserBalle();
      boutonRestart.style.display = "block";
    } else if (posXballe >= 585) {
      marquerPoint(scoreJoueurElement);
      afficherAlerte("Vous avez marqué un point !");
      stopperPartie();
      reinitialiserBalle();
      boutonRestart.style.display = "block";
    }
  }

  // Fonction pour afficher une alerte
  function afficherAlerte(message) {
    alert(message);
  }

  // Fonction pour réinitialiser la position de la balle
  function reinitialiserBalle() {
    posXballe = 290;
    posYballe = 190;
    directionXBalle = 1;
    directionYBalle = 1;
    vitesseBalle = vitesseBalleInitiale;
  }

  // Fonction pour réinitialiser la position des raquettes
  function reinitialiserRaquettes() {
    posYraquetteGauche = 168;
    posYraquetteDroite = 168;
  }

  // Fonction pour arrêter la partie
  function stopperPartie() {
    clearInterval(intervalleJeu);
  }

  // Fonction pour déplacer la raquette vers le bas
  function deplacerRaquetteBas() {
    if (posYraquetteGauche < 336) {
      posYraquetteGauche += vitesseRaquette;
    }
  }

  // Fonction pour déplacer la raquette vers le haut
  function deplacerRaquetteHaut() {
    if (posYraquetteGauche > 0) {
      posYraquetteGauche -= vitesseRaquette;
    }
  }

  // Fonction pour arrêter le déplacement de la raquette
  function arreterRaquette() {
    // Ne fait rien
  }
});
