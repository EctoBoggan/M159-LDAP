# L'utilisateur peut faire le nombre d'action qu'il souhaite tant qu'il ne quite pas l'application
while($true){

    # Demande à l'utilisateur de faire un choix
    "1. Créez les OU"
    "2. Ajouter des utilisateurs"
    "3. Supprimer les utilisateurs"
    "4. Supprimer les OU"
    "5. Quitter l'application"
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
        # Créer les OU
        1 {Invoke-Expression "$currentPath\scripts\newOU.ps1"}
        # Ajouter des utilisateurs
        2 {Invoke-Expression "$currentPath\scripts\AddUser.ps1"}
        # Supprimer tout les utilisateurs
        3 {Invoke-Expression "$currentPath\scripts\removeUser.ps1"}
        # Supprimer toutes les OU
        4 {Invoke-Expression "$currentPath\scripts\removeOU.ps1"}
        # Quitter l'application
        5 {exit}
    }
}
