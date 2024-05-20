<a id="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
    <a href="https://github.com/matte97p/Portfolio">
        <img src="storage/app/public/matte97.p.svg" alt="Logo" width="500" height="400">
    </a>
</div>

# Portfolio

> Conoscenze richieste:
> [Laravel](http://laravel.com/docs);
> [PostgreSQL](https://www.postgresql.org/docs/);

Rappresenta lo scheletro del nuovo backend. Leggi il generico README.md di progetto per avere maggiori informazioni.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## Namespace

**Principali cartelle del progetto(non vengono percorse tutte le cartelle base di Laravel fare riferimento alla [PATH ufficiale Laravel](https://laravel.com/docs/10.x/structure).**

    Portfolio
    |__ app
    |   |__ Exceptions -> Handler di eccezione custom
    |   |__ Http
    |   |   |__ Controllers
    |   |   |   |__ *.php -> astratti *
    |   |   |   |__ Concrete
    |   |   |__ Middleware
    |   |__ Models *
    |   |   |__ *.php -> concreti
    |   |   |__ Base *
    |   |__ Providers
    |   |__ Traits
    |   |__ Utils
    |       |__ ...
    |__ bootstrap
    |   |__ ...
    |__ config
    |__ database
    |   |__ factories
    |   |__ migrations
    |   |__ seeders
    |__ lang
    |   |__ ...
    |__ routes
    |   |__ ...
    |__ storage
    |   |__ ...
    |   |__ logs *
    |       |__ api
    |       |__ internal
    |__ ...

**\*** : [Classi Astratte](https://www.php.net/manual/en/language.oop5.abstract.php)

**\*** : i `Modelli` sono autogenerati con l'utilizzo della libreria [Reliese Laravel](https://github.com/reliese/laravel) e vengono aggiornato sulla base della configurazione a DB col comando:

```
php artisan code:models --table=\*\*
```

Qualsiasi customizzazione (o modello aggiuntivo) deve essere effettuata nella loro estensione(cartella Models) e non nel generato(cartella Base).

**\*** : i file di `LOG` registrano ogni attività esterna (API) che interna (internal) che viene effettuata.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## Login

> Conoscenze richieste:
> [SSO](https://it.wikipedia.org/wiki/Single_sign-on);
> [Oauth2](https://oauth.net/2/);

Passport-Laravel fornisce un sistema di login OAuth2 con il quale otteniamo un SSO dall'ente che detiene le credenziali criptate di accesso utilizzando Bearer Token oppure permette
l'autocertificazione ove non esiste il servizio di autenticazione.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## Controller

> Conoscenze richieste:
> [CRUD](https://it.wikipedia.org/wiki/CRUD);
> [Classi Astratte](https://www.php.net/manual/en/language.oop5.abstract.php);

I controller sono gestiti mediante due astratti principali `AbstractApiController` e `AbstractCrudController` (entrambi estendono `AbstractGenericController`) divisi ovviamente quindi
in controller CRUD per interazioni interna col DB (architettura a microservizi) e chiamate API esterne.

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## UUID

> Conoscenze richieste:
> [UUID](https://it.wikipedia.org/wiki/Universally_unique_identifier);

Tutti gli ID del database interno sono gestiti con UUID

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## Struttura del DB

> Conoscenze richieste:
> [PostgreSQL Inherits](#postgresql-inherits);

**L'ereditarietà è un concetto fondamentale per struttura scelta:**

[LEGENDA](#legenda-inherits)

-   **`Tabelle Generiche`**

    ```
    roles -> unione dei figli
    |__
        roles_currents -> Versioni attuali
        roles_history -> Versioni passate
    ```

    > Comandi per generare le migration:

    > [roles](#new-table)

    > [current e history](#basic-inherits-tables) + [trigger insert](#trigger-insert) + [trigger update](#trigger-update)

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## PostgreSQL INHERITS

> **_NOTE:_** PostgreSQL implementa l'ereditarietà delle tabelle, che può essere uno strumento utile per integrare la gestione Object Oriented nelle logiche di db invece che a BE.
> [OFFICIAL DOCS](https://www.postgresql.org/docs/9.1/ddl-inherit.html);

```
CREATE TABLE cities (
     name            text,
     population      float,
     altitude        int
 );

 CREATE TABLE capitals (
     state           char(2)
 ) INHERITS (cities);
```

**Il comando INHERITS permette alla tabella `capitals` di ereditare tutte le colonne di `cities`.**

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## Legenda INHERITS

```
|__ -> INHERITS
```

**`VERSIONI`**

**Quando una riga a DB viene modificata si attiva un trigger che registra la sua versione precedente alla modifica in una tabella di storico \*\_history.**

```
*_currents -> Versioni attuali del dato.
*_history -> Versioni passate del dato.
```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---

## Migration Command

**Per creare la struttura di base di una migration utilizzare i comandi qua di seguito e poi customizzare dove necessario.**

### New Table

```
php artisan make:table table_name
```

### Basic Inherits Tables

```
php artisan make:inherits table_name
```

### Trigger Insert

```
php artisan make:trigger_i table_name
```

### Trigger Update

```
php artisan make:trigger_u table_name
```

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---
