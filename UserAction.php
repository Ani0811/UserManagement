<?php
    $host = 'localhost';
    $db_name = 'college';
    $username = 'root';
    $password = 'sasa';
    $port = '3306';
    $Mode = '';
    $conn = null; $stmt = null; $SQL = null;

    echo $_GET['MODE'];
    if (isset($_GET['MODE']) && $_GET['MODE'] != '') 
    {
        $Mode = $_GET['MODE'];
    } /*else 
    {
        //Redirect to usermgmt.php here
        redirect('/php/UserMgmt.php');
    }
    */

    try
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $conn = new mysqli($host, $username, $password, $db_name, $port);
            echo $conn ->ping();
            if(!($conn -> connect_error))
            {
                if($Mode == 'I')
                {
                    $username = $_POST['username'];
                    $loc = $_POST['loc'];
                    $email = $_POST['email'];
                    $dob = $_POST['DOB'];
                    $uType = $_POST['uType'];
                    $uActive = $_POST['uActive'];
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $stmt = $conn -> prepare("INSERT INTO USER_MAST 
                                                            (
                                                            USER_NAME, USER_LOC, USER_EMAIL, USER_DOB,
                                                            USER_TYPE, ACTIVE, USER_PASSWORD
                                                            )
                                                        VALUES (?, ?, ?, STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?)");
                    if($stmt)
                    {
                        $stmt -> bind_param("sssssss", $username, $loc, $email, $dob, $uType, $uActive, $password);
                        if ($stmt->execute()) 
                        {
                            echo "Registration successful!";
                        }
                        else 
                        {
                            echo "Error: " . $stmt->error;
                        }
                        $stmt -> close();
                    }
                }
                else if($Mode == 'U')
                {
                    $id = $_POST['id'];
                    $username = $_POST['username'];
                    $email = $_POST['email'];
            
                    $sql = "UPDATE USER_MAST SET USER_NAME = ?, USER_LOC = ?, USER_EMAIL = ?, 
                                                    USER_DOB = STR_TO_DATE(?, '%d/%m/%Y'), USER_TYPE = ?, ACTIVE = ? 
                                            WHERE USER_ID = ?";
                    $stmt = $mysqli -> prepare($sql);
                    if($stmt)
                    {
                        $stmt->bind_param("ssssssi", $username, $loc, $email, $dob, $uType, $uActive, $id);
                        if($stmt -> execute())
                        {
                            echo "User details updated successfully";
                        }
                        else
                        {
                            echo "Error: " . $stmt->error;
                        }
                    }
                }
            $conn -> close();
            }
        }
    }
    catch(Exception $e) { echo "Error : " . $e -> getMessage();}
    finally { $conn = null; $stmt = null; $SQL = null;}
    function redirect($url) {
        header('Location: '.$url);
        die();
    }    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Action</title>
    <link rel="stylesheet" href="/php/css/bootstrap.4.4.1.css">
    <link rel="stylesheet" href="/php/css/bootstrap-datepicker.css">
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
            margin-left: auto;
            margin-right: auto;
        }
        th, td {
            text-align: left;
        }
        h1{
            text-align: center;
        }
        .button-container{
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }
        .button-container button{
            padding: 5px 10px;
        }
    </style>
    <script src="/php/js/jquery.js"></script>
    <script src="/php/js/bootstrap-datepicker.1.9.0.js"></script>
    <script>
    $(document).ready(function() {
        $('#DOB').datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true
        });
    });
	</script>
</head>
<body>
    <h1 class="center">User Management</h1>
    <form method="post" action="UserAction.php?MODE=I">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Enter User Details :- </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="username">User Name :</label></td>
                    <td><input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="loc">Location :</label></td>
                    <td><input type="text" id="loc" name="loc" value="<?php echo isset($_POST['loc']) ? $_POST['loc'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="email">Email :</label></td>
                    <td><input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="dob">Date of Birth :</label></td>
                    <td>
                        <input type="text" id="DOB" name="DOB" autocomplete="off" value="<?php echo isset($_POST['DOB']) ? $_POST['DOB'] : ''; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="uType">User Type :</label></td>
                    <td>
                        <select id="uType" name="uType" required>
                            <option selectedIndex="-1">-Select-</option>
                            <option value="Admin" <?php echo isset($_POST['uType']) && $_POST['uType'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="Group User" <?php echo isset($_POST['uType']) && $_POST['uType'] == 'Group User' ? 'selected' : ''; ?>>Group User</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="uActive">Active:</label></td>
                    <td>
                        <select id="uActive" name="uActive" required>
                            <option value="" selected>-Select-</option>
                            <option value="Yes" <?php echo isset($_POST['uActive']) && $_POST['uActive'] == 'Yes' ? 'selected' : ''; ?>>Yes</option>
                            <option value="No" <?php echo isset($_POST['uActive']) && $_POST['uActive'] == 'No' ? 'selected' : ''; ?>>No</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="password">Password :</label></td>
                    <td><input type="password" id="password" name="password" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <div class="button-container">
                            <button onclick="window.location.href='UserMgmt.php';">Back To View</button>
                            <button type="submit" onclick="window.location.href='UserAction.php?MODE=I';">
                                <?php echo $Mode == 'I' ? 'Register' : 'Update'; ?>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</body>
</html>
