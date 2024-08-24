<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="/php/css/bootstrap.4.4.1.css">
    <style>
        table {
            width: 75%; 
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
</head>
<body>
    <h1 class="center">User Details</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <!--<th>Select</th>-->
                    <th>User Name</th>
                    <th>Location</th>
                    <th>Email</th>
                    <th>Date of Birth</th>
                    <th>User Type</th>
                    <th>Active</th>
                    <th style='text-align:center; vertical-align:middle'>Modify</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    /*$host = 'localhost';
                    $db_name = 'college';
                    $username = 'root';
                    $password = 'sasa';
                    $port = '3306';*/

                    require 'config.php';

                    $NoRecordsFound = false;
                    $conn = null; $SQL = null; $result = null; $data = null;
                    
                    $conn = new mysqli($host, $username, $password, $db_name, $port);
                    $SQL = "SELECT USER_ID, USER_NAME, USER_LOC, USER_EMAIL, DATE_FORMAT(USER_DOB, '%d/%m/%Y') AS USER_DOB, USER_TYPE, ACTIVE FROM USER_MAST ORDER BY USER_ID DESC";
                    $result = $conn->query($SQL);
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    if($data)
                    {
                        foreach($data as $row)
                        {
                ?>                         
                            <tr>
                                <td><span><?php echo $row['USER_NAME']; ?></span></td>
                                <td><span><?php echo $row['USER_LOC']; ?></span></td>
                                <td><span><?php echo $row['USER_EMAIL']; ?></span></td>
                                <td><span><?php echo $row['USER_DOB']; ?></span></td>
                                <td><span><?php echo $row['USER_TYPE']; ?></span></td>
                                <td><span><?php echo $row['ACTIVE']; ?></span></td>
                                <td style='text-align:center; vertical-align:middle'><button onclick="window.location.href='UserAction.php?MODE=V&USERID=<?php echo $row['USER_ID']; ?>'
                                                    <?php echo $NoRecordsFound ? 'disabled': ''; ?>;">Modify User</button></td>
                            </tr>
                <?php
                        }
                    }
                    else
                    {
                        $NoRecordsFound = true;
                ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">
                            <?php echo "No Records Found!"; ?>
                            </td>
                        </tr>
                <?php
                    }
                    $conn->close(); $conn = null; $SQL = null; $result = null; $data = null;
                ?>  
                    <tr>
                        <td colspan="7">
                            <div class="button-container">
                                <div class="left-buttons">
                                <button onclick="window.location.href='UserAction.php?MODE=I';">Add New User</button>
                                </div>
                                <button onclick="window.close();">Cancel</button>
                            </div>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <input type="hidden" id="chk_USERID" value=''/> 
</body>
</html>
