<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="img/logo.png" />
        <meta name="viewport" content="width=device-width" />
        <title>Our-parking -rezerwuj miejsca parkingowe, zgłoś parking</title>
    <link rel="stylesheet" href="CSS/forms.css"/>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
    <body>

        <br><br><section class="container">
            <aside class="body_form">
                <a class="return" href="/"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
            <h1>Postoje</h1>
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

            @if (Session::has('stops') && !empty(Session::get('stops')))
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                Data
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                <tbody>
                @foreach (Session::get('stops') as $key=>$stop)
                    <tr>
                        <td id="1">
                            {{\Carbon\Carbon::parse($stop)->timezone('Europe/Warsaw')}}</td>         
                        <td>
                            <a href="/show_stop/{{Session::get('stops_id')[$key]}}"><i class="fa fa-sticky-note-o"></i> Szczegóły</a> |
                            <a href="/info_stop/{{Session::get('stops_id')[$key]}}">
                            @if(Session::get('end_date')[$key] == null)
                                <i class="fa fa-clock-o"></i> Stop &nbsp;&nbsp;  
                            @else
                                <i class="fa fa-car"></i> Postój
                            @endif
                            </a>
                        </td>
                    </tr>
                @endforeach
                    </tbody>
                </table>  
                @if (Session::has('stop') && !Session::has('mode'))
                    {{view('components.szczegoly');}}
                @endif
                {{view('components.ru_sure');}}
                @if (Session::has('mode'))
                    {{view('components.infopostoj');}}
                @endif
            @else
                <p>Brak postojów</p>
            @endif

            </aside>
        </section>
    </body>
    </body>
</html>
