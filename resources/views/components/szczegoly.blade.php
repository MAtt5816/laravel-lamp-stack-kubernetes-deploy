@if (Session::has('details'))
<section class="alert">
    <h3>Szczegóły</h3><a class="close" href ="{{Session::get('details')}}s"><i class="fa fa-close"></i></a>
    <hr>
        @switch(Session::get('details'))
            @case('vehicle')
                <h4>Numer rejestracyjny: </h4><div>{{Session::get('vehicle')->registration_plate}}</div>
                <h4>Marka: </h4><div>{{Session::get('vehicle')->brand}}</div>
                <h4>Model: </h4><div>{{Session::get('vehicle')->model}}</div><br>
                @break
            @case('stop')
                <h4>Data rozpoczęcia: </h4><div>{{\Carbon\Carbon::parse(Session::get('stop')->start_date)->timezone('Europe/Warsaw')}}</div>
                @if(Session::get('stop')->end_date != null)
                    <h4>Data zakończenia: </h4><div>{{\Carbon\Carbon::parse(Session::get('stop')->end_date)->timezone('Europe/Warsaw')}}</div>
                @endif
                <h4>Parking: </h4><div>{{Session::get('parking')->name}}</div>
                <h4>Pojazd: </h4><div>{{Session::get('vehicle')->registration_plate}}</div><br>
            @break
            @case('reservation')
                <h4>Data rozpoczęcia: </h4><div>{{\Carbon\Carbon::parse(Session::get('reservation')->start_date)->timezone('Europe/Warsaw')}}</div>
                <h4>Data zakończenia: </h4><div>{{\Carbon\Carbon::parse(Session::get('reservation')->end_date)->timezone('Europe/Warsaw')}}</div>
                <h4>Parking: </h4><div>{{Session::get('parking')->name}}</div>
                <h4>Pojazd: </h4><div>{{Session::get('vehicle')->registration_plate}}</div><br>
                @break
            @case('parking')
                {{-- <h4>Nazwa: </h4><div>{{Session::get('parking')->name}}</div> --}}
                <h4>Cena: </h4><div>{{Session::get('parking')->price}}</div>
                <h4>Ilość miejsc: </h4><div>{{Session::get('parking')->parking_spaces}}</div>
                <h4>Godziny otwarcia: </h4><div>{{Session::get('parking')->opening_hours}}</div>
                <h4>Dodatkowe usługi: </h4><div>{{Session::get('parking')->additional_services}}</div>
                <h4>Udogodnienia: </h4><div>{{Session::get('parking')->facilities}}</div>
            @break
            @case('inspector')
                <h4>Imię: </h4><div>{{Session::get('inspector')->name}}</div>
                <h4>Nazwisko: </h4><div>{{Session::get('inspector')->surname}}</div>
                <h4>Kod operatora: </h4><div>{{Session::get('inspector')->operator_code}}</div>
            @break
        @endswitch    
</section>
@endif
