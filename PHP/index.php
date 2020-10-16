<!DOCTYPE html>
<link rel='stylesheet' type='text/css' href='style.css' />
<header>MODULE 159</header>
<!--Formulaire-->
<form action="" method="post">
    <div class="container">
        <div class="center">
            <p class="title">Choisisez entre importer les données depuis Active directory vers la Base de données ou de supprimer les données de la base de données</p>
            <p><input type="submit" class="shiny-btn" value="Importer les données" name="btn"></p>
            <p><input type="submit" class="shiny-btn" value="Supprimer les données" name="btn"></p>
        </div>
    </div>
</form>

<!--CODE PHP-->
<?php
// Détection de la pression d'un bouton par l'utilisateur
if (isset($_REQUEST['btn'])) {

    echo "<h2> Console :</h2>";
    // Traitement du choix du bouton
    switch ($_REQUEST['btn']) {
            // Bouton import
        case "Importer les données":

            //paramètre de connexion
            //Utilisateur (ici Administrator)
            $ldap_dn = "cn=Administrator,cn=Users,dc=M159LDAP,dc=LOCAL";
            //Mot de passe
            $ldap_password = "Admlocal1";

            // Assigne et ouvre la connexion
            //$ldap_con = ldap_connect("ldap://localhost:10389");
            $ldap_con = ldap_connect("M159LDAP.LOCAL");

            //Parfois choisir la version du protocol
            //ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);

            //info
            echo "<p class='info'>Connexion à active directory .  .  .<br></p>";

            //Connection
            if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
                //info
                echo "<p class='success'> → Connection réussie</p>";
                echo "<p class='info'>importation des utilisateurs .  .  .<br></p>";

                //récupération des utilisateur
                $entries = getUsers($ldap_con);

                //info
                echo "<p class='success'> → " . (count($entries) - 1) . " utilisateurs importés<br></p>";

                //Afficher tout les utilisateurs
                //displayList($entries);

                //Ouverture de la connexion
                $connexion = dbConnect();

                //Création et insertion de tout les utilisateurs (l'entrée 0 est vide, donc on l'évite)
                //info
                echo "<p class='info'>insertion des nouveaux utilisateurs .  .  .<br></p>";
                $count = 0;
                foreach ($entries as $user) {
                    if ($count != 0) {
                        $SQLuser = generateUser($user);

                        $query = "INSERT INTO tb_user 
                        VALUES(null," .
                            $SQLuser['role'] . ",'" .
                            $SQLuser['nom']  . "','" .
                            $SQLuser['prenom'] . "','" .
                            $SQLuser['username'] . "','" .
                            $SQLuser['mail'] . "'," .
                            $SQLuser['tel'] . ");";
                        //Exécution et vérification de la requête
                        if ($connexion->query($query) === TRUE) {
                            //rien à signaler
                        } else {
                            //Erreur
                            echo "<p class='fail'>Erreur : " . $query . "<br>" . $connexion->error . "</p>";
                        }
                    }
                    $count++;
                }

                //info
                echo "<p class='success'> → " . ($count - 1) . " nouveaux utilisateurs</p>";

                //Fermeture de la connexion
                $connexion->close();
            } else {
                //info
                echo "<p class='fail'>Echec de connection à l'ective directory<br></p>";
            }
            break;
            // Bouton de suppression
        case "Supprimer les données":

            //Ouverture de la connexion
            $connexion = dbConnect();

            //info
            echo "<p class='info'>Suppression des entrées dans tb_user . . .<br></p>";

            //Compte des entrées
            $query = "SELECT count(*) as total from tb_user;";
            //Exécution de la requête
            $result = $connexion->query($query);
            //Réception des données de la requête
            $data = mysqli_fetch_assoc($result);

            //Suppression de donnée
            $query = "DELETE FROM tb_user;";
            //Exécution de la requête
            $connexion->query($query);

            //info
            echo "<p class='success'> → " . $data['total'] . " entrées supprimées<br></p>";

            //Fermeture de la connexion
            $connexion->close();
            break;
    }
}

/**
 * 
 * Affiche une liste
 * 
 * @param array liste à afficher
 */
function displayList($liste)
{
    print "<pre>";
    print_r($liste);
    print "<pre>";
}

/**
 * 
 * Cherche tout les utilisateurs et les enregistre dans une array
 * 
 * 
 * @param connexion onnexion au serveur ldap
 * @return array une liste d'utilisateurs
 */
function getUsers($connexion)
{
    //filtre (* pour tout les utilisateurs)
    $filter = "(cn=*)";
    //Recherche
    $result = ldap_search($connexion, "ou=M159,dc=M159LDAP,dc=LOCAL", $filter) or exit("<p class='fail'>Recherche échouée</p>");
    //Récupération du résultat
    return ldap_get_entries($connexion, $result);
}

/**
 * 
 * Génère un utilisateur prêt a être envoyer dans la base de donnée
 * 
 * Recherche d'info utilisateur :
 * entries = la liste d'utilisateurs
 * chaque utilisateurs dispose d'arrays portant le nom de l'information qu'elles contiennent
 * chaque info de l'array est à la position 0
 * exemple : $entries[0][telephonenumber][0] (donne le numéro de téléphone du premier utilisateur de la liste)
 *                  
 * @param user utilisateur a générer
 * @return myuser utilisateur généré 
 */
function generateUser($user)
{
    //Attribution des valeurs
    $myUser = array(
        "nom" => $user['sn'][0],
        "prenom" => $user['givenname'][0],
        "role" => 0,
        "username" => $user['userprincipalname'][0],
        "mail" => $user['mail'][0],
        "tel" => $user['telephonenumber'][0]
    );

    // Gestion clé étrangère
    switch ($user['description'][0]) {
        case "direction":
            $myUser['role'] = 1;

            break;
        case "administration":
            $myUser['role'] = 2;

            break;
        case "comptabilite":
            $myUser['role'] = 3;

            break;
        case "utilisateur":
            $myUser['role'] = 4;
            break;
    }

    return $myUser;
}

/**
 * Ouvre une connexion à la base de donnée
 * 
 * @return db la connexion
 */
function dbConnect()
{

    echo "<p class='info'>Connexion à la base de donnée .  .  .<br></p>";
    $user = 'root';
    $pass = '';
    $dbname = 'm159';

    $db = new mysqli('localhost', $user, $pass, $dbname) or die("<p class='fail'>echec de connexion</p>");
    echo "<p class='success'> → connexion à la base de donnée réuissie<br></p>";

    return $db;
}
?>

<footer>
    <p>&copy; Louis Bovay</p>
</footer>