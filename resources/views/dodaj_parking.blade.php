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
        <link rel="stylesheet" href="CSS/style.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin="">
        </script>
    </head>
    <body>
        <br><br><section class="container">
                <aside class="body_form">
                   <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a> 
                    <h1>Dodaj parking</h1>
                <form method="post" action="/add_parking">
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

                <input type="text" class="form_input" name="name" placeholder="Nazwa" title="Podaj max. 30 znaków" maxlength="30" required="true"><br>
                <input type="number" class="form_input" name="price" placeholder="Cena" min="0" step="0.01" required="true"><br>
                <input type="number" class="form_input" name="parking_spaces" placeholder="Ilość miejsc" min="1" step="1" required="true"><br>
                <input type="hidden" class="form_input" id="location" name="location" placeholder="Lokalizacja" value="" required="true" >
                <input type="text" class="form_input" name="opening_hours" placeholder="Godziny otwarcia" title="Podaj max. 20 znaków" maxlength="20" required="true"><br>
                <input type="text" class="form_input" name="additional_services" placeholder="Dodatkowe usługi" title="Podaj max. 40 znaków" maxlength="40" required="true"><br>
                <input type="text" class="form_input" name="facilities" placeholder="Udogodnienia" title="Podaj max. 40 znaków" maxlength="40" required="true"><br>
                <div id="map" class="mapForm"></div>
                </aside>
                <hr><input type="submit" class="button" value="Dodaj">
               <input type="reset" class="button" value="Wyczyść"></form>
                </section>

                {{view('components.map-one-pin-select');}}
    </body>
</html>
