<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">    
    <?php echo $this->Html->meta('icon') ?>

    <title> <?php echo $this->fetch('title') ?></title>


    <?php echo $this->Html->css('bootstrap.min') ?>
    <?php echo $this->Html->css('ie10-viewport-bug-workaround') ?>
    <?php echo $this->Html->css('starter-template') ?>

    <?php echo $this->Html->javascript('ie-emulation-modes-warning') ?>

    <!--[if lt IE 9]-->
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <!--<![endif]-->

    <?php echo $this->fetch('meta') ?>
    <?php echo $this->fetch('css') ?>
    <?php echo $this->fetch('script') ?>


</head>
