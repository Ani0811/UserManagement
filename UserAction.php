<?php
    /*$host = 'localhost';
    $db_name = 'college';
    $username = 'root';
    $password = 'sasa';
    $port = '3306';*/

    require 'config.php';

    $conn = null; $SQL = null; $result = null; $data = null;

    $user_name = '';
    $loc = '';
    $email = '';
    $DOB = '';
    $uType = '';
    $uActive = '';
    $pwd = '';

    $Mode = '';
    $USER_ID = '';

    $message = '';
    $message_type = '';
    
    $s_Server_URL = '';

    $s_URL = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    if(strripos($s_URL, '?') == false)
    {
        redirect('http://' . $_SERVER['SERVER_NAME'] . "/php/UserMgmt.php");
    }

    if (isset($_GET['MODE']) && $_GET['MODE'] != '') 
    {
        $Mode = $_GET['MODE'];
    }
    if (isset($_GET['USER_ID']) && $_GET['USER_ID'] != '') 
    {
        $USER_ID = $_GET['USER_ID'];
    }

    try
    {
        //if($_SERVER['REQUEST_METHOD'] == 'GET')
        //{
            //if(!($conn -> connect_error))
            //{
                if($Mode == 'V')
                {
                    $USER_ID = $_GET['USERID'];

                    $host = 'localhost';
                    $db_name = 'college';
                    $username = 'root';
                    $password = 'sasa';
                    $port = '3306';

                    $conn = null; $SQL = null; $result = null; $data = null;
                    $conn = new mysqli($host, $username, $password, $db_name, $port);
                    $SQL = "SELECT USER_ID, USER_NAME, USER_LOC, USER_EMAIL, 
                                   DATE_FORMAT(USER_DOB, '%d/%m/%Y') AS USER_DOB,
                                   USER_TYPE, ACTIVE FROM USER_MAST WHERE USER_ID = " . $USER_ID;

                    $result = $conn->query($SQL);
                    $data = $result->fetch_array(MYSQLI_BOTH); //MYSQLI_ASSOC);
                    
                    if($data !== null)
                    {
                        $user_name = $data['USER_NAME'];
                        $loc = $data['USER_LOC'];
                        $email = $data['USER_EMAIL'];
                        $DOB = $data['USER_DOB'];
                        $uType = $data['USER_TYPE'];
                        $uActive = $data['ACTIVE'];
                    }
                    $conn->close(); 
                    $conn = null; $result = null; $data = null; $SQL = null;  
                }
            //}
        //}
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $conn = new mysqli($host, $username, $password, $db_name, $port);
            //echo $conn ->ping();
            if(!($conn -> connect_error))
            {
                if($Mode == 'I')
                {
                    $user_name = $_POST['username'];
                    $loc = $_POST['loc'];
                    $email = $_POST['email'];
                    $DOB = $_POST['DOB'];
                    $uType = $_POST['uType'];
                    $uActive = $_POST['uActive'];
                    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $stmt = $conn -> prepare("INSERT INTO USER_MAST 
                                                            (
                                                            USER_NAME, USER_LOC, USER_EMAIL, USER_DOB,
                                                            USER_TYPE, ACTIVE, USER_PASSWORD
                                                            )
                                                        VALUES (?, ?, ?, STR_TO_DATE(?, '%d/%m/%Y'), ?, ?, ?)");
                    if($stmt)
                    {
                        $stmt -> bind_param("sssssss", $user_name, $loc, $email, $DOB, $uType, $uActive, $pwd);
                        if ($stmt->execute()) 
                        {
                            $message = "Registration successful!";
                            $message_type = "success";
                        }
                        else 
                        {
                            $message = "Error: " . $stmt->error;
                            $message_type = "error";
                        }
                        $stmt -> close();
                    }
                }
                else if($Mode == 'U')
                {
                    $user_name = $_POST['username'];
                    $loc = $_POST['loc'];
                    $email = $_POST['email'];
                    $DOB = $_POST['DOB'];
                    $uType = $_POST['uType'];
                    $uActive = $_POST['uActive'];
                    $pwd = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
                    $SQL = "UPDATE USER_MAST SET USER_NAME = ?, USER_LOC = ?, USER_EMAIL = ?, 
                                                 USER_DOB = STR_TO_DATE(?, '%d/%m/%Y'), USER_TYPE = ?, ACTIVE = ?,
                                                 USER_PASSWORD = ? 
                                            WHERE USER_ID = ?";
                    $stmt = $conn -> prepare($SQL);
                    if($stmt)
                    {
                        $stmt->bind_param("sssssssi", $user_name, $loc, $email, $DOB, $uType, $uActive, $pwd, $USER_ID);
                        if($stmt -> execute())
                        {
                            $message = "User details updated successfully";
                            $message_type = "success";
                        }
                        else
                        {
                            $message = "Error: " . $stmt->error;
                            $message_type = "error";
                        }
                    }
                }
            }
            $conn -> close();
        }
    }
    catch(Exception $e) {  $message = "Error: " . $e->getMessage(); $message_type = "error";}
    finally { $conn = null; $stmt = null; $SQL = null;}

    function redirect($url) 
    {
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
        table {
            width: 60%; 
            margin: 5px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        td.button-cell {
            padding: 5px;
            text-align: center; 
        }
        h1{
            text-align: center;
        }
        .button-container {
            margin-top: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .left-buttons {
            display: flex;
        }
        .left-buttons button {
            margin-right: 10px;
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

        <?php if ($message) { ?>
            alert('<?php echo $message; ?>');
        <?php } ?>
    });
    </script>
</head>
<body>
    <h1 class="center">User Management</h1>
    <form method="post" action="UserAction.php?MODE=<?php echo $Mode=="I"? "I" : "U&USER_ID=". $USER_ID ;?>">
        <table>
            <thead>
                <tr>
                    <th colspan="2">Enter User Details :- </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><label for="username">User Name :</label></td>
                    <td><input type="text" id="username" name="username" maxlength="30" value="<?php echo $user_name; ?>" <?php echo $Mode == 'V' ? 'readonly' : ''; ?> required></td>
                </tr>
                <tr>
                    <td><label for="loc">Location :</label></td>
                    <td><input type="text" id="loc" name="loc" maxlength="50" value="<?php echo $loc; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="email">Email :</label></td>
                    <td><input type="email" id="email" name="email" maxlength="30" value="<?php echo $email; ?>" required></td>
                </tr>
                <tr>
                    <td><label for="dob">Date of Birth :</label></td>
                    <td>
                        <input type="text" id="DOB" name="DOB" autocomplete="off" maxlength="10" value="<?php echo $DOB; ?>" required>
                    </td>
                </tr>
                <tr>
                    <td><label for="uType">User Type :</label></td>
                    <td>
                        <select id="uType" name="uType" required>
                        <?php
                            if($uType == '')
                            {
                        ?>
                                <option selected>-Select-</option>
                                <option value="Admin">Admin</option>
                                <option value="Group User">Group User</option>
                        <?php
                            }
                            else if($uType == 'Admin')
                            {
                        ?>
                                <option>-Select-</option>
                                <option value="Admin" selected>Admin</option>
                                <option value="Group User">Group User</option>
                        <?php
                            }
                            else if($uType == 'Group User')
                            {
                        ?>
                                <option>-Select-</option>
                                <option value="Admin">Admin</option>
                                <option value="Group User" selected>Group User</option>
                        <?php
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                <td><label for="uActive">Active :</label></td>
                    <td>
                        <select id="uActive" name="uActive" required>
                        <?php
                            if($uActive == '')
                            {
                        ?>
                                <option selected>-Select-</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                        <?php
                            }
                            else if($uActive == 'Yes')
                            {
                        ?>
                                <option>-Select-</option>
                                <option value="Yes" selected>Yes</option>
                                <option value="No">No</option>
                        <?php
                            }
                            else if($uActive == 'No')
                            {
                        ?>
                                <option>-Select-</option>
                                <option value="Yes">Yes</option>
                                <option value="No" selected>No</option>
                        <?php
                            }
                        ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="password">Password :</label></td>
                    <td><input type="password" id="password" name="password" required></td>
                </tr>
                <tr>
                    <td colspan="2" class="button-cell">
                        <div class="button-container">
                            <button onclick="window.location.href='UserMgmt.php';">Back To View</button>
                            <button type="submit"><?php echo $Mode == 'I' ? 'Register' : 'Update'; ?>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</body>
</html>
