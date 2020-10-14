<form action="" method="post">
    <p>Choisisez entre importer les données depuis Active directory vers la Base de données ou de supprimer les données de la base de données</p>
    <p><input type="submit" value="Importer les données" name="btn"></p>
    <p><input type="submit" value="Supprimer les données" name="btn"></p>
</form>

<?php

if (isset($_REQUEST['btn'])) {

    switch ($_REQUEST['btn']) {
        case "Importer les données":
            echo '<script>alert("Import")</script>';
            break;
        case "Supprimer les données":
            echo '<script>alert("Delete")</script>'; 
            break;
    }
}
?>