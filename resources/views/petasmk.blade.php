<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Web GIS SMK Lampung (Google Maps)</title>

  <!-- Turf.js untuk geospasial opsional (tidak wajib jika hanya pakai Google Maps) -->
  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzmvxD2iXxm-VCOO_xUQgsmufRyWBElPo&libraries=geometry&callback=initMap" async defer></script>
  <!-- Google Maps API -->
  

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Roboto', sans-serif;
    }
    #map {
      height: 100%;
      width: 100%;
    }
    .controls {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(255,255,255,0.9);
      padding: 15px;
      border-radius: 8px;
      z-index: 999;
      width: 300px;
    }
    .controls select,
    .controls input {
      width: 100%;
      margin-bottom: 10px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    #suggestions {
      background: white;
      border: 1px solid #ccc;
      border-radius: 4px;
      max-height: 200px;
      overflow-y: auto;
      display: none;
    }
    #suggestions div {
      padding: 8px;
      cursor: pointer;
    }
    #suggestions div:hover {
      background: #eee;
    }
    #jumlahSekolah {
      background: #007cbf;
      color: white;
      padding: 10px;
      border-radius: 4px;
      text-align: center;
      font-weight: bold;
      display: none;
      margin-top: 10px;
    }
    .bottom-panel {
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      background: rgba(255,255,255,0.95);
      padding: 15px;
      z-index: 999;
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }
    .bottom-panel button {
      background: #007cbf;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }
    #hasilPrediksi {
      margin-top: 10px;
      font-size: 14px;
      max-height: 200px;
      overflow-y: auto;
      display: none;
      flex: 1;
      margin-left: 20px;
    }
    #hasilPrediksi .close-btn {
      background: #ff0000;
      color: white;
      border: none;
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 3px;
      margin-bottom: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<div class="controls">
  <select id="kabupatenSelect">
    <option value="">-- Semua Kabupaten --</option>
    <option value="Lampung Barat">Lampung Barat</option>
    <option value="Lampung Utara">Lampung Utara</option>
    <option value="Lampung Timur">Lampung Timur</option>
    <option value="Lampung Selatan">Lampung Selatan</option>
    <option value="Lampung Tengah">Lampung Tengah</option>
    <option value="Pringsewu">Pringsewu</option>
    <option value="Pesawaran">Pesawaran</option>
    <option value="Tanggamus">Tanggamus</option>
    <option value="Pesisir Barat">Pesisir Barat</option>
    <option value="Tulang Bawang">Tulang Bawang</option>
    <option value="Tulang Bawang Barat">Tulang Bawang Barat</option>
    <option value="Mesuji">Mesuji</option>
    <option value="Way Kanan">Way Kanan</option>
    <option value="Metro">Metro</option>
    <option value="Bandar Lampung">Bandar Lampung</option>
  </select>

  <input type="text" id="searchBox" placeholder="Cari Nama Sekolah...">
  <div id="suggestions"></div>
  <div id="jumlahSekolah"></div>
</div>

<div id="map"></div>

<div class="bottom-panel">
  <button id="lokasiSayaBtn">Lokasi Saya & Prediksi Zonasi</button>
  <div id="hasilPrediksi"></div>
</div>

<script>
let map;
let sekolahData = null;
let allSekolah = [];
let markers = [];
let prediksiMarkers = [];
let userMarker = null;
let garisPolylines = [];

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -4.8, lng: 104.9 },
    zoom: 8,
    mapTypeId: "satellite"
  });

  // Tampilkan batas kabupaten (GeoJSON)
  map.data.loadGeoJson('/kablam.geojson');
  map.data.setStyle({
    strokeColor: '#ff0000',
    strokeWeight: 2
  });

  // Load GeoJSON sekolah
  fetch('/geojson/sekolahsmk')
    .then(res => res.json())
    .then(data => {
      sekolahData = data;
      allSekolah = data.features.map(f => ({
        nama: f.properties.nama_sekolah,
        coords: {
          lat: f.geometry.coordinates[1],
          lng: f.geometry.coordinates[0]
        },
        properties: f.properties
      }));
      loadSekolahMarkers(data.features);
    });
}

