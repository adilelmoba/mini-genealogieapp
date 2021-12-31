<?php

// SE CONNECTER A LA BASE DE DONNEES
$serveur = 'localhost';
$login_db = 'root';
$password_db = '';
$database = 'genealogieapp_db';

$lien = mysqli_connect($serveur, $login_db, $password_db, $database);

if (!$lien) {
  die("Connection failed: " . mysqli_connect_error());
} else {
  // echo 'Database connected! 👍';
}

// DECLARATION DES VARIABLES GLOBAL
global $global_nom;
global $global_prenom;

$GLOBALS["nom"] = "";
$GLOBALS["prenom"] = "";

if (isset($_POST['envoyer'])) {
  // VERIFIER LE CONTENU DES INPUTS
  $nom = mysqli_real_escape_string($lien, $_POST['nom']);
  $prenom = mysqli_real_escape_string($lien, $_POST['prenom']);
  $date_de_naissance = mysqli_real_escape_string($lien, $_POST['date_de_naissance']);

  $nom = strtoupper($nom);
  $prenom = ucfirst($prenom);

  $sql = "INSERT INTO famille VALUES(NULL, '$nom', '$prenom', '$date_de_naissance')";
  $resultat = mysqli_query($lien, $sql);

  if (!$resultat) {
    echo "Erreur SQL! : $sql <br>" . mysqli_error($lien);
  } else {
    // echo 'Inserted Successfully! 🤞';

    // REMPLIR LES VARIABLES GLOBAL POUR LES UTILISER APRES, POUR L'AFFICHAGE
    $global_nom = $nom;
    $global_prenom = $prenom;
  }
}

// TESTER POUR AVOIR EST CE QUE C'EST UNE VISITE OU AFFICHAGE APRES L'INSERTION DU MEMBRE FAMILLE
if (empty($global_nom)) {
  $sql = "SELECT DISTINCT nom FROM famille";
} else {
  $sql = "SELECT DISTINCT nom FROM famille WHERE nom = '$nom'";
}

$resultat = mysqli_query($lien, $sql);

if (!$resultat) {
  echo "Erreur SQL! : $sql <br>" . mysqli_error($lien);
} else {
  // echo 'Selection de noms Successfully! 🤞';
}

$noms_array = [];
while ($nom = mysqli_fetch_array($resultat)) {
  $noms_array[] = $nom[0];
}

// UTILISATION DE VARIABLES POUR PERSONNALISER L'AFFICHAGE
$afficher = false;
$pour_trier = true;

