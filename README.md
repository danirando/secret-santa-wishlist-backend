🧱 Milestone 0: Analisi e Configurazione
Obiettivo: Preparare l'ambiente di lavoro e definire la struttura dei dati.

Database Design: Disegna lo schema ER. Avrai bisogno principalmente di due tabelle: wishlists (con UUID/token e stato pubblicazione) e gifts (collegata alla wishlist).

Setup Ambiente:

Inizializza il progetto Laravel (Backend).

Configura la connessione a PostgreSQL (crea il DB tramite DBeaver).

Inizializza il progetto React con Vite (Frontend).

Crea i due repository Git come richiesto.

🧱 Milestone 1: Backend Core (Gestione Interna)
Obiettivo: Creare le API base per gestire i dati prima della pubblicazione.

Migrations & Models: Crea le migration per wishlists e gifts e i relativi Model su Laravel.

API Store Wishlist: Crea un endpoint (POST) che riceve un intero array di regali e crea la wishlist nel DB in un colpo solo (o crea la wishlist vuota e poi aggiunge i regali).

Nota: Qui genererai l'UUID univoco che servirà dopo.

API CRUD Regali (Opzionale per ora): Se decidi di salvare i dati sul server man mano che l'utente scrive, ti servono endpoint per aggiungere/rimuovere regali. Se invece (come da requisiti "Bozza") salvi tutto alla fine, ti basta l'endpoint di creazione massiva.

🧱 Milestone 2: 2.1 🚀 Setup Iniziale e Stato Base
Obiettivo: Definire i componenti React e lo stato iniziale per la lista dei desideri.

2.1.1 Creazione Componenti: Creare la struttura base dell'applicazione React (ad esempio, WishlistApp, GiftForm, GiftList).

2.1.2 Definizione Struttura Dati: Definire l'interfaccia o la struttura dell'oggetto "Regalo" (es. { id: uuid, name: string, price: number, link: string, priority: number }).

2.1.3 Stato Locale (Base): Implementare lo stato React principale nell'applicazione (probabilmente un useState per l'array dei regali e uno per il titolo della lista) per gestire la lista in memoria.

2.2 🎁 Creazione del Form e Logica di Aggiunta
Obiettivo: Permettere all'utente di inserire nuovi regali e aggiungerli allo stato locale.

2.2.1 Componente GiftForm: Creare il form con campi controllati per Nome, Link, Prezzo, e Priorità.

2.2.2 Gestione Input: Collegare gli input allo stato locale del form.

2.2.3 Validazione Client-side (Iniziale): Implementare la logica di validazione che impedisce l'invio se il Nome o il Prezzo sono mancanti/non validi.

2.2.4 Logica di Aggiunta: Creare la funzione per aggiungere il nuovo oggetto "Regalo" allo stato dell'array dei regali principale (WishlistApp).

2.3 📋 Visualizzazione e Funzionalità CRUD Locale
Obiettivo: Mostrare la lista dei regali in memoria e permettere la modifica e l'eliminazione.

2.3.1 Componente GiftList: Mappare l'array dei regali e visualizzare i dati in formato lista (Draft View).

2.3.2 Funzione di Eliminazione: Creare una funzione che riceve l'ID di un regalo e filtra lo stato dell'array per rimuoverlo (Rimuovi).

2.3.3 Funzione di Modifica (Toggle): Implementare un meccanismo che, cliccando su un regalo, passi dalla modalità di visualizzazione a una modalità di modifica inline (Modifica).

2.3.4 Funzione di Modifica (Salvataggio): Creare la funzione che salva le modifiche allo stato dell'array quando l'utente termina la modifica di un singolo regalo (Update).

2.4 💾 Persistenza con LocalStorage
Obiettivo: Mantenere i dati della bozza persistenti tra le sessioni del browser.

2.4.1 Hook useEffect per Salvataggio: Utilizzare l'hook useEffect per salvare l'intero array dei regali nel localStorage ogni volta che lo stato dell'array cambia.

2.4.2 Hook useEffect per Caricamento: Utilizzare useEffect (con dependency array vuoto []) per leggere i dati dal localStorage all'avvio dell'applicazione e inizializzare lo stato React principale con questi dati.

2.4.3 Gestione Reset: Creare un pulsante o una funzione per "Svuota lista/Nuova bozza" che pulisca sia lo stato React che la chiave nel localStorage.

