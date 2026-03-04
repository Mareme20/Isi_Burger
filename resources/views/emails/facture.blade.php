<!DOCTYPE html>
<html>
<head>
    <title>Votre facture ISI BURGER</title>
</head>
<body style="font-family: sans-serif;">
    <h2>Bonjour {{ $commande->user->name }},</h2>
    <p>Votre commande **#CMD-{{ $commande->id }}** est désormais prête !</p>
    <p>Vous trouverez votre facture détaillée en pièce jointe de cet e-mail.</p>
    <p>Merci de votre confiance et bon appétit !</p>
    <br>
    <p>L'équipe **ISI BURGER**</p>
</body>
</html>
