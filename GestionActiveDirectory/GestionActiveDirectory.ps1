﻿while($true){
    # Demande à l'utilisateur de faire un choix
    "1. Créez les OU"
    "2. Ajouter des utilisateurs"
    "3. Supprimer les utilisateurs"
    "4. Supprimer les OU"
    "5. Quitter"
    $choix = Read-Host -Prompt "Saisissez votre choix "

    # Vérification de la réponse de l'utilisateur
    while($choix -ne 1 -AND $choix -ne 2 -AND $choix -ne 3 -AND $choix -ne 4 -AND $choix -ne "5")
    {    
        $choix = Read-Host "choix incorrect, réessayez"
    }

    # clear + nouvelle ligne
    cls
    "`r`n"

    # Récupération du chemin actuel
    $currentPath = $PSScriptRoot

    #En fonction de son choix, ouvre un fichier (Invoke-Expression permet d'executer un commande String)

    switch ($choix)
    {
        1 {Invoke-Expression "$currentPath\scripts\newOU.ps1"}
        2 {Invoke-Expression "$currentPath\scripts\AddUser.ps1"}
        3 {Invoke-Expression "$currentPath\scripts\removeUser.ps1"}
        4 {Invoke-Expression "$currentPath\scripts\removeOU.ps1"}
        5 {exit}
    }
}