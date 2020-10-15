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

            //Connection
            if(ldap_bind($ldap_con,$ldap_dn,$ldap_password)){
                echo "Connection réussie, importation des utilisateurs .  .  . <br>";

                $filter = "(uid=newton)";
                echo "Utilisateurs importés.";
            }else{
                echo "Echec de connection à l'ective directory";
            }
            break;
            // Bouton de suppression
        case "Supprimer les données":
            echo "Suppression des données . . .<br>";

            echo "Données supprimées";
            break;
    }
}
?>


<p>&copy; Louis Bovay</p>