<?php

namespace App\Http\Controllers;

use App\Models\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GiftController extends Controller
{
    /**
     * Permette a un utente di prenotare un regalo specifico.
     * Rotta: POST /api/public/gifts/{gift_id}/book
     */
    public function book(Request $request, $gift_id)
    {
        try {
            DB::beginTransaction();
            
            // 1. Validazione dei dati dell'ospite
            $request->validate([
                'booked_message' => 'nullable|string|max:500', // Messaggio di prenotazione opzionale
                // Potresti richiedere qui 'booked_name' o 'booked_email' per identificare il prenotatore.
            ]);

            // 2. Recupero del Regalo
            $gift = Gift::findOrFail($gift_id);

            // 3. Controllo Conflitto: È già prenotato?
            if ($gift->is_booked) {
                DB::rollBack();
                return response()->json([
                    'gift_id' => ['Questo regalo è già stato prenotato da un altro Babbo Natale!']
                ], 409);
            }

            // 4. Esecuzione della Prenotazione
            $gift->is_booked = true;
            $gift->booked_message = $request->input('booked_message', 'Regalo prenotato!');
            $gift->booked_at = now(); 
            // Aggiungi qui: $gift->booked_by = $request->input('booked_name'); se raccogli il nome.
            $gift->save();

            DB::commit();

            // 5. Risposta di Successo
            return response()->json([
                'message' => 'Regalo prenotato con successo! Grazie per aver partecipato al Secret Santa.'
            ], 200);

        } catch (ValidationException $e) {
            // Gestione specifica degli errori di validazione (inclusi i conflitti 409)
            return response()->json($e->errors(), $e->status);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Si è verificato un errore durante la prenotazione. Riprova.',
                // 'debug' => $e->getMessage() // Rimuovere in produzione
            ], 500);
        }
    }

    /**
     * Permette a un utente di annullare la prenotazione di un regalo.
     * Rotta: DELETE /api/public/gifts/{gift_id}/unbook
     */
    public function unbook(Request $request, $gift_id)
    {
        try {
            DB::beginTransaction();

            // 1. Recupero del Regalo
            $gift = Gift::findOrFail($gift_id);

            // 2. Controllo: È prenotato?
            if (!$gift->is_booked) {
                DB::rollBack();
                return response()->json([
                    'error' => 'Questo regalo non risulta prenotato, impossibile annullare.'
                ], 400); 
            }

            // 3. Annullamento della Prenotazione
            $gift->is_booked = false;
            $gift->booked_message = null;
            $gift->booked_at = null; 
            // Aggiungi qui: $gift->booked_by = null;
            $gift->save();

            DB::commit();

            // 4. Risposta di Successo
            return response()->json([
                'message' => 'Prenotazione annullata con successo. Il regalo è di nuovo disponibile.'
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Si è verificato un errore durante l\'annullamento della prenotazione.'], 500);
        }
    }
}