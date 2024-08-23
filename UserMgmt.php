<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="/php/css/bootstrap.4.4.1.css">
    <style>
        table, th, td {
            margin-top: 10px;
            border: 1px solid black;
        }
        h1{
            text-align: center;
        }
        .button-container {
            margin-top: 20px;
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
    <script>
        var strUserID = null;
        var checkboxes = document.getElementsByName("ALL_CHECKBOX");
        var ele_UserID = document.getElementsById("chk_USERID");
        
        function getCheckUser(strUserID)
        {
            alert('ok');
            var i = 0;
            var n = checkboxes.length;
            for (i = 0, i < n; i++)
            {
                if(checkboxes[i].checked == true)
                {
                    ele_UserID.value = checkboxes[i].id;
                }
                else
                {
                    for (k = 0, k < n; k++)
                    {
                        if(checkboxes[k].id != ele_UserID.value)
                        {
                            checkboxes[k].checked = false;
                        }
                    }
                }
            }
            ele_UserID = null;
        }        

        function getModifyUser(strUserID_Chk)
        {
            var strUserID = '';
            var checkboxes = document.getElementsByName("ALL_CHECKBOX");
            var isChecked = document.getElementById(strUserID_Chk).checked;
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                if (checkboxes[i].id != sSalesPeriodID) 
                { checkboxes[i].checked = false; }
            }            
            
            if(isChecked)
            {   
                strUserID = document.getElementById(strUserID_Chk).id;
            }
            window.location.href='UserAction.php?MODE=U&USER_ID=' + strUserID;
        }
    </script>    
</head>
<body>
    <h1 class="center">User Details</h1>
    <table style="width:100%;">
        <thead>
            <tr>
                <th>Select</th>
                <th>User Name</th>
                <th>Location</th>
                <th>Email</th>
                <th>Date of Birth</th>
                <th>User Type</th>
                <th>Active</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $host = 'localhost';
                $db_name = 'college';
                $username = 'root';
                $password = 'sasa';
                $port = '3306';
                $NoRecordsFound = false;
                $conn = null; $SQL = null; $result = null; $data = null;
                
                $conn = new mysqli($host, $username, $password, $db_name, $port);
                $SQL = "SELECT USER_ID, USER_NAME, USER_LOC, USER_EMAIL, USER_DOB, USER_TYPE, ACTIVE FROM USER_MAST ORDER BY USER_ID DESC";
                $result = $conn->query($SQL);
                $data = $result->fetch_all(MYSQLI_ASSOC);
                if($data)
                {
                    foreach($data as $row)
                    {
            ?>                         
                        <tr>
                            <td>
                                <input type="checkbox" name="ALL_CHECKBOX" id="<?php echo $row['USER_ID']; ?>" onclick="getCheckUser('<?php echo $row['USER_ID']; ?>');" />
                            </td>
                            <td><span><?php echo $row['USER_NAME']; ?></span></td>
                            <td><span><?php echo $row['USER_LOC']; ?></span></td>
                            <td><span><?php echo $row['USER_EMAIL']; ?></span></td>
                            <td><span><?php echo $row['USER_DOB']; ?></span></td>
                            <td><span><?php echo $row['USER_TYPE']; ?></span></td>
                            <td><span><?php echo $row['ACTIVE']; ?></span></td>
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
            ?>  
        </tbody>
    </table>
    <div class="button-container">
        <div class="left-buttons">
            <button onclick="window.location.href='UserAction.php?MODE=I';">Add New User</button>
            <button onclick="getModifyUser();" <?php echo $NoRecordsFound ? 'disabled': ''; ?>>Modify User</button>
        </div>
        <button onclick="window.close();">Cancel</button>
    </div>
    <input type="hidden" id="chk_USERID" value=''>; 
</body>
</html>