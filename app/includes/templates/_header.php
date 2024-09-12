<?php

/**
 * Template for header's index.
 *
 * @param string $url - The url the header title will lead you too.
 * @param string|null $linkTitle - The <a> title so people now where the link leads when they mouseover it.
 * @return string - header's content for index.php, HTML elements
 */
function fetchHeader(string $url, string $linkTitle = null): string
{
    return '
        <a href="' . $url . '" title="' . $linkTitle . '">
            <h1 class="header__ttl">WellComm</h1>
        </a>
        <div class="hamburger">
            <a href="#menu" id="hamburger-menu-icon">
                <img src="img/hamburger.svg" alt="Menu Hamburger">
            </a>
        </div>
    ';
}
