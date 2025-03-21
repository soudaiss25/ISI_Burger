<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes - ISI Burger</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">

<!-- Navbar -->
<nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-red-600">🍔 ISI BURGER</h1>
    <div class="flex items-center space-x-4">
        <a href="{{ route('catalogue_Client') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Accueil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Déconnexion</button>
        </form>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container mx-auto px-4 py-8">
    <h2 class="text-4xl font-bold text-gray-800 mb-8">Mes Commandes</h2>

    <!-- Liste des commandes -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            @if($commandes->isEmpty())
                <p class="text-gray-600 text-center">Vous n'avez aucune commande pour le moment.</p>
            @else
                <div class="space-y-6">
                    @foreach($commandes as $commande)
                        <div class="border-b pb-6 last:border-b-0">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold text-gray-800">Commande #{{ $commande->id }}</h3>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full
                                                {{ $commande->statut === 'En attente' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($commande->statut === 'Prête' ? 'bg-green-100 text-green-800' :
                                                   ($commande->statut === 'Payée' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ $commande->statut }}
                                    </span>
                            </div>

                            <!-- Détails des burgers commandés -->
                            <div class="space-y-4">
                                @foreach($commande->burgers as $burger)
                                    <div class="flex items-center space-x-4">
                                        <img src="{{ Storage::url($burger->image) }}" alt="{{ $burger->nom }}" class="w-16 h-16 object-cover rounded-lg">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-medium text-gray-800">{{ $burger->nom }}</h4>
                                            <p class="text-sm text-gray-600">{{ $burger->pivot->quantite }} x {{ $burger->prix }} CFA</p>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-800">{{ $burger->pivot->quantite * $burger->prix }} CFA</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total de la commande -->
                            <div class="mt-4 pt-4 border-t flex justify-between items-center">
                                <p class="text-lg font-semibold text-gray-800">Total</p>
                                <p class="text-xl font-bold text-red-600">{{ $commande->burgers->sum(function ($burger) {
                                        return $burger->pivot->quantite * $burger->prix;
                                    }) }} CFA</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-8 text-center mt-12">
    <p>&copy; 2025 ISI BURGER. Tous droits réservés.</p>
</footer>

</body>
</html>
