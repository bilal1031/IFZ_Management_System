
<!DOCTYPE html>
<html>
<head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <script src="./js/jquery.min.js"></script>
        <script src="./js/popper.min.js"></script>
        <script src="./js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
<body style="  background-image: url('./photos/wallpaper1.jpg');
               background-size: 100%;
               background-repeat: no-repeat;
               background-attachment: fixed;">

  <?php             
        include "./Components/navbar.php";
        navbar("about");
        include "./Components/about_div.php";
        include "./Components/footer.php";
  ?>  

</body>
</html>