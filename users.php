<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

require 'includes/db.php';

// Eliminar usuario si se envía la solicitud de eliminación
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header('Location: users.php');
    exit();
}

// Agregar usuario si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_user') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Asegúrate de guardar la contraseña de forma segura
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
    $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password, 'role' => $role]);

    header('Location: users.php');
    exit();
}

// Editar usuario si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_user') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
    $stmt->execute(['username' => $username, 'email' => $email, 'role' => $role, 'id' => $id]);

    header('Location: users.php');
    exit();
}

// Obtener la lista de usuarios
$sql = "SELECT id, username, email, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1>Gestión de Usuarios</h1>
    
    <!-- Formulario para añadir un usuario -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">Añadir Usuario</button>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editUserModal" 
                                data-id="<?= $row['id'] ?>" 
                                data-username="<?= $row['username'] ?>" 
                                data-email="<?= $row['email'] ?>" 
                                data-role="<?= $row['role'] ?>">Editar</button>
                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Está seguro de eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal para añadir usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <input type="hidden" name="action" value="add_user">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Añadir Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="add-username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="add-username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="add-email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="add-email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="add-password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="add-password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="add-role" class="form-label">Rol</label>
                    <select class="form-select" id="add-role" name="role" required>
                        <option value="admin">Administrador</option>
                        <option value="user">Usuario</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para editar usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST">
            <input type="hidden" name="action" value="edit_user">
            <input type="hidden" id="edit-id" name="id">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit-username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="edit-username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="edit-email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="edit-email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="edit-role" class="form-label">Rol</label>
                    <select class="form-select" id="edit-role" name="role" required>
                        <option value="admin">Administrador</option>
                        <option value="user">Usuario</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Pasar datos al modal de edición
    var editUserModal = document.getElementById('editUserModal');
    editUserModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var username = button.getAttribute('data-username');
        var email = button.getAttribute('data-email');
        var role = button.getAttribute('data-role');

        editUserModal.querySelector('#edit-id').value = id;
        editUserModal.querySelector('#edit-username').value = username;
        editUserModal.querySelector('#edit-email').value = email;
        editUserModal.querySelector('#edit-role').value = role;
    });
</script>
</body>
</html>
