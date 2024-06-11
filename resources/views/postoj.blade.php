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
        <link rel="stylesheet" href="CSS/style.css" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin="">
        </script>
    </head>
    <body>
        <script>
            $(document).ready(function(){
                $("button.toogle1").click(function() {  // start-stop
                    $(".toogle1").addClass("active").removeClass("inactive");
                    $(".toogle2").addClass("inactive").removeClass("active");
                    $('input[name="end_date"]').prop( "disabled", true);
                    $('input[name="end_date"]').prop("hidden", true);
                    $("#end_date_label").prop("hidden", true);
                });
                $("button.toogle2").click(function() {  // time
                    $(".toogle2").addClass("active").removeClass("inactive");
                    $(".toogle1").addClass("inactive").removeClass("active");
                    $('input[name="end_date"]').prop( "disabled", false);
                    $('input[name="end_date"]').prop("hidden", false);
                    $("#end_date_label").prop("hidden", false);
                });
            });
        </script>
        <br><br><section class="container">
                <aside class="body_form">
                    <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                    <h1>Dodaj postój</h1>
                <form method="post" action="/stop">
                    @csrf
                    <button class="toogle1 inactive" type="button" formnovalidate>start/stop</button> <button class="toogle2 active" type="button" formnovalidate>czasowe</button>
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

                <label id="end_date_label"> Data zakończenia<span class="required">*</span> </label>
                <input type="datetime-local" class="form_input" name="end_date" required="true"><br>

                @if (Session::has('cars'))
                    <label>Wybierz pojazd <span class="required">* </span> </label>
                        <div class ="select">
                        <select test="" name="vehicle_id">
                        @foreach (Session::get('cars') as $key=>$car)
                            <option value="{{Session::get('cars_id')[$key]}}">{{$car}}</option>
                        @endforeach
                        </select>
                                </div>
                    @else
                    <p>Brak pojazdów</p>
                    @endif
                    <br />
                    @if (Session::has('parkings'))
                    <label>Wybierz parking <span class="required">* </span> </label>
                        <div class ="select">
                        <select test="" id="parking" name="parking_id" hidden>
                        @foreach (Session::get('parkings') as $key=>$parking)
                            <option value="{{Session::get('parkings_id')[$key]}}">{{$parking}}</option>
                        @endforeach
                        </select>
                                </div>
                    @else
                    <p>Brak parkingów</p>
                    @endif
                    <div id="map" class="mapForm"></div>
                </aside>
                <hr><input type="submit" class="button" value="Start">
               <input type="reset" class="button" value="Wyczyść"></form>
                </section>

                {{view('components.map-select');}}
    </body>
</html>
