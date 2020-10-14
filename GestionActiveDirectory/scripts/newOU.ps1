# Chemin vers les OU
$BindUnit = "dc=M159LDAP,dc=LOCAL"
$BindUnitM159 = "ou=M159,dc=M159LDAP,dc=LOCAL"

# Création de la nouvelle branche OU
# La protection anti-supression accidentelle est enlvée pour facilité les tests
New-ADOrganizationalUnit -Name M159 -path $BindUnit -ProtectedFromAccidentalDeletion $false
# Création des feuilles OU
New-ADOrganizationalUnit -Name Direction -path $BindUnitM159 -ProtectedFromAccidentalDeletion $false
New-ADOrganizationalUnit -Name Administration -path $BindUnitM159 -ProtectedFromAccidentalDeletion $false
New-ADOrganizationalUnit -Name Comptabilité -path $BindUnitM159 -ProtectedFromAccidentalDeletion $false
New-ADOrganizationalUnit -Name Utilisateur -path $BindUnitM159 -ProtectedFromAccidentalDeletion $false

# Message de confirmation
" -> Unité(s) organisationnelle(s) créée(s) avec succès ! `r`n"