<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width" />
        <title>Our-parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
        <link rel="stylesheet" href="../CSS/forms.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>
    <body>
        <br><br><section class="container">
                <aside class="body_form">
                    <a class="return" href="/signup"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <h1>Stwórz konto kontrolera</h1>
                <form method="post" action="/signup_inspector">
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

                    <h4>Dane konta</h4><hr>
                    <input type="hidden" name="user_type" value="inspector">
                <input type="text" class="form_input" name="login" placeholder="Login" title="Użyj max. 15 liter, cyfr lub znaków ._%+-" pattern="[A-Za-ząćęłńóśźżŁŻŚŹĆÓ0-9._%+-]{1,15}$" required="true"><br>
                <input type="password" class="form_input" name="password" placeholder="Hasło" title="Podaj 8-50 znaków" pattern=".{8,50}" required="true">
                <input type="password" class="form_input" name="password_confirmation" placeholder="Powtórz hasło" required="true"><br>
                <h4>Kod</h4><hr>
                <input type="text" class="form_input" name="operator_code" placeholder="Kod_operatora" required="true">
                <br> <hr>
                </aside>
                <input type="submit" class="button" value="Dodaj">
               <input type="reset" class="button" value="Wyczyść"></form>
                </section>
    </body>
</html>
