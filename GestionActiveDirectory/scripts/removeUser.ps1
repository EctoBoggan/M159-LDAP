# Chemin de l'OU
$OUpath = "ou=M159,dc=M159LDAP,dc=LOCAL"

# Array d'utilisateurs + le compte
$deletes= Get-ADUser -SearchBase $OUpath -filter * -properties SamAccountName
$count=($deletes.Count)

# Supression des utilisateurs
"Supression des utilisateurs . . ."
foreach ($delete in $deletes) 
{
    remove-aduser -identity $delete.SamAccountName -confirm:$false
 }

# Message de confirmation
echo " -> $count utilisateur(s) supprimé(s) `r`n"