if (isset($_POST['trier'])) {
  $pour_trier = false;
  $afficher = true;

  if (
    isset($_POST['select_family']) &&
    isset($_POST['choix']) &&
    isset($_POST['select_mode'])
  ) {

    $family = $_POST['select_family']; // 1ERE OPTION : quelle famille
    $choix = $_POST['choix']; // 2EME OPTION: prenom/date
    $mode = $_POST['select_mode']; // 3EME OPTION: quel mode

    $sql = "SELECT * FROM famille WHERE nom = '$family' ORDER BY id_membre $mode";

    $resultat = mysqli_query($lien, $sql);
    $membres = mysqli_fetch_all($resultat, MYSQLI_ASSOC);

    if (!$resultat) {
      echo "Erreur SQL! : $sql <br>" . mysqli_error($lien);
    } else {
      // GOOD 💪
    }

    // DU CODE REPETE, MAIS POUR "readability" ET LA FACILITE DE LA LECTURE DE CODE
    if ($choix === "prenom") {
      $prenoms = [];
      $j = 0;
      foreach ($membres as $membre) {
        $prenoms[$j] = $membre['date_de_naissance'];
        $j++;
      }
      asort($prenoms);
    } elseif ($choix === "date") {
      $dates = [];
      $i = 0;
      foreach ($membres as $membre) {
        $dates[$i] = $membre['date_de_naissance'];
        $i++;
      }
      asort($dates);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php if (!empty($global_nom)) { ?>
    <title><?= $global_prenom ?> a été ajouté avec succès!</title>
  <?php } elseif ($afficher) { ?>
    <title>
      La généalogie de la famille <?= $family; ?>
    </title>
  <?php } else { ?>
    <title>GenealogieApp | Genealogie</title>
  <?php } ?>


  <!-- FLAVICO -->
  <link rel="shortcut icon" href="assets/images/navicon.ico">
  <link rel="apple-touch-icon" sizes="128x128" type="image/x-icon" href="assets/images/navicon.ico">

  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

  <main class="main scale-up-hor-center">
    <header class="header">
      <nav class="nav">
        <div class="logo">
          <a href="/genealogieapp/">
            <h2>Genealogie<strong class="strong">App.</strong></h2>
          </a>
        </div>

        <ul class="ul">
          <li>
            <a href="/genealogieapp/">Famille</a>
          </li>

          <li>
            <a href="genealogie" class="active">Genealogie</a>
          </li>
        </ul>
      </nav>
    </header>

    <section class="section">
      <div class="section_content section__content-genealogie">

        <!-- 
          DES CONDITIONS POUR AVOIR QUEL TYPE D'AFFICHAGE SERAIT UTILISE :

          if(!empty($global_nom)) : Affichage aprés insertion d'un membre famille
          elseif($afficher) : Affichage du tri
          else : Affichage d'une visite normale
        -->
        <?php if (!empty($global_nom)) { ?>
          <h1 class="h1-genealogie"><strong><?= $global_prenom . ' ' . $global_nom ?></strong> a été ajouté avec succès! 😉</h1>
          <p>Vous pouvez maintenant suivre les instructions pour voir l'arbre de votre famille</p>

        <?php } elseif ($afficher) { ?>
          <h1 class="h1-genealogie">La généalogie de la famille <strong><?= $family; ?></strong></h1>
          <p>Le tri selon vos choix par: <strong><?= ucfirst($choix) ?></strong>
            et mode:
            <strong><?php echo $mode === "ASC" ? "Accroissant" : "Décroissant"; ?></strong>
          </p>

        <?php } else { ?>
          <h1 class="h1-genealogie">Bienvenue à nouveau</h1>
          <p>Aidez-nous à trier votre famille</p>
        <?php } ?>

        <?php if ($pour_trier) { ?>
          <div class="section_card">
            <form method="POST" class="form-genealogie" autocomplete="off">

              <div class="input__container input__container-active">
                <strong class="number__strong">1</strong>

                <div class="select__container">
                  <select name="select_family" id="select" class="select family">
                    <option value="empty">Sélectionner votre famille:</option>

                    <!-- 
                        AFFICHAGE DE FAMILLES A PARTIR DE LA BASE DE DONNEES
                        
                        LE MOT "DISTINCT" EST POUR EVITER LA REPETITION D'AFFICHAGE DU NOM DE FAMILLE
                        ($sql = "SELECT DISTINCT nom FROM famille";)
                      -->
                    <?php foreach ($noms_array as $nom) : ?>
                      <option value="<?= $nom; ?>"><?= $nom; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

              </div>

              <div class="input__container">
                <strong class="number__strong">2</strong>

                <div class="select__container radio">
                  <label for="date">Par prenom: </label>
                  <input type="radio" name="choix" id="prenom" value="prenom">

                  <label for="date">Par date: </label>
                  <input type="radio" name="choix" id="date" value="date">

                </div>
              </div>

              <div class="input__container">
                <strong class="number__strong">3</strong>

                <div class="select__container">
                  <select name="select_mode" id="select" class="select mode">
                    <option value="empty">Sélectionner quel mode:</option>
                    <option value="ASC">Croissant</option>
                    <option value="DESC">Decroissant</option>
                  </select>
                </div>
              </div>

              <input type="submit" name="trier" id="trier" value="Trier" disabled>

            </form>
          </div>
        <?php } ?>

        <!--
          DES CONDITIONS POUR PERSONNALISER L'AFFICHAGE DE TRI, SE BASANT SUR LE CHOIX ET LE MODE

          array_reverse(); : POUR INVERSER LE TABLEAU
          date("d-m-Y", strtotime($date)) : POUR FORMATTER LES DATES

          DU CODE REPETE, MAIS POUR "readability" ET LA FACILITE DE LA LECTURE DE CODE
        -->
        <?php if ($afficher) { ?>
          <?php
          if ($mode === "ASC" && $choix === "date") {
            foreach (array_reverse($dates) as $date) {
              echo '
                <div class="tri">
                  <h4>' .  date("d-m-Y", strtotime($date)) . '</h4>
                </div>
                ';
            }
          } elseif ($mode === "DESC" && $choix === "date") {
            foreach ($dates as $date) {
              echo '
                <div class="tri">
                  <h4>' .  date("d-m-Y", strtotime($date)) . '</h4>
                </div>
                ';
            }
          } elseif ($mode === "ASC" && $choix === "prenom") {
            foreach (array_reverse($prenoms) as $prenom) {
              foreach ($membres as $membre) {
                if ($membre['date_de_naissance'] === $prenom) {
                  echo '
                    <div class="tri">
                      <h4>' .  $membre['prenom'] . '</h4>
                    </div>
                    ';
                }
              }
            }
          } elseif ($mode === "DESC" && $choix === "prenom") {
            foreach ($prenoms as $prenom) {
              foreach ($membres as $membre) {
                if ($membre['date_de_naissance'] === $prenom) {
                  echo '
                    <div class="tri">
                      <h4>' .  $membre['prenom'] . '</h4>
                    </div>
                    ';
                }
              }
            }
          }
          ?>
        <?php } ?>

      </div>
      <img class="tree-img" src="assets/images/family-tree.png" alt="Image Arbre généalogique">
    </section>
  </main>

  <!-- JAVASCRIPT -->
  <script src="assets/js/genealogie.js"></script>
</body>

</html>