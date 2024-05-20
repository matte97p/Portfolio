<a id="readme-top"></a>

<!-- PROJECT LOGO -->
<br />
<div align="center">
    <a href="https://github.com/matte97p/CloudCare">
        <img src="storage/app/public/matte97.p.svg" alt="Logo" width="500" height="400">
    </a>
</div>

# CloudCare

> Conoscenze richieste:
> [Laravel](http://laravel.com/docs);
> [MySQL](https://www.mysql.org/docs/);

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

<p align="right">(<a href="#readme-top">back to top</a>)</p>

---
