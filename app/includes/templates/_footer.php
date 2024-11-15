<?php

/**
 * Template for footer.
 *
 * @return string - Footer's content, HTML elements
 */
function fetchFooter(): string
{
    return '
        <nav>
            <ul class="footer__nav">
                <li>
                    <a class="footer__lnk" href="/policy">
                        Politique de confidentialité
                    </a>
                </li>
                <span>|</span>
                <li>
                    <a class="footer__lnk" href="/legal">
                        Mentions légales
                    </a>
                </li>
            </ul>
        </nav>
        <p>©copyright</p>
    ';
}
