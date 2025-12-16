<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WishlistController extends Controller
{
    /**
     * Store a newly created resource in storage (Pubblicazione Iniziale).
     * Rotta: POST /api/wishlists
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. VALIDAZIONE: Controlla che i dati principali siano presenti
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'gifts' => 'required|array',
                'gifts.*.name' => 'required|string|max:255',
                'gifts.*.price' => 'nullable|numeric|min:0',
                'gifts.*.link' => 'nullable|url|max:2048',
                // Aggiungere qui altri campi del regalo per la validazione
            ]);

            // 2. CREAZIONE WISHLIST
            $wishlist = Wishlist::create([
                'title' => $validatedData['title'],
                'secret_token' => (string) Str::uuid(), // Genera l'UUID segreto
                'is_published' => true,
            ]);

            // 3. CREAZIONE REGALI (Relazione uno-a-molti)
            $giftsData = collect($validatedData['gifts'])->map(function ($gift) {
                return [
                    'name' => $gift['name'],
                    'link' => $gift['link'] ?? null,
                    'price' => $gift['price'] ?? 0,
                    'priority' => $gift['priority'] ?? 3,
                ];
            });

            $wishlist->gifts()->createMany($giftsData->toArray());

            DB::commit();

            // 4. RISPOSTA AL FRONTEND: Restituisce il token segreto
            return response()->json([
                'message' => 'Wishlist creata e pubblicata!',
                'token' => $wishlist->secret_token,
                'secret_link' => url("/wishlist/{$wishlist->secret_token}"), 
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Impossibile salvare la wishlist.'], 500);
        }
    }

    /**
     * Visualizza la wishlist in modalità proprietario (dettagli completi).
     * Rotta: GET /api/wishlists/{token}
     */
    public function showOwnerView($token)
    {
        // Cerca la Wishlist tramite il token segreto (o fallisce con 404)
        $wishlist = Wishlist::where('secret_token', $token)->firstOrFail();

        // Carica i regali con tutti i dettagli di prenotazione
        $gifts = $wishlist->gifts->map(function ($gift) {
            return [
                'id' => $gift->id,
                'name' => $gift->name,
                'description' => $gift->description,
                'price' => $gift->price,
                'link' => $gift->link,
                'is_booked' => (bool) $gift->is_booked,
                'booked_message' => $gift->booked_message, // Dettaglio Proprietario
                'booked_at' => $gift->booked_at,
            ];
        });

        return response()->json([
            'id' => $wishlist->id,
            'title' => $wishlist->title,
            'secret_token' => $wishlist->secret_token,
            'is_published' => $wishlist->is_published,
            'gifts' => $gifts,
        ]);
    }

    /**
     * Visualizza la wishlist in modalità pubblica (ospite, dati filtrati).
     * Rotta: GET /api/public/{token}
     */
    public function showPublicView($token)
    {
        // Cerca la Wishlist pubblicata
        $wishlist = Wishlist::where('secret_token', $token)
                            ->where('is_published', true)
                            ->firstOrFail();

        // Filtra i dati sensibili (nasconde chi ha prenotato, ecc.)
        $gifts = $wishlist->gifts->map(function ($gift) {
            return [
                'id' => $gift->id,
                'name' => $gift->name,
                'price' => $gift->price,
                'link' => $gift->link,
                'priority' => $gift->priority,
                // Mostra solo lo stato di prenotazione
                'is_booked' => (bool) $gift->is_booked, 
            ];
        });

        return response()->json([
            'title' => $wishlist->title,
            'gifts' => $gifts,
        ]);
    }

    /**
     * Update the specified resource in storage. (Gestione solo del proprietario)
     * Rotta: PUT /api/wishlists/{token}
     */
    public function update(Request $request, $token)
    {
        try {
            DB::beginTransaction();
            
            // 1. Cerca la Wishlist
            $wishlist = Wishlist::where('secret_token', $token)->firstOrFail();

            // 2. Validazione (simile a store, ma solo per i dati che si possono aggiornare)
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'gifts' => 'required|array',
                'gifts.*.id' => 'nullable|integer', // ID esistente se è un update di un regalo
                'gifts.*.name' => 'required|string|max:255',
                'gifts.*.price' => 'nullable|numeric|min:0',
                'gifts.*.link' => 'nullable|url|max:2048',
            ]);

            // 3. Aggiorna il titolo
            $wishlist->update(['title' => $validatedData['title']]);

            $existingGiftIds = $wishlist->gifts->pluck('id')->toArray();
            $updatedGiftIds = [];

            // 4. Cicla e Sincronizza i regali (Crea o Aggiorna)
            foreach ($validatedData['gifts'] as $giftData) {
                if (isset($giftData['id'])) {
                    // AGGIORNA un regalo esistente
                    $gift = $wishlist->gifts()->where('id', $giftData['id'])->first();
                    if ($gift) {
                        $gift->update($giftData);
                        $updatedGiftIds[] = $gift->id;
                    }
                } else {
                    // CREA un nuovo regalo
                    $newGift = $wishlist->gifts()->create($giftData);
                    $updatedGiftIds[] = $newGift->id;
                }
            }

            // 5. Rimuovi i regali che non sono più nell'elenco
            $giftsToDelete = array_diff($existingGiftIds, $updatedGiftIds);
            if (!empty($giftsToDelete)) {
                $wishlist->gifts()->whereIn('id', $giftsToDelete)->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Wishlist aggiornata con successo.'], 200);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Impossibile aggiornare la wishlist.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage. (Gestione solo del proprietario)
     * Rotta: DELETE /api/wishlists/{token}
     */
    public function destroy($token)
    {
        // 1. Cerca la Wishlist
        $wishlist = Wishlist::where('secret_token', $token)->firstOrFail();

        // 2. Elimina (Laravel gestirà le cascate, se definite nelle migrazioni)
        $wishlist->delete();

        return response()->json(['message' => 'Wishlist eliminata con successo.'], 204); // 204 No Content
    }
    
    // I metodi index() e show(string $id) originali non sono usati
}