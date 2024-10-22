<?php

// Lien potentiel vers "my-team.php" mais je ne vois pas l'intérêt de cette page si il n'y a pas besoin d'affecter un client.
// De plus, l'affectation d'un client pourrait se faire directement dans la page "clients.php" dans la section "Mes clients" 
// ce qui éviterait la multiplication des pages.
'<li class="dropdown__child-itm">
    <a href="my-team.php" class="dropdown__child-lnk" aria-label="Lien vers mon équipe">Mon équipe</a>
</li>';


'<li class="nav__itm ' . $NetworkActive . ' dropdown">
<button class="nav__lnk nav__lnk--network dropdown__button" aria-label="Menu déroulant" id="dropdown-btn">Mon réseau<span class="nav__arrow">▼</span></button>
<ul class="dropdown__child" id="dropdown-child">
    <li class="dropdown__child-itm">
        <a href="/clients.php" class="dropdown__child-lnk" aria-label="Lien vers mes clients">Mes clients</a>
    </li>
    <li class="dropdown__child-itm">
        <a href="/partners.php" class="dropdown__child-lnk" aria-label="Lien vers mes partenaires">Mes partenaires</a>
    </li>
    <li class="dropdown__child-itm">
        <a href="/new-user.php" class="dropdown__child-lnk" aria-label="Lien vers création d\'un nouvel utilisateur client ou Toile de Com">Nouvel utilisateur</a>
    </li>
</ul>
</li>';