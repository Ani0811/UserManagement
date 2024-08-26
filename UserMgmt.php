<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="/php/css/bootstrap.4.4.1.css">
    <link rel="stylesheet" href="/php/css/usmp.css">
</head>
<body>
    <h1>User Details</h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
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
                    require 'config.php';

                    $NoRecordsFound = false; $NoOfRows = 0;
                    $conn = null; $SQL = null; $result = null; $data = null;
                    
                    $conn = new mysqli($host, $username, $password, $db_name, $port);
                    $SQL = "SELECT USER_ID, USER_NAME, USER_LOC, USER_EMAIL, DATE_FORMAT(USER_DOB, '%d/%m/%Y') AS USER_DOB, USER_TYPE, ACTIVE FROM USER_MAST ORDER BY USER_ID DESC";
                    $result = $conn->query($SQL);
                    $data = $result->fetch_all(MYSQLI_ASSOC);
                    if($data)
                    {
                        $NoOfRows = $result -> num_rows;
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
                                <td style='text-align:center; vertical-align:middle'>
                                    <button onclick="window.location.href='UserAction.php?MODE=V&USERID=<?php echo $row['USER_ID']; ?>'
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
                    </tbody>
            </table>
    </div>
    <div>
        <table>
            <tbody>
                <tr>
                    <td style="width:11%">
                        <button onclick="window.location.href='UserAction.php?MODE=I';">Add New User</button>
                    </td>
                    <td style='width:100%; text-align: center;'>
                        <label for="nor">No. of Records : <?php echo $NoOfRows; ?></label>
                    </td>
                    <td style='width:10%; text-align:right;'>
                        <button onclick="window.close();">Cancel</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
