<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width" />
        <link rel="shortcut icon" href="img/logo.png" />
        <title>Our-parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
        <link rel="stylesheet" href="CSS/forms.css"/>
        <link rel="stylesheet" href="CSS/details.css"/>
        <link rel="stylesheet" href="CSS/style.css" type="text/css">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css" integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14=" crossorigin=""/>
        <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js" integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg=" crossorigin="">
        </script>
    </head>
    <body>

        <br><br><section class="container">
            <aside class="body_form">
                <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                <h1>Moje parkingi</h1>
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

                @if (Session::has('parkings') && !empty(Session::get('parkings')))
                        <table class="table">
                    <thead>
                        <tr>
                            <th>
                                Nazwa
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach (Session::get('parkings') as $key=>$parking)
                    <tr>
                        <td id="1">
                            {{$parking}}</td>         
                        <td>
                            <a href="/edit_parking/{{Session::get('parkings_id')[$key]}}"><i class="fa fa-edit"></i> Edytuj</a> |
                            <a href="/show_parking/{{Session::get('parkings_id')[$key]}}"><i class="fa fa-sticky-note-o"></i> Szczegóły</a> |
                            <a class="delete" href="/delete_parking/{{Session::get('parkings_id')[$key]}}"><i class="fa fa-trash"></i> Usuń</a>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                {{view('components.ru_sure');}}
                @else
                <p>Brak parkingów</p>
                @endif  
                <div id="map" class="mapForm"></div>
            </aside>
        </section>

        {{view('components.map-static-one-operator');}}

    </body>
</html>
