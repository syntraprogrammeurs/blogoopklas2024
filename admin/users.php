<?php
include("includes/header.php");
include("includes/sidebar.php");
include("includes/content-top.php");
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="page-header">All Users</h1>
                <hr>

                <table class="table table-dark mb-0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>USERNAME</th>
                        <th>PASSWORD</th>
                        <th>FIRST_NAME</th>
                        <th>LAST_NAME</th>
                        <th>ACTION</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $users = User::find_all();
                    ?>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td class="text-bold-500"><?php echo $user->id; ?></td>
                            <td class="d-flex align-items-center">
                                <div class="avatar me-2">
                                    <img src="../admin/assets/compiled/jpg/1.jpg" alt="avtar img holder">
                                </div>
                                <?php echo $user->username; ?>
                            </td>
                            <td class="text-bold-500"><?php echo $user->password; ?></td>
                            <td><?php echo $user->first_name; ?></td>
                            <td><?php echo $user->last_name; ?></td>
                            <td><a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail badge-circle font-medium-1"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>


            </div>
        </div>
    </div>


<?php
include("includes/footer.php");
?>