<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html>
    <head>
        <link rel="shortcut icon" href="img/logo.png" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width" />
        <title>Our-parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
        <link rel="stylesheet" href="CSS/forms.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>
    <body>
        <?php
        ?>
        <br><br><br><section class="container">
                <aside class="left-panel">
                 <div class="reurn_block"><a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a></div>   
                <h1>LOGOWANIE</h1>
                <form method="post" action="/login">
                @csrf

                @if ($errors->any())
                <div class="alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('success'))
            <div class="alert-success">
                @if(is_array(session('success')))
                    <ul>
                        @foreach (session('success') as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @else
                    {{ session('success') }}
                @endif
            </div>
            @endif

                <!-- <input type="email" class="form_input" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"><br/> -->
                <input type="login" class="form_input" name="login" placeholder="Login" title="Użyj max. 15 liter, cyfr lub znaków ._%+-" pattern="[A-Za-z0-9._%+-ąćęłńóśźżŁŻŚŹĆÓ]{1,15}$" required><br/>
                <input type="password" class="form_input" name="password" placeholder="Hasło" title="Podaj 8-50 znaków" pattern=".{8,50}" required><br/>
                <input type="submit" value="Login" class="button"></form></aside>
                <aside class="right-panel"><div class="logo"><img src="img/logo.png" alt="parking logo"/>
                <p>Ours-parking.com</p></div><br/><br/>
                <nav class="panel_footer">Nie masz jeszcze konta ? <a href="/signup">Zarejestruj się</a></nav</aside>
                </section>

        
    </body>
</html>
