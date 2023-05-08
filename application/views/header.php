<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--[if IE]><link rel="shortcut icon" href="../../public/images/favicon.ico"><![endif]-->
    <link rel="apple-touch-icon-precomposed" href="../../public/images/apple-touch-icon-precomposed.png">
    <link rel="icon" href="../../public/images/favicon.png">

    <title><?php echo $page_title; ?> - VyvaSync</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Lato:400,300,700,900,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href="../../public/css/theme.min.css" rel="stylesheet">
    <link href="../../public/css/style.css" rel="stylesheet">
    <link href="../../public/css/bootstrap-tour.min.css" rel="stylesheet">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../public/js/functions.js"></script>
 
      
    <script type="text/javascript" src="../../public/js/bootstrap-tour.js"></script>
    <script type="text/javascript" src="../../public/js/bootstrap-tour.min.js"></script>
    <script type="text/javascript">checkSession('<?php echo $page_title; ?>')</script>
    <script type="text/javascript">getTotalNotifications()</script>

    <script type="text/javascript">
        $(document).keypress(function(e){
            if(e.which==13) {
                $('#btn-enter-primary').trigger('click');
            }   
        });
    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-66449236-1', 'auto');
        ga('send', 'pageview');
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#project_start_date").datepicker();
            $("#project_end_date").datepicker();
            $("#scene_date").datepicker();
        });
    </script>

    <script type="text/javascript">
        $(function(){
            $("[data-toggle='tooltip']").tooltip();
        });
    </script>

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    </head>
    <body>
        <input type="hidden" id="tourcheck" />