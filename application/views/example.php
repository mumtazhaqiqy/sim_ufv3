<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<script src="http://ummicore.test/assets/grocery_crud/js/jquery-1.7.1.min.js"></script>
	<script src="http://ummicore.test/assets/grocery_crud/themes/flexiajax/js/cookies.js"></script>
	<script src="http://ummicore.test/assets/grocery_crud/themes/flexiajax/js/flexigrid.js"></script>
	<script src="http://ummicore.test/assets/grocery_crud/themes/flexiajax/js/jquery.form.js"></script>
	<script src="http://ummicore.test/assets/grocery_crud/themes/flexiajax/js/jquery.numeric.js"></script>
<style type='text/css'>
body
{
	font-family: Arial;
	font-size: 14px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
	text-decoration: underline;
}
</style>
</head>
<body>
	<div style='height:20px;'></div>  
    <div>
        <script type="text/javascript">
	var dialog_forms = '';

</script>
<script type='text/javascript'>

    if(typeof(uniques_hash) == 'undefined' || uniques_hash === null)
    {
        var base_url = 'http://ummicore.test/';
        var uniques_hash = {};
        var subjects = {};
        var unic_ids = {};
        var ajax_list_info_urls = {};
    }
    var unic_name = 'grid_';

    subjects[unic_name] = 'Guru_quran';
    unic_ids[unic_name] = '';

    ajax_list_info_urls[unic_name] = 'http://ummicore.test/multigrid/index/ajax_list_info/';

    uniques_hash[unic_name] = '6b4e345c1b5cac1adaedfa77272ecc35';

    var message_alert_delete = "Are you sure that you want to delete this record?";
</script>

	<div style='height:20px;'></div>  
    <div>
        <?php echo $output; ?>
        <?php echo $output2; ?>
        


    </div>
</body>
</html>
