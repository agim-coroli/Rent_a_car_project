<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) : ?>

    <body style="background-color: brown;">
    <?php else: ?>

        <body>

        <?php endif; ?>

        <?php require_once "components/header.php"; ?>

        <p>Bienvenue sur le dashboard</p>

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

                            <form method="post">

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($value->getId()) ?>
                                        <input type="hidden" name="id"
                                            value="<?= $value->getId() ?>">
                                    </td>

                                    <td><input type="text" name="full_name"
                                            value="<?= htmlspecialchars($value->getFullName()) ?>"></td>

                                    <td><input type="text" name="users[<?= $value->getId() ?>][pseudo]"
                                            value="<?= htmlspecialchars($value->getPseudo()) ?>"></td>

                                    <td><input type="email" name="users[<?= $value->getId() ?>][email]"
                                            value="<?= htmlspecialchars($value->getEmail()) ?>"></td>

                                    <td><input type="text" name="users[<?= $value->getId() ?>][phone]"
                                            value="<?= htmlspecialchars($value->getPhone()) ?>"></td>

                                    <td><input type="date" name="users[<?= $value->getId() ?>][date_birth]"
                                            value="<?= $value->getDateBirth() ? $value->getDateBirth()->format('Y-m-d') : '' ?>"></td>

                                    <td>
                                        <select name="users[<?= $value->getId() ?>][gender]">
                                            <option value="male" <?= $value->getGender() === 'male' ? 'selected' : '' ?>>Male</option>
                                            <option value="female" <?= $value->getGender() === 'female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </td>

                                    <td>
                                        <select name="users[<?= $value->getId() ?>][role]">
                                            <option value="0" <?= $value->getRole() === 0 ? 'selected' : '' ?>>User</option>
                                            <option value="1" <?= $value->getRole() === 1 ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </td>

                                    <td><?= $value->getCreatedAt() ? htmlspecialchars($value->getCreatedAt()->format('Y-m-d H:i:s')) : '' ?></td>

                                    <td>
                                        <button type="submit">Update</button>
                                        <a href="?pg=delete&id=<?= $value->getId() ?>">Delete</a>
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
                                <td><?= $value->getRole() !== null ? ($value->getRole() ? 'Admin' : 'User') : '' ?></td>
                                <td><?= $value->getCreatedAt() ? htmlspecialchars($value->getCreatedAt()->format('Y-m-d H:i:s')) : '' ?></td>
                                <td><a href="?pg=delete&id=<?= $value->getId() ?>">delete</a> & <a href="?pg=edit&id=<?= $value->getId() ?>">edit</a></td>

                            </tr>
                            </thead>
                        <?php endif; ?>
                    <?php endforeach; ?>


                </tbody>
            </table>

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
                            <td><?= $value->getRole() !== null ? ($value->getRole() ? 'Admin' : 'User') : '' ?></td>
                            <td><?= $value->getCreatedAt() ? htmlspecialchars($value->getCreatedAt()->format('Y-m-d H:i:s')) : '' ?></td>
                            <td><a href="?pg=delete&id=<?= $value->getId() ?>">delete</a> & <a href="?pg=edit&id=<?= $value->getId() ?>">edit</a></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


        <?php endif; ?>












        </body>

</html>