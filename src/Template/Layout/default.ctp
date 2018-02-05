<!DOCTYPE html>
<html lang="en">
    <?php echo $this->element('head'); ?>  
    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">                    
                    <a class="navbar-brand" href="javascript:void(0)">Go to University</a>
                </div>                
            </div>
        </nav>

        <div class="container">
            <div class="starter-template">
                <?php echo $this->fetch('content') ?>                
            </div>
        </div><!-- /.container -->


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->        
        <?php echo $this->Html->script('bootstrap.min') ?>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <?php echo $this->Html->script('ie-emulation-modes-warning') ?>
        <?php echo $this->fetch('script') ?>        
    </body>
</html>
