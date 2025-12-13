<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css">

    <title>Document</title>
    <style>
        body{
            border: solid red;
        }
    </style>
</head>

<body >
    <?php
    require_once PATH . "/src/view/components/header.php";
    ?>

    <div style="margin: 20px 0;">
        <a href="?pg=generate-user" style="padding: 5px 5px; background-color: #4CAF50; text-decoration: none; border-radius: 5px; font-weight: bold;">
            ➕
        </a>
    </div>

    <?php if (isset($_GET['pg']) && $_GET['pg'] === "edit"): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>id</th>
                    <th>full name</th>
                    <th>pseudo</th>
                    <th>email</th>
                    <th>phone</th>
                    <th>date_birth</th>
                    <th>gender</th>
                    <th>role</th>
                    <th>created at</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tab as $value): ?>
                    <?php if ((int)$_GET['id'] === $value->getId()): ?>
                        <form method="post" action="?pg=edit&id=<?= $value->getId() ?>">
                            <tr>
                                <td>
                                    <?= htmlspecialchars($value->getId()) ?>
                                    <input type="hidden" name="id" value="<?= $value->getId() ?>">
                                </td>
                                <td><input type="text" name="full_name" value="<?= htmlspecialchars($value->getFullName()) ?>"></td>
                                <td><input type="text" name="pseudo" value="<?= htmlspecialchars($value->getPseudo()) ?>"></td>
                                <td><input type="email" name="email" value="<?= htmlspecialchars($value->getEmail()) ?>"></td>
                                <td><input type="text" name="phone" value="<?= htmlspecialchars($value->getPhone()) ?>"></td>
                                <td><input type="date" name="date_birth" value="<?= $value->getDateBirth() ? $value->getDateBirth()->format('Y-m-d') : '' ?>"></td>
                                <td>
                                    <select name="gender">
                                        <option value="Masculin" <?= $value->getGender() === 'Masculin' ? 'selected' : '' ?>>Masculin</option>
                                        <option value="Feminin" <?= $value->getGender() === 'Feminin' ? 'selected' : '' ?>>Feminin</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="role">
                                        <option value="0" <?= $value->getRole() === 0 ? 'selected' : '' ?>>User</option>
                                        <option value="1" <?= $value->getRole() === 1 ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </td>
                                <td><?= $value->getCreatedAt() ? htmlspecialchars($value->getCreatedAt()->format('Y-m-d H:i:s')) : '' ?></td>
                                <td>
                                    <button>Update</button> Or
                                    <a href="?pg=delete&id=<?= $value->getId() ?>">Delete</a>
                                </td>
                                <td>
                                    <a href="?pg=dashboard&modify=manage_users">X</a>
                                </td>
                            </tr>
                        </form>
                    <?php else: ?>
                        <tr>
                            <td><?= htmlspecialchars($value->getId()) ?></td>
                            <td><?= htmlspecialchars($value->getFullName()) ?></td>
                            <td><?= htmlspecialchars($value->getPseudo()) ?></td>
                            <td><?= htmlspecialchars($value->getEmail()) ?></td>
                            <td><?= htmlspecialchars($value->getPhone()) ?></td>
                            <td><?= $value->getDateBirth() ? htmlspecialchars($value->getDateBirth()->format('Y-m-d')) : '' ?></td>
                            <td><?= htmlspecialchars($value->getGender()) ?></td>
                            <td><?= $value->getRole() !== null ? ($value->getRole() == 1 ? 'Admin' : 'User') : '' ?></td>
                            <td><?= $value->getCreatedAt() ? htmlspecialchars($value->getCreatedAt()->format('Y-m-d H:i:s')) : '' ?></td>
                            <td><a href="?pg=delete&id=<?= $value->getId() ?>">delete</a> & <a href="?pg=edit&id=<?= $value->getId() ?>">edit</a></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <?php if (!isset($tab)): ?>
            <p>Aucune donnée disponible.</p>
        <?php else: ?>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>full name</th>
                        <th>pseudo</th>
                        <th>email</th>
                        <th>phone</th>
                        <th>date_birth</th>
                        <th>gender</th>
                        <th>role</th>
                        <th>created at</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tab as $value): ?>
                        <tr>
                            <td><?= htmlspecialchars($value->getId()) ?></td>
                            <td><?= htmlspecialchars($value->getFullName()) ?></td>
                            <td><?= htmlspecialchars($value->getPseudo()) ?></td>
                            <td><?= htmlspecialchars($value->getEmail()) ?></td>
                            <td><?= htmlspecialchars($value->getPhone()) ?></td>
                            <td><?= $value->getDateBirth() ? htmlspecialchars($value->getDateBirth()->format('Y-m-d')) : '' ?></td>
                            <td><?= htmlspecialchars($value->getGender()) ?></td>
                            <td><?= $value->getRole() !== null ? ($value->getRole() == 1 ? 'Admin' : 'User') : '' ?></td>
                            <td><?= $value->getCreatedAt() ? htmlspecialchars($value->getCreatedAt()->format('Y-m-d H:i:s')) : '' ?></td>
                            <td><a href="?pg=delete&id=<?= $value->getId() ?>">delete</a> & <a href="?pg=edit&id=<?= $value->getId() ?>">edit</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>

</html>