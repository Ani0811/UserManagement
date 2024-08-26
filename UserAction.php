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
    
    $b_No_Record_Found = 0;
    $sRedirectURL = 'http://' . $_SERVER['SERVER_NAME'] . "/php/UserMgmt.php";

    //$s_URL = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    //getCheckQryString();
    
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
                    else
                    {
                        $message = "Error: User not found !.Click on <BACK TO VIEW>.";
                        echo '<script language="javascript">alert("'. $message .'")</script>';
                        $b_No_Record_Found = 1;
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
                            $message = "Registration successful ! Do you want add another user? [Yes/No]";

                            //echo '<script language="javascript">alert("'. $message .'"); get_Clear_form();</script>';
                            echo '<script language="javascript">confirm("'. $message .'"); get_Clear_form();</script>';
                            $user_name = ''; $loc = ''; $email = ''; $DOB = ''; $uType = ''; $uActive = ''; $pwd = '';
                        }
                        else 
                        {
                            $message = "Error: " . $stmt->error;
                            echo '<script language="javascript">alert("'. $message .'");</script>';
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
                            echo '<script language="javascript">alert("'. $message .'"); get_Clear_form();</script>';
                        }
                        else
                        {
                            $message = "Error: " . $stmt->error;
                            echo '<script language="javascript">alert("'. $message .'");</script>';
                        }
                    }
                }
            }
            $conn -> close();
        }
    }
    catch(Exception $e) {  $message = "Error: " . $e->getMessage(); echo '<script language="javascript">alert("'. $message .'")</script>';}
    finally { $conn = null; $stmt = null; $SQL = null;}

    /*
    function getCheckQryString()
    {
        $sRedirectURL = 'http://' . $_SERVER['SERVER_NAME'] . "/php/UserMgmt.php";
        try
        {
            if(strlen($_SERVER['QUERY_STRING']) > 0)
            {
                parse_str($_SERVER['QUERY_STRING'], $s_Qry);
                if(count($s_Qry) == 1)
                {
                    $Mode = $s_Qry['MODE']; 
                    if(!$s_Qry['MODE']) { throw new Exception(redirect($sRedirectURL)); }
                }
                else if(count($s_Qry) == 2)
                {
                    $Mode = $s_Qry['MODE'];
                    if(!$s_Qry['MODE']) { throw new Exception(redirect($sRedirectURL)); }

                    $USER_ID = $s_Qry['USERID'];
                    if(!$s_Qry['USERID']) { throw new Exception(redirect($sRedirectURL)); }
                }
                else { redirect($sRedirectURL); }

                if($Mode != 'I') { redirect($sRedirectURL); }
                if($Mode != 'U') { redirect($sRedirectURL); }
                if($Mode != 'V') { redirect($sRedirectURL); }
            }
            else if (strlen($_SERVER['QUERY_STRING']) == 0) { redirect($sRedirectURL); }
        }
        catch(Exception $e) { redirect($sRedirectURL); }
    }
*/
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
    <link rel="stylesheet" href="/php/css/usac.css">
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
    <script>
        function Submit_Data(sURL)
        {
            var sURL = 'UserAction.php?MODE=<?php echo $Mode=="I"? "I" : "U&USER_ID=". $USER_ID ;?>';
            document.forms[0].action = sURL; //'UserAction.php?MODE=I';
            document.forms[0].submit();
        }
        function get_Clear_form()
        {
            document.getElementById('username').value = '';
            document.getElementById('loc').value = '';
            document.getElementById('email').value = '';
            document.getElementById('DOB').value = '';
            
            var uType = document.getElementById('uType');
            uType.selectedIndex = 0;
            
            var uActive = document.getElementById('uActive');
            uActive.selectedIndex = 0;

            document.getElementById('password').value = '';
            var btn_B_T_V = document.getElementById('btn_B_T_V')
            btn_B_T_V.setfocus();
        }
    </script>    
</head>
<body>
    <div>
    <form method="post">
        <div class="table-container">
            <h1>User Details</h1>
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
                        <td><input type="password" id="password" name="password" autocomplete="off" maxlength="20"  required></td>
                    </tr>
                    <tr>
                        <td colspan="2" class="button-cell">
                            <div class="button-container">
                                <button id="btn_B_T_V" onclick="window.location.href='UserMgmt.php';">Back To View</button>
                                <button id="btnSubmit" type="button" onclick="Submit_Data();" <?php if($b_No_Record_Found == 1) { ?> disabled <?php } ?>><?php echo $Mode == 'I' ? 'Register' : 'Update'; ?>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</body>
</html>
