<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>DoKado</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='styles.css'>
    <script src='main.js'></script>
</head>
<body>
    <?php include 'includes\header.php';

        // Determine the page to display
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        // Switch statement to include the appropriate page
        switch ($page) {
            case 'herenkleding':
                include 'pages\heren_kleding.php';
                break;

            case 'dameskleding':
                include 'pages\dames_kleding.php';
                break;    

            case 'contact':
                include 'pages/contact.php';
                break;

            default:
                include 'pages/home.php';
                break;
        }
        
    
    include 'includes\footer.php';
    
    ?>
    
</body>
</html>