<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js "></script>
</head>
<body>
    
<a href="/test/respons/1" class="reff">1</a>
<a href="/test/respons/2" class="reff">2</a>
<a href="/test/respons/3" class="reff">3</a>
<a href="/test/respons/4" class="reff">4</a>

<div id="response"></div>

<script>
$('a').click(function(event) { 
    event.preventDefault(); 
    $.ajax({
        url: $(this).attr('href'),
        success: function(response) {
            alert(response);
            
        }
    });
    // return false; // for good measure
});


</script>


</body>
</html>