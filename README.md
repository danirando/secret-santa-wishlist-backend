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

🧱 Milestone 2: Frontend - Gestione Bozza (LocalStorage)
Obiettivo: Permettere all'utente di creare la lista senza internet (Bozza).

UI Homepage & Form: Crea la pagina principale con il form di inserimento regalo (Nome, Link, Prezzo, Priorità).

Logica LocalStorage: Implementa la logica React per salvare l'array dei regali nel localStorage del browser ogni volta che se ne aggiunge o modifica uno.

Lista Regali (Draft View): Visualizza i regali salvati sotto il form, con possibilità di modificarli o eliminarli (agendo sullo stato locale e localStorage).

Validazione Client-side: Assicurati che non si possano inserire regali senza nome o prezzo.

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
Obiettivo: Migliorare l'esperienza utente e aggiungere le feature extra.

UI/UX Polish: Controlla che tutto sia responsive su mobile (Mobile First).

Feedback Utente: Aggiungi spinner di caricamento durante le chiamate API e messaggi toast per confermare le azioni (es. "Regalo aggiunto!", "Lista pubblicata!").

Bonus Web Share API: Implementa il tasto "Condividi" nativo nella pagina di successo post-pubblicazione.

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
