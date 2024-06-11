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
        <link rel="stylesheet" href="CSS/forms.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>
    <body>
        <br><br><section class="container">
                <aside class="body_form">
                    <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <h1>Dodaj pojazd</h1>
                <form method="post" action="/vehicle">
                    @csrf
                <hr>
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

                <input type="text" class="form_input" name="registration_plate" placeholder="Numer rejestracyjny" title="Podaj max. 8 znaków" maxlength="8" required="true"><br>
                <input type="text" class="form_input" name="brand" placeholder="Marka" title="Podaj max. 20 znaków" maxlength="20" required="true"><br>
                <input type="text" class="form_input" name="model" placeholder="Model" title="Podaj max. 20 znaków" maxlength="20" required="true"><br>
                </aside>
                <hr><input type="submit" class="button" value="Dodaj">
               <input type="reset" class="button" value="Wyczyść"></form>
                </section>
                
    </body>
</html>