2.5 🔗 Miglioramenti UX e Pronto alla Pubblicazione
Obiettivo: Aggiungere il pulsante di pubblicazione e preparare i dati per l'invio al backend.

2.5.1 Visualizzazione Titolo: Aggiungere un campo di input per il Titolo della Wishlist e mantenerlo sincronizzato con lo stato e il localStorage.

2.5.2 Pulsante Pubblica: Aggiungere un pulsante "Pubblica lista" che richiami una funzione di callback.

2.5.3 Pre-Validazione Finale: Assicurarsi che il pulsante "Pubblica" sia disabilitato se la lista è vuota o se manca il titolo.

🧱 Milestone 3: Pubblicazione Wishlist (Integrazione)
Obiettivo: Spostare i dati dal LocalStorage al Database PostgreSQL.

Pulsante Pubblica: Nel frontend, crea il pulsante che prende i dati dal localStorage e li invia all'API Laravel creata nella Milestone 1.

Risposta API: Il backend deve restituire l'UUID (il link segreto).

UI Successo: Mostra all'utente il link generato da condividere (es. tuosito.com/wishlist/{uuid}).

Pulizia: Una volta pubblicata con successo, pulisci il localStorage.

🧱 Milestone 4: Backend - Lato Ospite (Guest)
Obiettivo: Permettere agli amici di vedere e prenotare.

API Get Wishlist (Public): Crea un endpoint GET /api/wishlist/{uuid}. Deve restituire la lista dei regali.

Importante: Se il regalo è prenotato, il backend deve restituire is_booked: true ma NON deve restituire dati sensibili se non necessari.

API Prenotazione: Crea un endpoint POST /api/gifts/{id}/book.

Accetta: un messaggio opzionale ("Babbo Natale è passato").

Logica: Cambia lo stato del regalo a booked e salva il messaggio.

Controllo: Impedisci di prenotare un regalo già prenotato.

🧱 Milestone 5: Frontend - Vista Pubblica & Prenotazione
Obiettivo: L'interfaccia per chi riceve il link.

Routing Dinamico: Configura React Router per gestire l'URL /wishlist/:uuid.

Fetch Data: Al caricamento della pagina, chiama l'API per ottenere i regali.

Visualizzazione Read-Only: Mostra la lista dei regali (senza tasti modifica/elimina).

Se un regalo è già prenotato, mostralo come "Non disponibile" o barrato.

Azione Prenota:

Aggiungi un pulsante "Prenota questo regalo".

Apri una Modale (o un prompt) per inserire il messaggio di auguri.

Chiama l'API di prenotazione e aggiorna la UI al successo.

🧱 Milestone 6: Rifiniture e Bonus

UI/UX Polish: Controlla che tutto sia responsive su mobile (Mobile First).


Bonus Web Share API: Implementa il tasto "Condividi" nativo nella pagina di successo post-pubblicazione. (c'è già?)

Bonus Liste Salvate (Opzionale): Implementa la pagina per vedere le liste create o salvate (richiede di salvare gli UUID nel localStorage o implementare un sistema utenti base).

💡 Struttura Dati Consigliata (Schema Mentale)
Per aiutarti con la Milestone 0 e 1, ecco una bozza delle entità:

Table: wishlists

id (Primary Key)

uuid (String, Unique, Index - questo è il token segreto)

title (String, opzionale se vuoi dare un nome alla lista)

owner_name (String, nome di chi crea la lista)

timestamps

Table: gifts

id (Primary Key)

wishlist_id (Foreign Key -> wishlists)

name (String)

link (String, nullable)

price (Decimal/Integer)

priority (Integer 1-5)

is_booked (Boolean, default false)

booked_message (Text, nullable - il messaggio dell'amico)

timestamps# React + Vite

This template provides a minimal setup to get React working in Vite with HMR and some ESLint rules.

Currently, two official plugins are available:

- [@vitejs/plugin-react](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react) uses [Babel](https://babeljs.io/) (or [oxc](https://oxc.rs) when used in [rolldown-vite](https://vite.dev/guide/rolldown)) for Fast Refresh
- [@vitejs/plugin-react-swc](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react-swc) uses [SWC](https://swc.rs/) for Fast Refresh

## React Compiler

The React Compiler is not enabled on this template because of its impact on dev & build performances. To add it, see [this documentation](https://react.dev/learn/react-compiler/installation).

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.
