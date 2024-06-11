<script>
    var map = L.map('map').setView([51.2482, 22.5703], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    @if(Session::has('parking'))
    var marker = L.marker([{{Session::get('parking')->location}}]).addTo(map);;
    @else
    var marker = null;
    @endif
    map.on('click', function (e) {
        if (marker !== null) {
            map.removeLayer(marker);
        }
        console.log(e.latlng.lat);
        document.getElementById("location").setAttribute('value', e.latlng.lat+","+e.latlng.lng);
        marker = L.marker(e.latlng).addTo(map);
    });
</script>