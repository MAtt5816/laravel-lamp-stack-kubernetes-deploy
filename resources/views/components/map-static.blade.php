@if (Session::has('parking'))
    {{view('components.szczegoly');}}
@endif
<script>
    var map = L.map('map').setView([51.2482, 22.5703], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    @if(Session::has('locations'))
    @foreach(Session::get('locations') as $key=>$location)
        var marker = L.marker([{{$location}}]).addTo(map);
        marker.bindPopup("<b>{{Session::get('parkings')[$key]}}</b><hr>Miejsc ogółem: <b>{{Session::get('total')[$key]}}</b><br>"+
        "Aktualnie wolnych miejsc: <b>{{Session::get('free')[$key]}}</b><br><br><a href='/show_parking/{{Session::get('parkings_id')[$key]}}'>{{Session::has('token')?'Szczegóły':''}}</a>");
    @endforeach
    @endif
</script>