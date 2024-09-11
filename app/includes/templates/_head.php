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
    <title>' . $title . '</title>
    <link rel="shortcut icon" href="https://www.toiledecom.fr/wp-content/uploads/2020/06/Flavicon-toiledecom-150x150.webp" type="image/x-icon">
    <!-- if development -->
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/js/script.js"></script>
 ';
}