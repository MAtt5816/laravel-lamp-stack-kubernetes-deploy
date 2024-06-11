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
        <link rel="stylesheet" href="CSS/forms.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
      <?php
        if(isset($_POST['user'])){
    $user = $_POST['user'];
    $user1 = "kierowca";
    $user2 = "operator";
    $user3 = "kontroler";
    if($user == $user1)
    header("Location: user/signup_k.php");
    if($user == $user2)
        header("Location: user/signup_o.php");
    if($user == $user3)
        header("Location: user/signup_i.php");
}
        ?>
    </head>
    <body>
        
        <br><br><section class="container">
            <div class="reurn_block"><a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a></div>
            <nav class="header">
                <h2>Tworzenie konta</h2>
                <p> Wybierz typ użytkownika</p></nav>
        <br/><form method="post"><div class="user_type">
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

                <input type="radio" id="register_driver" name="user" value="kierowca" checked><label for="register_driver">
        <div class="title">Kierowca</div>
        <div class="description">Jestem kierowcą</div></label><br/>
        <input type="radio" id="register_operator" name="user" value="operator"><label for="register_operator">
        <div class="title">Operator</div>
        <div class="description">Posiadam parking dla samochodów</div></label><br>
        <input type="radio" id="register_inspector" name="user" value="kontroler"><label for="register_inspector">
        <div class="title">Kontroler</div>
        <div class="description">Posiadam kod podany przez operatora</div></label>
        <br/><input type="submit" value="Kontynuuj"/>
            </div></form></section>
        
    </body>
</html>
