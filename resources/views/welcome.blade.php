<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <link rel="shortcut icon" href="img/logo.png" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Our parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin=""/>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <link rel="stylesheet" href="CSS/style.css" type="text/css">
        <link rel="stylesheet" href="CSS/details.css" type="text/css"/>
        <link rel="stylesheet" href="CSS/header.css" type="text/css">
        <link rel="stylesheet" href="CSS/footer.css" type="text/css">
        <!-- Make sure you put this AFTER Leaflet's CSS -->
        <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin="">
        </script>
    </head>
    
    <body>
        @if(Session::has('widget'))
        <iframe srcdoc="{{Session::get('widget')}}"></iframe>
        @endif
       
            {{view('components.header');}}

            @if (Session::has('token'))
            {{view('components.main');}}
                @if (Session::get('user')->user_type == 'operator' || Session::get('user')->user_type == 'inspector')
                {{view('components.map-static-one-operator');}}
                @else
                {{view('components.map-static');}}
                @endif
            @else
            {{view('components.home');}}
            {{view('components.map-static');}}
            @endif
            
           {{view('components.footer');}}
            
    </body>
</html>
