<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// kan ingelogde user lijst met producten opvragen?
// word de juiste blade view gerendered?
// worden de producten doorgegeven aan de view?
test('producten kunnen worden bekeken', function () {
    $user = User::factory()->create(); // maak test user aan
    $this->actingAs($user); // simuleer user ingelogd

    $products = Product::factory()->count(3)->create(); // maak 3 producten aan in de database

    $response = $this->get(route('products.index')); // voer een get verzoek uit naar de route

    $response->assertStatus(200); // controleer of de pagina juist laadt (HTTP 200 OK)
    $response->assertViewIs('products.index'); // controleer of de juiste blade view wordt geladen
    $response->assertViewHas('products', function ($viewProducts) use ($products) {
        return $viewProducts->contains($products->first()); // controleer of ten minste 1 van de aangemaakte producten in de view aanwezig is
    });
});

// kan een ingelogde user een product aanmaken?
// word de user na het aanmaken van een product geredirect naar de index pagina?
// word het product opgeslagen in de database?
test('producten kunnen worden aangemaakt en opgeslagen', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $productData = Product::factory()->make()->toArray(); // maak een testproduct (zonder opslaan) zet het om naar een array

    $response = $this->post(route('products.store'), $productData); // simuleer een POST verzoek naar de route

    $response->assertStatus(302); // controleer of de response een redirect geeft (HTTP 302)
    $response->assertRedirect(route('products.index')); // controleer of de redirect naar de index gaat
    $this->assertDatabaseHas('products', [
        'Name' => $productData['Name'],
        'Price' => $productData['Price'], // controleer of het product correct in de database is opgeslagen   
    ]);
});

test('een product kan worden opgehaald om te editen', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = Product::factory()->create(); // maak een product aan in de database

    $response = $this->get(route('products.edit', $product)); // simuleer een GET verzoek naar de route

    $response->assertStatus(200); // controleer of de pagina juist laadt (HTTP 200 OK)
    $response->assertViewIs('products.edit'); // controleer of de juiste blade view wordt geladen
    $response->assertViewHas('product', $product); // controleer of het juiste product wordt doorgegeven aan de view
});

// kan een ingelogde user een product updaten?
// word de user na het updaten van een product geredirect naar de index pagina?
// word het product correct geupdate in de database?
test('een product kan worden opgehaald en geupdate worden', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = Product::factory()->create(); // maak een product aan in de database

    $productData = Product::factory()->make()->toArray(); // maak een testproduct (zonder opslaan) zet het om naar een array

    $response = $this->put(route('products.update', $product), $productData); // simuleer een PUT verzoek naar de route

    $response->assertStatus(302); // controleer of de response een redirect geeft (HTTP 302)
    $response->assertRedirect(route('products.index')); // controleer of de redirect naar de index gaat
    $this->assertDatabaseHas('products', [
        'Name' => $productData['Name'],
        'Price' => $productData['Price'], // controleer of het product correct in de database is opgeslagen   
    ]);
});

// kan een ingelogde user een product verwijderen?
// word de user na het verwijderen van een product geredirect naar de index pagina?
// word het product correct verwijderd uit de database?
test('een product kan worden opgehaald en worden verwijderd', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $product = Product::factory()->create(); // maak een product aan in de database

    $response = $this->delete(route('products.destroy', $product)); // simuleer een DELETE verzoek naar de route

    $response->assertStatus(302); // controleer of de response een redirect geeft (HTTP 302)
    $response->assertRedirect(route('products.index')); // controleer of de redirect naar de index gaat
    $this->assertDatabaseMissing('products', [
        'id' => $product->id, // controleer of het product correct uit de database is verwijderd
    ]);
});