<ul>
    ------------------------------------------------------------------------------
    <?php if ($_SESSION["role"] === 1): ?>
        <li><a href="?pg=dashboard&modify=manage_users">Gerer les utilisateurs</a></li>
    <?php endif; ?>

    <li><a href="?pg=dashboard&modify=account_modify">Modifier mon compte</a></li>
    <li><a href="?pg=dashboard&modify=account_delete">Supprimer mon compte</a></li>
</ul>
