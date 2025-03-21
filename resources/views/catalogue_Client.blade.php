<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISI Burger - Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Ajout de styles personnalisés */
        .burger-card {
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .burger-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        .card-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-actions {
            margin-top: auto;
        }
        .button-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        /* Animation pour le panier */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .animate-pulse-custom {
            animation: pulse 0.7s;
        }
        /* Espace pour la navbar fixed */
        .navbar-spacer {
            height: 76px; /* Ajustez selon la hauteur de votre navbar */
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Navbar (fixed) -->
<nav class="bg-white shadow-md py-3 px-6 flex justify-between items-center fixed top-0 left-0 right-0 z-50">
    <h1 class="text-3xl font-bold text-red-600">🍔 ISI BURGER</h1>
    <div class="flex items-center space-x-4">
        @if(Auth::check())
            <a href="{{ route('commandes') }}" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900">Mes Commandes</a>
            <!-- Bouton panier avec nombre d'articles -->
            <button class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 relative" onclick="toggleCart()">
                <i class="fas fa-shopping-cart"></i> Panier
                <span id="cart-count" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full h-5 w-5 flex items-center justify-center text-xs">0</span>
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Se connecter</a>
            <a href="{{ route('register') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">S'inscrire</a>
        @endif
    </div>
</nav>

<!-- Espace pour compenser la navbar fixed -->
<div class="navbar-spacer"></div>

<!-- Panier sidebar -->
<div id="cart-sidebar" class="fixed top-0 right-0 h-full w-80 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
    <div class="p-4">
        <div class="flex justify-between items-center border-b pb-4">
            <h2 class="text-xl font-bold">Votre Panier</h2>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-red-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div id="cart-items" class="py-4">
            <!-- Les articles du panier seront ajoutés ici dynamiquement -->
            <p class="text-gray-500 text-center py-8" id="empty-cart-message">Votre panier est vide</p>
        </div>

        <div class="border-t pt-4">
            <div class="flex justify-between font-bold text-lg mb-4">
                <span>Total:</span>
                <span id="cart-total">0 CFA</span>
            </div>
            @if(Auth::check())
                <form id="checkout-form" action="{{route('commande.checkout')}}" method="POST">
                    @csrf
                    <input type="hidden" name="cart_items" id="cart_items_input">
                    <button id="checkout-button" class="w-full py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Procéder à la commande
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-center transition">
                    Se connecter pour commander
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Overlay pour le panier -->
<div id="cart-overlay" class="fixed inset-0 bg-black opacity-0 pointer-events-none transition-opacity duration-300 z-40"></div>

<!-- Hero Section -->
<header class="relative bg-cover bg-center h-[700px] flex items-center justify-center text-white text-center"
        style="background-image: url({{ Storage::url('images.jpg') }});">

    <div class="absolute inset-0 bg-black bg-opacity-50"></div> <!-- Overlay plus sombre pour meilleure lisibilité -->

    <div class="relative p-8 bg-black bg-opacity-30 rounded-lg backdrop-filter backdrop-blur-sm max-w-3xl mx-auto">
        <h2 class="text-5xl font-bold">🍔 Les Meilleurs Burgers de la Ville !</h2>
        <p class="mt-3 text-xl">Savourez un goût inoubliable avec nos ingrédients frais.</p>
        <a href="#menu" class="mt-5 inline-block px-6 py-3 bg-red-600 text-white font-semibold rounded-lg text-lg hover:bg-red-700 transition duration-300 transform hover:scale-105">
            Voir le Menu
        </a>
    </div>
</header>

<!-- Menu Section -->
<section id="menu" class="py-16 container mx-auto">
    <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">Notre Menu 🍽</h2>

    <!-- Filtres -->
    <div class="mb-8 flex flex-col md:flex-row justify-center gap-4 px-4">
        <input type="text" placeholder="🔍 Rechercher..." class="border p-3 rounded-lg w-full md:w-1/3" id="searchInput">
        <input type="number" placeholder="💰 Prix max" class="border p-3 rounded-lg w-full md:w-1/5" id="priceInput">
        <button class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition" onclick="filterBurgers()">Filtrer</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4" id="burgerList">
        @foreach($burgers as $burger)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden burger-card">
                <img src="{{ Storage::url($burger->image) }}" alt="{{ $burger->nom }}" class="w-full h-56 object-cover">
                <div class="p-6 card-content">
                    <h3 class="text-2xl font-semibold">{{ $burger->nom }}</h3>
                    <p class="text-gray-600 mt-2">{{ $burger->description }}</p>
                    <div class="mt-4 flex flex-col space-y-3 card-actions">
                        <span class="text-xl font-bold text-red-600">{{ $burger->prix }} CFA</span>
                        <div class="button-group">
                            <a href="{{ route('burger.show', $burger->id) }}" class="px-3 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                                <i class="fas fa-info-circle"></i> Détails
                            </a>

                            @if(Auth::check())
                                <!-- Bouton "Ajouter au panier" -->
                                <button
                                    class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition"
                                    onclick="addToCart('{{ $burger->id }}', '{{ $burger->nom }}', {{ $burger->prix }}, '{{ Storage::url($burger->image) }}')">
                                    <i class="fas fa-cart-plus"></i> Ajouter
                                </button>

                                <!-- Form de commande directe (amélioré) -->
                                <form action="{{ route('commande.store', $burger->id) }}" method="POST" class="flex items-center space-x-2 mt-2">
                                    @csrf
                                    <input type="hidden" name="burger_id" value="{{ $burger->id }}">
                                    <div class="flex items-center border rounded-lg overflow-hidden">
                                        <button type="button" onclick="decrementQuantity(this)" class="px-2 py-1 bg-gray-200 hover:bg-gray-300">-</button>
                                        <input type="number" name="quantite" value="1" min="1" class="border-0 p-1 w-12 text-center" readonly>
                                        <button type="button" onclick="incrementQuantity(this)" class="px-2 py-1 bg-gray-200 hover:bg-gray-300">+</button>
                                    </div>
                                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                        <i class="fas fa-check"></i> Commander
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                    <i class="fas fa-sign-in-alt"></i> Se connecter
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Avis Clients -->
<section class="bg-blue-50 py-16">
    <div class="container mx-auto">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-8">Nos Clients Satisfaits ⭐</h2>
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 px-4">
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition duration-300">
                <div class="text-yellow-500 text-2xl mb-3">⭐⭐⭐⭐⭐</div>
                <p class="text-gray-700 italic">"Les burgers sont incroyablement délicieux, un vrai régal !"</p>
                <span class="block mt-3 font-bold text-gray-900">- Sophie D.</span>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg text-center transform hover:scale-105 transition duration-300">
                <div class="text-yellow-500 text-2xl mb-3">⭐⭐⭐⭐⭐</div>
                <p class="text-gray-700 italic">"Service rapide et ingrédients de qualité, je recommande !"</p>
                <span class="block mt-3 font-bold text-gray-900">- Marc T.</span>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-8 mt-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h3 class="text-2xl font-bold text-red-500">🍔 ISI BURGER</h3>
                <p class="mt-2">&copy; 2025 ISI BURGER. Tous droits réservés.</p>
            </div>
            <div class="flex space-x-4">
                <a href="#" class="text-white hover:text-red-500 text-2xl"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white hover:text-red-500 text-2xl"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white hover:text-red-500 text-2xl"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>
</footer>

<script>
    // Gestion du panier
    // Gestion optimisée du panier
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartIsUpdating = false; // Flag pour éviter les mises à jour simultanées

    // Afficher/cacher le panier avec animation plus fluide
    function toggleCart() {
        const sidebar = document.getElementById('cart-sidebar');
        const overlay = document.getElementById('cart-overlay');

        sidebar.classList.toggle('translate-x-full');

        if (sidebar.classList.contains('translate-x-full')) {
            overlay.classList.add('opacity-0');
            overlay.classList.add('pointer-events-none');
        } else {
            overlay.classList.remove('opacity-0');
            overlay.classList.remove('pointer-events-none');

            // Force une mise à jour du panier quand on l'ouvre
            updateCartDisplay();
        }
    }

    // Ajouter un burger au panier avec animation optimisée
    function addToCart(id, name, price, image) {
        if (cartIsUpdating) return; // Éviter les ajouts multiples rapides
        cartIsUpdating = true;

        // Convertir l'id en string pour assurer la comparaison correcte
        const burgerId = String(id);

        // Vérifier si l'article est déjà dans le panier
        const existingItemIndex = cart.findIndex(item => String(item.id) === burgerId);

        if (existingItemIndex !== -1) {
            // Si l'élément existe, incrémenter sa quantité
            cart[existingItemIndex].quantity += 1;
        } else {
            // Sinon, ajouter un nouvel élément
            cart.push({
                id: burgerId,
                name: name,
                price: price,
                image: image,
                quantity: 1
            });
        }

        // Sauvegarder le panier dans localStorage de façon asynchrone
        saveCartToLocalStorage();

        // Animation pour confirmer l'ajout
        const cartBtn = document.querySelector('button[onclick="toggleCart()"]');
        cartBtn.classList.add('animate-pulse-custom');

        // Mettre à jour l'affichage du panier et terminer l'animation
        setTimeout(() => {
            updateCartDisplay();
            cartBtn.classList.remove('animate-pulse-custom');
            cartIsUpdating = false;}, 300);
    }

    // Fonction optimisée pour sauvegarder le panier
    function saveCartToLocalStorage() {
        // Utiliser setTimeout pour déplacer l'opération coûteuse hors du thread principal
        setTimeout(() => {
            localStorage.setItem('cart', JSON.stringify(cart));
        }, 0);
    }

    // Supprimer un item du panier avec feedback visuel
    function removeFromCart(id) {
        // Trouver l'élément à supprimer pour l'animation
        const itemElement = document.querySelector(`[data-item-id="${id}"]`);
        if (itemElement) {
            // Ajouter une classe pour l'animation de suppression
            itemElement.classList.add('opacity-50', 'scale-95');
        }

        // Délai court pour l'animation avant la suppression réelle
        setTimeout(() => {
            cart = cart.filter(item => String(item.id) !== String(id));
            saveCartToLocalStorage();
            updateCartDisplay();
        }, 200);
    }

    // Mettre à jour la quantité avec retour visuel immédiat
    function updateQuantity(id, change) {
        const itemIndex = cart.findIndex(item => String(item.id) === String(id));

        if (itemIndex !== -1) {
            // Mettre à jour visuellement d'abord
            const quantityElement = document.querySelector(`[data-item-id="${id}"] .quantity-value`);
            if (quantityElement) {
                const newQuantity = cart[itemIndex].quantity + change;
                if (newQuantity > 0) {
                    quantityElement.textContent = newQuantity;
                }
            }

            // Puis mettre à jour les données
            cart[itemIndex].quantity += change;

            if (cart[itemIndex].quantity <= 0) {
                removeFromCart(id);
            } else {
                saveCartToLocalStorage();
                // Mise à jour partielle pour être plus rapide
                updateCartCount();
                updateCartTotal();
            }
        }
    }

    // Mise à jour du compteur d'articles (fonction séparée pour optimisation)
    function updateCartCount() {
        const cartCount = document.getElementById('cart-count');
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = totalItems;

        // Animation subtile pour l'indicateur
        cartCount.classList.add('scale-110');
        setTimeout(() => {
            cartCount.classList.remove('scale-110');
        }, 300);
    }

    // Mise à jour du total du panier (fonction séparée)
    function updateCartTotal() {
        const cartTotal = document.getElementById('cart-total');
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        cartTotal.textContent = `${total} CFA`;
    }

    // Mettre à jour l'affichage complet du panier (optimisé)
    function updateCartDisplay() {
        const cartItems = document.getElementById('cart-items');
        const emptyMessage = document.getElementById('empty-cart-message');
        const checkoutButton = document.getElementById('checkout-button');
        const cartItemsInput = document.getElementById('cart_items_input');

        // Mettre à jour le compteur et le total
        updateCartCount();
        updateCartTotal();

        // Afficher ou masquer le message "panier vide"
        if (cart.length === 0) {
            emptyMessage.style.display = 'block';
            if (checkoutButton) {
                checkoutButton.disabled = true;
                checkoutButton.classList.add('opacity-50');
            }
        } else {
            emptyMessage.style.display = 'none';
            if (checkoutButton) {
                checkoutButton.disabled = false;
                checkoutButton.classList.remove('opacity-50');
            }
        }

        // Préparer les données pour la soumission du formulaire
        if (cartItemsInput) {
            cartItemsInput.value = JSON.stringify(cart);
        }

        // Vider et reconstruire la liste des articles
        cartItems.innerHTML = '';

        if (cart.length === 0) {
            cartItems.appendChild(emptyMessage);
        } else {
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'flex items-center justify-between border-b py-3';
                itemElement.dataset.itemId = item.id; // Ajouter un attribut data pour cibler l'élément

                itemElement.innerHTML = `
                <div class="flex items-center space-x-3">
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                    <div>
                        <h4 class="font-semibold">${item.name}</h4>
                        <p class="text-red-600">${item.price} CFA</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateQuantity('${item.id}', -1)" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">-</button>
                    <span class="quantity-value">${item.quantity}</span>
                    <button onclick="updateQuantity('${item.id}', 1)" class="px-2 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">+</button>
                    <button onclick="removeFromCart('${item.id}')" class="text-red-500 ml-2 transition hover:text-red-700">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

                cartItems.appendChild(itemElement);
            });
        }
    }

    // Réinitialiser le panier après une commande réussie
    function clearCart() {
        // Vider l'array du panier
        cart = [];
        // Vider le localStorage
        localStorage.removeItem('cart');
        // Mettre à jour l'affichage
        updateCartDisplay();

        // Feedback visuel
        const cartBtn = document.querySelector('button[onclick="toggleCart()"]');
        if (cartBtn) {
            cartBtn.classList.add('bg-green-500');
            setTimeout(() => {
                cartBtn.classList.remove('bg-green-500');
            }, 1000);
        }

        console.log('Panier vidé!'); // Pour déboguer
    }

    // Soumettre la commande avec confirmation
    function submitOrder() {
        if (cart.length > 0) {
            // Mettre à jour le champ caché avec les données du panier
            document.getElementById('cart_items_input').value = JSON.stringify(cart);

            // Vider le panier immédiatement avant la soumission
            clearCart();

            // Soumission du formulaire
            document.getElementById('checkout-form').submit();
        }
    }
    // Initialisation avec chargement différé pour éviter de bloquer le rendu
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutForm = document.getElementById('checkout-form');
        const checkoutButton = document.getElementById('checkout-button');

        if (checkoutForm && checkoutButton) {
            // Remplacer l'événement de clic par défaut
            checkoutButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (cart.length > 0) {
                    // Mettre à jour le champ caché avec les données du panier
                    document.getElementById('cart_items_input').value = JSON.stringify(cart);

                    // Créer une copie du panier pour la soumission
                    const cartCopy = JSON.parse(JSON.stringify(cart));

                    // Vider le panier immédiatement
                    clearCart();

                    // Forcer la sauvegarde du localStorage vide
                    localStorage.setItem('cart', JSON.stringify([]));

                    // Soumettre le formulaire après un court délai
                    setTimeout(function() {
                        checkoutForm.submit();
                    }, 100);
                }
            });
        }

        // Initialiser l'affichage du panier
        updateCartDisplay();
    });

        // Fermer le panier en cliquant sur l'overlay
        document.getElementById('cart-overlay').addEventListener('click', toggleCart);


    document.getElementById('checkout-button').addEventListener('click', function(event) {
        document.getElementById('checkout-form').submit();
    });
    // Modifiez votre gestionnaire de soumission de commande


    // Ajouter des transitions CSS pour rendre les interactions plus fluides
    document.head.insertAdjacentHTML('beforeend', `
<style>
    .cart-item-enter {
        opacity: 0;
        transform: translateY(-10px);
    }
    .cart-item-enter-active {
        opacity: 1;
        transform: translateY(0);
        transition: opacity 300ms, transform 300ms;
    }
    #cart-count {
        transition: all 0.3s ease;
    }
    .scale-110 {
        transform: scale(1.1);
    }
</style>
`);
</script>

</body>
</html>
