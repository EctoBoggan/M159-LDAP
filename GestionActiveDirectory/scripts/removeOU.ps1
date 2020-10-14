# Chemin vers l'OU
$BindUnitM159 = "ou=M159,dc=M159LDAP,dc=LOCAL"

# Supprimer les OU, toujours en commençant par les feuilles, puis la branche
# feuilles
Remove-ADOrganizationalUnit -Identity "ou=Administration,$BindUnitM159" -Confirm:$false
Remove-ADOrganizationalUnit -Identity "ou=Comptabilité,$BindUnitM159" -Confirm:$false
Remove-ADOrganizationalUnit -Identity "ou=Direction,$BindUnitM159" -Confirm:$false
Remove-ADOrganizationalUnit -Identity "ou=Utilisateur,$BindUnitM159" -Confirm:$false
#branche
Remove-ADOrganizationalUnit -Identity $BindUnitM159 -Confirm:$false

# Message de confirmation
" -> Unité(s) organisationnelle(s) supprimée(s) avec succès ! `r`n"