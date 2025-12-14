<header>
    <nav>
        <ul>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 0): ?>
                <li><a href="./">home</a></li>
                <li><a href="?pg=catalogue">catalogue</a></li>
                <li><a href="?pg=dashboard">dashboard</a></li>
                <li><a href="?pg=reservation">reservation</a></li>
                <li><a href="?pg=deconnexion">deconnexion</a></li>

                <p>------------------------------------------------</p>


                <li><a href="?pg=dashboard&modify=account_modify">Modifier mon compte</a></li>
                <li><a href="?pg=dashboard&modify=account_delete">Supprimer mon compte</a></li>
            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 1): ?>
                <li><a href="./">home</a></li>
                <li><a href="?pg=catalogue">catalogue</a></li>
                <li><a href="?pg=dashboard">dashboard</a></li>
                <li><a href="?pg=deconnexion">deconnexion</a></li>

                <p>------------------------------------------------</p>

                <li><a href="?pg=dashboard&modify=account_modify">Modifier mon compte</a></li>
                <li><a href="?pg=dashboard&modify=account_delete">Supprimer mon compte</a></li>

                <p>------------------------------------------------</p>

                <li><a href="?pg=dashboard&modify=manage_users">Gerer les utilisateurs</a></li>
            <?php else: ?>
                <li><a href="./">home</a></li>
                <li><a href="?pg=catalogue">catalogue</a></li>
                <li><a href="?pg=connexion">connexion</a></li>
                <li><a href="?pg=inscription">inscription</a></li>

            <?php endif; ?>
        </ul>
    </nav>
</header>