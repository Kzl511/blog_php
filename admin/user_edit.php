<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
}

if ($_POST) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($_POST['isAdmin'])) {
        $role = 1;
    } else {
        $role = 0;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stmt->execute(
        array (
            ':id' => $id,
            ':email' => $email
        )
    );
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<script>alert('Email Duplicated!');</script>";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name='$name', email='$email', password='$password', role='$role' WHERE id='$id'");
        $result = $stmt->execute();
    
        if ($result) {
            echo "<script>alert('Successfully updated!');  window.location.href='user_list.php';</script>";
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id =" . $_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
?>

<?php include('header.php'); ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                                <label for="">Name</label>
                                <input type="text" name="name" class="form-control" value="<?php echo $result[0]['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $result[0]['email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control" value="<?php echo $result[0]['password']; ?>" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="checkbox" id="isAdmin" name="isAdmin" value="1" style="margin-right: 5px;">
                                <label for="isAdmin" class="mb-0"> Create as Admin?</label>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-success" value="Submit">
                                <a href="index.php" class="btn btn-warning">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<?php include('footer.php'); ?>