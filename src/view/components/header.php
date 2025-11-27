<header>
    <nav>
        <ul>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <li><a href="./">home</a></li>
                <li><a href="?pg=catalogue">catalogue</a></li>
                <li><a href="?pg=dashboard">dashboard</a></li>
                <li><a href="?pg=deconnexion">deconnexion</a></li>
            <?php else: ?>
                <li><a href="./">home</a></li>
                <li><a href="?pg=catalogue">catalogue</a></li>
                <li><a href="?pg=connexion">connexion</a></li>
                <li><a href="?pg=inscription">inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>