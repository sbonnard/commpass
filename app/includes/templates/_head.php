<?php

/**
 * Template for head.
 *
 * @param string $title - A title for your head.
 * @return string - Footer's content, HTML elements
 */
function fetchHead(string $title): string
{
    return '
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Commpass, le CRM spécialisé dans la gestion des budgets de communication, vous aide à créer des comptes-rendus clients clairs et performants. Simplifiez la gestion de vos campagnes.">
    <title>' . $title . '</title>
    <link rel="shortcut icon" href="https://www.toiledecom.fr/wp-content/uploads/2020/06/Flavicon-toiledecom-150x150.webp" type="image/x-icon">
    <!-- if development -->
    <!-- <script type="module" src="http://localhost:5173/@vite/client"></script> -->
    <!-- <script type="module" src="http://localhost:5173/js/script.js"></script> -->

    <!-- Production -->
    <link rel="stylesheet" href="/assets/script-KITKk1ta.css">
    <script type="module" src="/assets/script-CrfELStr.js"></script>

    <!-- C3 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.css" />

    <!-- D3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/5.16.0/d3.min.js"></script>

    <!-- C3 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.7.20/c3.min.js"></script>';
}
