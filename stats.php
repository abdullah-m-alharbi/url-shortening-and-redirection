<?php
session_start();
//require_once 'dbConfig.php';
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "URL_Service";
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
// get property info for property that was clicked on
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Page Title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>

            function myFunction() {
                /* Get the text field */
                var copyText = document.getElementById("myInput");
                /* Select the text field */
                copyText.select();
                copyText.setSelectionRange(0, 99999); /*For mobile devices*/
                /* Copy the text inside the text field */
                document.execCommand("copy");
                /* Alert the copied text */
                alert("Copied the text: " + copyText.value);
            }
        </script>
        <style>
            .header {
                padding: 10px;
                text-align: center;
                background: #ededed;
                color: black;
                font-size: 30px;
            }

            #box{
                margin:0 auto 20px auto;
                max-width:75%;
                box-shadow:0 1px 4px #ccc;
                border-radius:2px;padding:10px 30px 5px;
                background:#fff;
                text-align:center}


            #form_url input[type=text]{
                display:table-cell;
                width:100%;height:56px;padding:10px 16px;
                font:17px lato,arial;color:#000;
                background:#fff;
                border:2px solid #bbb;
                border-right:3;
                border-radius:3px;
                border-bottom-right-radius:5;
                border-top-right-radius:5;
                box-sizing:border-box}

            #form_url table{
                display:table-cell;
                width:100%;height:56px;padding:10px 16px;
                font:17px lato,arial;color:#000;
                background:#fff;
                border:2px solid #bbb;
                border-right:3;
                border-radius:3px;
                border-bottom-right-radius:5;
                border-top-right-radius:5;
                box-sizing:border-box}

            #form_url button{height:56px;padding:10px 16px;font:bold 17px lato,arial;color:#fff;background-color:#2c87c5;text-align:center;vertical-align:middle;cursor:pointer;white-space:nowrap;border:0;border-radius:3px;}

            p{font:30px 'source sans pro',arial;color:#484848;line-height:1.5;text-align:left}

            #customers {
                font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            #customers td, #customers th {
                border: 1px solid #ddd;
                padding: 8px;
                text-align:left
            }
            #customers tr:nth-child(even){background-color: #f2f2f2;}
            #customers tr:hover {background-color: #ddd;}
            #customers th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #2c87c5;
                color: white;
            </style>


        <div class="header">
            <img src="logo-3.png">
        </div>
    </head>
    <body>
        <section id="box">
            <br>
            <section id="">
                <h1>Shortening and Redirection Service</h1>

                <?php
// Check connection
                if (mysqli_connect_errno()) {
                    echo "Failed to connect to MySQL: " . mysqli_connect_error();
                }
                $query = "SELECT * FROM records WHERE id=".$_POST['id'];
                $result = $conn->query($query);
                
                $query2 = "SELECT * FROM urls WHERE id=".$_POST['id'];
                $result2 = $conn->query($query2);
                
                $row2 = mysqli_fetch_array($result2);
               echo "<h2>URL description: ".$row2['description']."<br>Long URL: <a href='".$row2['long_url']."'>".$row2['long_url']."</a><br>Short URL: <a href='http://localhost/url-service/".$row2['short_url']."'>".$row2['short_url']."</a></h2>";
                
                echo "<table id='customers' border='1'>
<tr>
<th>ID</th>
<th>Country</th>
<th>City</th>
<th>Created</th>

</tr>";
                $x = 1;
                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . ($x++) . "</td>";
                    echo "<td>" . $row['country'] . "</td>";
                    echo "<td>" . $row['city'] . "</td>";
                    echo "<td>" . $row['created'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                mysqli_close($conn);
                ?>     

                <br>
            </section>
                    <div id="form_url">
             <button onclick="window.location.href = 'index.php';">Home</button>
            </div>
        </section>
    </body>
</html>