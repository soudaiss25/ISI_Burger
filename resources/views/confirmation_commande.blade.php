
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Confirmation de votre commande</title>
</head>
<body>
    <h2>Bonjour {{ $commande->user->name }},</h2>
<p>Votre commande <strong>#{{ $commande->id }}</strong> a été enregistrée avec succès.</p>
<p><strong>Montant total :</strong> {{ number_format($commande->montantTotal, 2) }} €</p>
<p><strong>Statut :</strong> {{ ucfirst($commande->statut) }}</p>

<h3>Détails de la commande :</h3>
<ul>
    @foreach ($commande->burgers as $burger)
        <li>{{ $burger->nom }} - {{ $burger->pivot->quantite }} x {{ number_format($burger->prix, 2) }} €</li>
    @endforeach
</ul>

<p>Merci pour votre commande !</p>
</body>
</html>
