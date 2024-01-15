<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="page-header">Testen database connectie</h1>
            <hr>
            <?php
              if($conn = $database->connection) {
                  //parameters
                  $id = mysqli_real_escape_string($conn, 1);
                  //connection
                  $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id=?");
                  //bind parameters
                  mysqli_stmt_bind_param($stmt, "s", $id);
                  //execute statement
                  mysqli_stmt_execute($stmt);
                  // process the result
                  $result = mysqli_stmt_get_result($stmt);
                  // close the statement
                  mysqli_stmt_close($stmt);
                  // close the connection
                  mysqli_close($conn);
                  // check if there are any rows in the result
                  if (mysqli_num_rows($result) > 0) {
                      // output data of each row
                      while ($row = mysqli_fetch_array($result)) {
                          // process each row here
                          // for example, you can print the username and password like this:
                          echo "<br> id: " . $row['id'] . " " . $row['username'] . $row['first_name'] . "<br>";
                      }
                  } else {
                      echo "0 results";
                  }
              }else{
                  echo "fout!";
              }
            ?>

        </div>
    </div>
</div>