function loadSekolahMarkers(features) {
  markers.forEach(m => m.setMap(null));
  markers = [];

  features.forEach(f => {
    const lat = f.geometry.coordinates[1];
    const lng = f.geometry.coordinates[0];
    const props = f.properties;

    const marker = new google.maps.Marker({
      position: { lat, lng },
      map,
      title: props.nama_sekolah,
      icon: {
        url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
      }
    });

    const infoWindow = new google.maps.InfoWindow({
      content: createInfoWindowContent(lat, lng, props)
    });

    marker.addListener("click", () => {
      infoWindow.open(map, marker);
    });

    markers.push(marker);
  });
}

function createInfoWindowContent(lat, lng, props) {
  return `
    <strong style="font-size:16px; color:#007cbf;">${props.nama_sekolah}</strong><br>
    Desa: ${props.desa}<br>
    Kecamatan: ${props.kecamatan}<br>
    Alamat: ${props.alamat_lengkap}<br>
    Jumlah guru: ${props.jumlah_guru}<br>
    Jumlah siswa: ${props.jumlah_siswa}<br>
    <img src="${props.Foto_Lokal}" width="200" style="margin-top:8px;"><br>
    <button onclick="openStreetView(${lat}, ${lng})" style="margin-top:10px; padding:6px 12px; background:#007cbf; color:white; border:none; border-radius:4px; cursor:pointer;">
      Lihat Street View
    </button>
    <button onclick="openGoogleMaps('${props.Url_Google_maps}')" style="margin-top:10px; margin-left:5px; padding:6px 12px; background:#28a745; color:white; border:none; border-radius:4px; cursor:pointer;">
      Lihat Detail Sekolah
    </button>
  `;
}

document.getElementById("kabupatenSelect").addEventListener("change", e => {
  const selectedKab = e.target.value;
  const jumlahDiv = document.getElementById("jumlahSekolah");

  if (selectedKab === "") {
    loadSekolahMarkers(sekolahData.features);
    jumlahDiv.style.display = "none";
  } else {
    const filtered = sekolahData.features.filter(f => f.properties.kabupaten === selectedKab);
    loadSekolahMarkers(filtered);

    let html = `<strong>${filtered.length}</strong> sekolah SMK di <strong>${selectedKab}</strong><br><br>`;
    if (filtered.length > 0) {
      html += `<div style="max-height:200px; overflow-y:auto;">`;
      filtered.forEach(f => {
        html += `<div style="cursor:pointer; padding:5px; border-bottom:1px solid #ddd;"
          onclick='flyToSekolah(${f.geometry.coordinates[1]}, ${f.geometry.coordinates[0]}, ${JSON.stringify(f.properties)})'>
          ${f.properties.nama_sekolah}
        </div>`;
      });
      html += `</div>`;
    } else {
      html += "Tidak ada sekolah ditemukan.";
    }

    jumlahDiv.innerHTML = html;
    jumlahDiv.style.display = "block";
  }
});

function flyToSekolah(lat, lng, props) {
  map.setCenter({ lat, lng });
  map.setZoom(15);

  const infoWindow = new google.maps.InfoWindow({
    content: createInfoWindowContent(lat, lng, props),
    position: { lat, lng }
  });

  infoWindow.open(map);
}

document.getElementById("searchBox").addEventListener("input", e => {
  const keyword = e.target.value.toLowerCase();
  const suggestionsDiv = document.getElementById("suggestions");
  suggestionsDiv.innerHTML = "";

  if (keyword.length < 2) {
    suggestionsDiv.style.display = "none";
    return;
  }

  const filtered = allSekolah.filter(item =>
    item.nama.toLowerCase().includes(keyword)
  );

  if (filtered.length === 0) {
    suggestionsDiv.style.display = "none";
    return;
  }

  filtered.forEach(item => {
    const div = document.createElement("div");
    div.textContent = item.nama;
    div.addEventListener("click", () => {
      flyToSekolah(item.coords.lat, item.coords.lng, item.properties);
      suggestionsDiv.style.display = "none";
      document.getElementById("searchBox").value = item.nama;
    });
    suggestionsDiv.appendChild(div);
  });

  suggestionsDiv.style.display = "block";
});

