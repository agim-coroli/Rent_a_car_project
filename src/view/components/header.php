<header>
    <nav>
        <ul>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <li><a href="./">home</a></li>
                <li><a href="catalogue">catalogue</a></li>
                <li><a href="dashboard">dashboard</a></li>
                <li><a href="deconnexion">deconnexion</a></li>
            <?php else: ?>
                <li><a href="./">home</a></li>
                <li><a href="catalogue">catalogue</a></li>
                <li><a href="connexion">connexion</a></li>
                <li><a href="inscription">inscription</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>