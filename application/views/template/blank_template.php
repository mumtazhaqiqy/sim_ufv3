<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <?php echo $this->layout->get_meta_tags() ?>
    <?php echo $this->layout->get_title() ?>
    <?php echo $this->layout->get_favicon() ?>
    <?php echo $this->layout->get_schema() ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
    <?php
        $path = [
            'assets/plugins/bootstrap/dist/css/bootstrap.min.css',
            'assets/plugins/AdminLTE/dist/css/AdminLTE.min.css',
            'assets/plugins/AdminLTE/dist/css/skins/skin-'.$this->config->item('skin').'.min.css',
            'assets/plugins/font-awesome/css/font-awesome.min.css',
            'assets/plugins/alertify-js/build/css/alertify.min.css',
            'assets/plugins/alertify-js/build/css/themes/default.min.css',
            'assets/plugins/iCheck/skins/square/blue.css',
            'assets/plugins/flag-icon-css/css/flag-icon.min.css',
            'assets/plugins/jquery-confirm/dist/jquery-confirm.min.css',
        ];
        if (isset($grocery_css)) {
            foreach ($grocery_css as $key => $value) {
                $path[] = $value;
            }
        }
        if (isset($css_plugins)) {
            foreach ($css_plugins as $key => $value) {
                $path[] = $value;
            }
        }
        $path[] = 'assets/css/a-design.css';
    ?>
    <?php $this->layout->set_assets($path, 'styles') ?>
    <?php echo $this->layout->get_assets('styles') ?>    
    <?php
        $baseJs = ['assets/plugins/jquery/dist/jquery.min.js'];
        if (isset($grocery_js)) {
            foreach ($grocery_js as $key => $value) {
                $baseJs[] = $value;
            }
        }
        $path = [
            'assets/plugins/bootstrap/dist/js/bootstrap.min.js',
            'assets/plugins/AdminLTE/dist/js/app.min.js',
            'assets/plugins/alertify-js/build/alertify.min.js',
            'assets/plugins/slimScroll/jquery.slimscroll.min.js',
            'assets/plugins/list.js/dist/list.min.js',
            'assets/plugins/iCheck/icheck.min.js',
            'assets/plugins/jquery-confirm/dist/jquery-confirm.min.js'
        ];
        $path = array_merge($baseJs, $path);
        $path[] = 'assets/js/a-design.js';
    ?>
    <?php $this->layout->set_assets($path, 'scripts') ?>
    <?php echo $this->layout->get_assets('scripts') ?>
</head>
<body>
    <!-- Site wrapper -->
    <div class="wrapper">  
        <!-- Content Wrapper. Contains page content -->
        
        <?php echo $this->layout->get_wrapper('page') ?>
        
    </div><!-- ./wrapper -->
    <?php
    if (isset($js_plugins)) {
        foreach ($js_plugins as $key => $value) {
            $path2[] = $value;
        }
    }?>
    <?php $this->layout->set_assets($path2, 'scripts') ?>
    <?php echo $this->layout->get_assets('scripts') ?>

    <script>
        // Send message to the top window (parent) at 500ms interval
    setInterval(function() {
        // first parameter is the message to be passed
        // second paramter is the domain of the parent 
        // in this case "*" has been used for demo sake (denoting no preference)
        // in production always pass the target domain for which the message is intended 
        window.top.postMessage(document.body.scrollHeight, "*");
    }, 500); 
    
    </script>
</body>
</html>