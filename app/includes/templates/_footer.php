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
                    <a href="policy.php">
                        Politique de confidentialité
                    </a>
                </li>
                <span>|</span>
                <li>
                    <a href="legal.php">
                        Mentions légales
                    </a>
                </li>
            </ul>
        </nav>
        <p>©copyright</p>
    ';
}
