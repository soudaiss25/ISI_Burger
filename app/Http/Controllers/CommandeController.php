<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationCommande;
use App\Models\Burger;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commandes = Commande::where('user_id', Auth::id())->get();
        return view('commandes_client', compact('commandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'burger_id' => 'required|exists:burgers,id',
            'quantite' => 'required|integer|min:1'
        ]);

        $burger = Burger::findOrFail($request->burger_id);

        // Calcul du montant total
        $montantTotal = $burger->prix * $request->quantite;

        // Création de la commande avec le montant total
        $commande = Commande::create([
            'user_id' => Auth::id(),
            'statut' => 'En attente',
            'montantTotal' => $montantTotal,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Associer le burger à la commande
        $commande->burgers()->attach($request->burger_id, [
            'quantite' => $request->quantite,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mise à jour du stock
        $burger->stock -= $request->quantite;
        $burger->save();
        Mail::to(Auth::user()->email)->send(new ConfirmationCommande($commande));
        return redirect()->route('commandes')->with('success', 'Commande ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $commande = Commande::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('commandes.show', compact('commande'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $commande = Commande::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $commande->delete();
        return redirect()->route('commandes')->with('success', 'Commande annulée avec succès.');
    }
    public function checkout(Request $request)
    {
        $request->validate([
            'cart_items' => 'required|json',
        ]);

        $cartItems = json_decode($request->cart_items, true);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Votre panier est vide.');
        }

        $montantTotal = 0;

        // Calcul du montant total avant la création de la commande
        foreach ($cartItems as $item) {
            $burger = Burger::findOrFail($item['id']);
            $montantTotal += $burger->prix * $item['quantity'];
        }

        // Créer une nouvelle commande avec le montant total
        $commande = Commande::create([
            'user_id' => Auth::id(),
            'statut' => 'en_attente',
            'montantTotal' => $montantTotal,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Ajouter chaque élément du panier à la table d'association CommandeBurger
        foreach ($cartItems as $item) {
            $burger = Burger::findOrFail($item['id']);

            DB::table('commandes_burgers')->insert([
                'commande_id' => $commande->id,
                'burger_id' => $burger->id,
                'quantite' => $item['quantity'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Mise à jour du stock
            $burger->stock -= $item['quantity'];
            $burger->save();
        }
//        if ($request->has('redirectWithParam')) {
//            return redirect()->route('commandes', ['orderComplete' => 'true']);
//        }
        Mail::to(Auth::user()->email)->send(new ConfirmationCommande($commande));

        return redirect()->route('commandes')->with('success', 'Votre commande a été enregistrée avec succès!');
    }



}
