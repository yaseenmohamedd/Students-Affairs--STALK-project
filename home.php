<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Affairs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: whitesmoke;
            font-family: "PT Serif", serif;
            font-weight: 700;
            font-style: normal;
        }

        label {
            font-weight: bold;
        }

        #logo {
            height: 100px;
            width: 100px;
            border-radius: 20px;
        }

        #main {
            width: 100%;
            font-size: 20px;
        }

        th, td {
            padding: 17px;
        }

        main {
            float: right;
            border: 2px solid gray;
            padding: 8px;
        }

        input {
            padding: 5px;
            border: 3px solid black;
            font-family: "PT Serif", serif;
            font-size: 15px;
            text-align: center;
        }

        aside {
            float: left;
            width: 25%;
            padding: 20px;
            background-color: skyblue;
            text-align: center;
            border: 5px solid black;
            border-radius: 5px;
            font-size: 25px;
        }

        table {
            width: 880px;
            background-color: silver;
            color: black;
            font-size: 20px;
            text-align: center;
        }

        th {
            background-color: silver;
            padding: 10px;
        }

        aside button {
            padding: 8px;
            width: 150px;
            font-size: 20px;
            font-weight: bold;
            font-family: "PT Serif", serif;
        }
    </style>
</head>

<body dir="ltr">
    <?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "students";
    $conn = mysqli_connect($host, $user, $password, $db);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $show = mysqli_query($conn, "SELECT * FROM student");
    $id = "";
    $name = "";
    $address = "";
    $is_editing = false;

    if (isset($_POST["id"])) {
        $id = $_POST["id"];
    }
    if (isset($_POST["name"])) {
        $name = $_POST["name"];
    }
    if (isset($_POST["address"])) {
        $address = $_POST["address"];
    }

    if (isset($_POST["add"]) && !empty($id) && !empty($name) && !empty($address)) {
        $sqli = "INSERT INTO student (id, name, address) VALUES ('$id', '$name', '$address')";
        if (mysqli_query($conn, $sqli)) {
            header("Location: home.php");
            exit(); // Make sure to exit after header redirection
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["delete"])) {
        $delete_id = $_POST["delete"];
        $sqls = "DELETE FROM student WHERE id = '$delete_id'";
        if (mysqli_query($conn, $sqls)) {
            header("Location: home.php");
            exit(); // Make sure to exit after header redirection
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["edit"])) {
        $id = $_POST["edit"];
        $sql_search = "SELECT * FROM student WHERE id = '$id'";
        $result_search = mysqli_query($conn, $sql_search);
        if ($result_search) {
            $row_search = mysqli_fetch_assoc($result_search);
            if ($row_search) {
                $id = $row_search['id'];
                $name = $row_search['name'];
                $address = $row_search['address'];
                $is_editing = true;
            } else {
                echo "No student found with ID: $id";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["save"]) && !empty($id) && !empty($name) && !empty($address)) {
        $sql_edit = "UPDATE student SET name='$name', address='$address' WHERE id='$id'";
        if (mysqli_query($conn, $sql_edit)) {
            header("Location: home.php");
            exit(); // Make sure to exit after header redirection
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

    if (isset($_POST["search"]) && !empty($_POST["search_id"])) {
        $search_id = $_POST["search_id"];
        $sql_search = "SELECT * FROM student WHERE id = '$search_id'";
        $result_search = mysqli_query($conn, $sql_search);
        if ($result_search) {
            $row_search = mysqli_fetch_assoc($result_search);
            if ($row_search) {
                $id = $row_search['id'];
                $name = $row_search['name'];
                $address = $row_search['address'];
                $is_editing = true;
            } else {
                echo "No student found with ID: $search_id";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
    ?>

    <div id="main">
        <form action="" method="post">
            <aside>
                <div id="form">
                    <img id="logo" src="Images/student_8289404.png" alt="website logo">
                    <h1>Control Panel</h1>
                    <label for="id">Student Id: </label><br>
                    <input type="text" name="id" id="id" value="<?php echo $id; ?>"><br><br>
                    <label for="name">Student Name: </label><br>
                    <input type="text" name="name" id="name" value="<?php echo $name; ?>"><br><br>
                    <label for="address">Address: </label><br>
                    <input type="text" name="address" id="address" value="<?php echo $address; ?>"><br><br>
                    <?php if ($is_editing) { ?>
                        <button name="save">Save</button>
                    <?php } else { ?>
                        <button name="add">Add</button>
                    <?php } ?>
                    <br><br>
                    <label for="search_id">Enter ID: </label><br>
                    <input type="text" name="search_id" id="search_id"><br><br>
                    <button name="search">Search</button>
                </div>
            </aside>
            <main>
                <table>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    while ($row = mysqli_fetch_array($show)) {
                        echo  "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>";
                        echo "<button type='submit' name='edit' value='" . $row['id'] . "'>Edit</button>";
                        echo "<button type='submit' name='delete' value='" . $row['id'] . "'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </main>
        </form>
    </div>
</body>

</html>
