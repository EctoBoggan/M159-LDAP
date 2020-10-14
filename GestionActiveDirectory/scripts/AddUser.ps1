# Modules requis pour l'import
Import-Module ActiveDirectory

# Chemin de base vers l'OU M159
$OUpath = "ou=M159,dc=M159LDAP,dc=LOCAL"

# Compte total d'utilisateurs ajoutés
$count = 0

# Mot de passe par défaut
$securePassword = ConvertTo-SecureString "TESTpassw0rd" -AsPlainText -Force

# Chemin vers le fichier CSV
$currentPath = $PSScriptRoot
$filepath = "$currentPath\..\ressources\userLDAP.csv"

# Variable contenant le fichier CSV
"Récupération du fichier CSV à $filepath"
$users = Import-Csv $filepath
"Fichier chargé avec succès"

# Récupération de chaques utilisateurs
"Ajout des utilisateurs . . ."
ForEach ($user in $users){

    # Récupération des information de l'utilisateur
    $prenom = $user.prenom
    $nom = $user.nom
    $telephone = $user.telephone
    $username = $user.username
    $mail = $user.mail
    $description = $user.description

    # Détermination du chemin de l'OU
    $userOUpath = ""
    If($user.description -eq "direction"){
        $userOUpath = "ou=Direction,$OUpath"
    }
    ElseIf($user.description -eq "administration"){
        $userOUpath = "ou=Administration,$OUpath"
    }
    ElseIf($user.description -eq "comptabilite"){
        $userOUpath = "ou=Comptabilité,$OUpath"
    }
    ElseIf($user.description -eq "utilisateur"){
        $userOUpath = "ou=Utilisateur,$OUpath"
    }

    New-ADUser -Name "$prenom $nom" -GivenName $prenom -Surname $nom -EmailAddress $mail -Description $description -UserPrincipalName $username -path $userOUpath -AccountPassword $securePassword -ChangePasswordAtLogon $true -OfficePhone $telephone -Enabled $true
    $count++
}

echo " -> $count nouveaux utilisateurs `r`n"