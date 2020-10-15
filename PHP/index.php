<!--Formulaire-->
<form action="" method="post">
    <p>Choisisez entre importer les données depuis Active directory vers la Base de données ou de supprimer les données de la base de données</p>
    <p><input type="submit" value="Importer les données" name="btn"></p>
    <p><input type="submit" value="Supprimer les données" name="btn"></p>
</form>


<?php
// Détection de la pression d'un bouton par l'utilisateur
if (isset($_REQUEST['btn'])) {

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

            echo "Connexion à active directory .  .  .<br>";

            //Connection
            if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)) {
                echo "Connection réussie, importation des utilisateurs .  .  .<br>";

                $entries = getUsers($ldap_con);
                echo (count($entries) -1) , " utilisateurs importés<br>";

                //Afficher tout les utilisateurs
                //displayList($entries);

                $connexion = dbConnect();

                //Création et insertion de tout les utilisateurs (l'entrée 0 est vide, donc on l'évite)
                $count = 0;
                foreach ($entries as $user) {
                    if ($count != 0) {
                        $SQLuser = generateUser($user);
                        
                        $query = "INSERT INTO tb_user VALUES(null," . $SQLuser['role'] . ",'" . $SQLuser['nom']  . "','" . $SQLuser['prenom']
                        . "','" . $SQLuser['username'] . "','" . $SQLuser['mail'] . "'," . $SQLuser['tel'] . ");";

                        if($connexion->query($query)===TRUE){
                            //ça continue
                        }else{
                            echo "Erreur : " . $query . "<br>" . $connexion->error;
                        }
                    }
                    $count++;
                }

                $connexion->close();
                
            } else {
                echo "Echec de connection à l'ective directory<br>";
            }
            break;
            // Bouton de suppression
        case "Supprimer les données":
            echo "Suppression des données . . .<br>";

            echo "Données supprimées<br>";
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
    $result = ldap_search($connexion, "ou=M159,dc=M159LDAP,dc=LOCAL", $filter) or exit("Recherche échouée");
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
        "tel" => intval($user['telephonenumber'][0])
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

function dbConnect(){

    echo "Connexion à la base de donnée .  .  .<br>";
    $user = 'root';
    $pass = '';
    $dbname = 'm159';

    $db = new mysqli('localhost',$user,$pass,$dbname) or die("echec de connexion");
    echo "connexion à la base de donnée réuissie<br>";

    return $db;
}
?>


<p>&copy; Louis Bovay</p>