<style>
    #map {
        height: 400px;
    }
</style>
<div id="map"></div>
<script>
    var lokasi = "{{ $presensi->lokasi_in }}";
    var nama_karyawan = "{{ $presensi->nama_lengkap }}";
    var lok = lokasi.split(",");
    var latitude = lok[0];
    var longitude = lok[1];
    var map = L.map('map').setView([latitude, longitude], 16);
    L.tileLayer('http://{s}.google.com/vt/lyrs=p&x={x}&y={y}&z={z}', {
        maxZoom: 20,
        subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([latitude, longitude], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 50
    }).addTo(map);
    var popup = L.popup()
        .setLatLng([latitude, longitude])
        .setContent(nama_karyawan)
        .openOn(map);
</script>
