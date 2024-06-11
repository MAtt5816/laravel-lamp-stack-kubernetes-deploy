<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        
        <link rel="shortcut icon" href="img/logo.png" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width" />
        <title>Our-parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
        <link rel="stylesheet" href="CSS/settings.css"/>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>
    <body>
        <section class="settings">
            <section class="settings_panel_left">
                <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                <div class="user_logo"><i class="fa fa-user"></i></div>
                <span class="user">{{Session::get('user')->login}}</span>
                <hr>
                    <a href="/settings"><input id='option' name='option' type="button" value="Konto"/></a>
                    <hr>
                    <a href="/change_password"><input id='option' name='option' type="button" value="Hasło"/></a>
                <hr>
                <a href="/delete_account"><input id='option' name='option' type="button" value="Usuń konto"/></a>
                <hr>
            </section>
            <section class="settings_panel_right">
                <nav class="header"><h1>Ustawienia</h1>
                    <hr></nav>
                @switch($option)
                    @case('settings')
                        {{view('components.konto')}}
                    @break
                    @case('change_password')
                        {{view('components.zmiana_hasla')}}
                    @break
                    @case('delete_account')
                        {{view('components.deleteaccount')}}
                    @break
                @endswitch
            </section>
        </section>
    </body>
</html>
