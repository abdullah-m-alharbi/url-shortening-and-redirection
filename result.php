<?php
session_start();
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
                alert("Copied!");
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
                max-width:758px;
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


            #form_url button{height:56px;padding:10px 16px;font:bold 17px lato,arial;color:#fff;background-color:#2c87c5;text-align:center;vertical-align:middle;cursor:pointer;white-space:nowrap;border:0;border-radius:3px;}

            p{font:30px 'source sans pro',arial;color:#484848;line-height:1.5;text-align:left}
        </style>

    <div class="header">
        <img src="logo-3.png">

    </div>
</head>
<body>
    <section id="box">
        <div id="form_url">
            <br>
            <section id="box">
                <h1>Shortening and Redirection Service</h1>

                <input type="text" value="<?php echo $_SESSION["newurl"]; ?>" id="myInput">
                <br>
                <br>
                <button onclick="myFunction()">Copy text</button>
                <br>
            </section>

        </div>
    </section>
</body>

</html>

