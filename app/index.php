<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WellComm</title>
    <link rel="shortcut icon" href="icones/favicon.ico" type="image/x-icon">
    <!-- if development -->
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/js/script.js"></script>
</head>

<body>

    <header class="header">
        <h1 class="header__ttl">WellComm</h1>
        <div class="hamburger">
            <a href="#menu" id="hamburger-menu-icon">
                <img src="img/hamburger.svg" alt="Menu Hamburger">
            </a>
        </div>
        <nav class="nav hamburger__menu" id="menu" aria-label="Navigation principale du site">
            <ul class="nav__lst" id="nav-list">
                <li class="nav__itm">
                    <a href="#connection-form" id="connection-menu" class="nav__lnk">Se connecter</a>
                </li>
                <li class="nav__itm">
                    <a href="contact.php" class="nav__lnk">Nous contacter</a>
                </li>
            </ul>
        </nav>
        <form class="form login__menu" action="post" id="connection-form" aria-label="Formulaire de connexion">
            <a class="button--close" href="index.php" aria-label="Fermer le formulaire de connexion">
                <img src="img/close-btn.svg" alt="Bouton fermer">
            </a>
            <ul class="form__lst">
                <li class="form__itm">
                    <label for="username" aria-label="Saississez votre nom du'ilisateur">Nom d'utilisateur</label>
                    <input class="form__input" type="text" name="username" id="username" placeholder="alemaitre78">
                </li>
                <li class="form__itm">
                    <label for="password" aria-label="Saississez votre mot de passe">Mot de passe</label>
                    <div class="form__input--password">
                        <input class="form__input" type="password" name="password" id="password" placeholder="•••••••••••">
                        <button class="button--eye button--eye--inactive" id="eye-button" aria-label="Montrer le mot de passe en clair dans le champs de saisie"></button>
                    </div>
                </li>
            </ul>
            <input class="button button--connection" type="submit" value="Connexion">
        </form>
    </header>

    <main class="container container__flex">
        <h2 class="ttl" id="take-control">
            Prenez le contrôle
            de votre budget
            <span class="ttl--tertiary">communication</span>
        </h2>
        <section class="section" aria-labelledby="take-control">
            <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
            <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
        </section>

        <h2 class="ttl" id="report">
            <span class="ttl--tertiary">Comptes rendus</span>
            EN TEMPS RÉEL
        </h2>
        <section class="section" aria-labelledby="report">
            <img src="img/working-team.webp" alt="Équipe travaillant avec des graphiques">
            <p class="paragraph">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum nunc nulla, rutrum at lacinia ut, bibendum eget lectus. Duis tortor diam, aliquet a ullamcorper vel, sagittis ac urna. Donec et faucibus nisi. Suspendisse in velit purus. Aenean sodales nunc nisi, id euismod lorem semper ultrices. Nulla vulputate blandit orci, congue tempus purus gravida ultricies.</p>
        </section>

    </main>

    <footer class="footer">
        <nav>
            <ul class="footer__nav">
                <li>Politique de confidentialité</li>
                <span>|</span>
                <li>Mentions légales</li>
            </ul>
        </nav>
        <p>©copyright</p>
    </footer>
</body>

<script type="module" src="js/script.js"></script>
<script type="module" src="js/password.js"></script>

</html>