document.addEventListener("click", e => {
  if (!document.getElementById("suggestions").contains(e.target) &&
      e.target.id !== "searchBox") {
    document.getElementById("suggestions").style.display = "none";
  }
});

document.getElementById("lokasiSayaBtn").addEventListener("click", () => {
  if (!navigator.geolocation) {
    alert("Browser tidak mendukung geolocation");
    return;
  }

  navigator.geolocation.getCurrentPosition(pos => {
    const lat = pos.coords.latitude;
    const lng = pos.coords.longitude;

    if (userMarker) userMarker.setMap(null);

    userMarker = new google.maps.Marker({
      position: { lat, lng },
      map,
      icon: { url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png" }
    });

    map.setCenter({ lat, lng });
    map.setZoom(14);

    const jarakData = allSekolah.map(sek => {
      const jarak = google.maps.geometry.spherical.computeDistanceBetween(
        new google.maps.LatLng(lat, lng),
        new google.maps.LatLng(sek.coords.lat, sek.coords.lng)
      ) / 1000;
      return { ...sek, jarak };
    }).sort((a,b) => a.jarak - b.jarak);

    const radiusKm = 10;
    const terdekat = jarakData.filter(s => s.jarak <= radiusKm);

    let hasil = "";
    if (terdekat.length === 0) {
      hasil = "<strong>Tidak ada sekolah dalam radius 10 km.</strong>";
    } else {
      hasil = `<button class="close-btn" onclick="hidePrediksi()">Tutup</button>`;
      hasil += `<strong>Sekolah terdekat (≤ ${radiusKm} km):</strong><br>`;
      terdekat.forEach(s => {
        hasil += `• ${s.nama} (${s.jarak.toFixed(2)} km)<br>`;
      });

      garisPolylines.forEach(line => line.setMap(null));
      garisPolylines = [];
      prediksiMarkers.forEach(m => m.setMap(null));
      prediksiMarkers = [];

      terdekat.slice(0, 5).forEach(s => {
        const line = new google.maps.Polyline({
          path: [
            { lat, lng },
            s.coords
          ],
          strokeColor: "#ff9900",
          strokeOpacity: 1.0,
          strokeWeight: 2,
          map
        });
        garisPolylines.push(line);

        const marker = new google.maps.Marker({
          position: s.coords,
          map,
          title: s.nama,
          icon: {
            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
          }
        });

        const infoWindow = new google.maps.InfoWindow({
          content: createInfoWindowContent(s.coords.lat, s.coords.lng, s.properties)
        });

        marker.addListener("click", () => {
          infoWindow.open(map, marker);
        });

        prediksiMarkers.push(marker);
      });
    }

    const hasilDiv = document.getElementById("hasilPrediksi");
    hasilDiv.innerHTML = hasil;
    hasilDiv.style.display = "block";

  }, err => {
    alert("Gagal mendeteksi lokasi: " + err.message);
  });
});

function hidePrediksi() {
  document.getElementById("hasilPrediksi").style.display = "none";
  garisPolylines.forEach(line => line.setMap(null));
  prediksiMarkers.forEach(m => m.setMap(null));
}

function openStreetView(lat, lng) {
  window.open(`https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=${lat},${lng}`, '_blank');
}

function openGoogleMaps(url) {
  if (url && url.trim() !== "") {
    window.open(url, '_blank');
  } else {
    alert("Link Google Maps tidak tersedia untuk sekolah ini.");
  }
}

window.initMap = initMap;
</script>

</body>
</html>
