<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Web GIS SMA/SMK Lampung</title>

  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6.5.0/turf.min.js"></script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBzmvxD2iXxm-VCOO_xUQgsmufRyWBElPo&libraries=geometry&callback=initMap" async defer></script>

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <style>
    /* General Styles */
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: 'Roboto', sans-serif;
    }
    #map {
      height: 100%;
      width: 100%;
    }
    button {
      cursor: pointer;
    }

    /* Top-Left Controls */
    .controls {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(255,255,255,0.9);
      padding: 15px;
      border-radius: 8px;
      z-index: 999;
      width: 300px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    }
    .controls select,
    .controls input {
      width: 100%;
      box-sizing: border-box;
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
      background: white;
      color: #333;
      padding: 15px;
      border-radius: 4px;
      text-align: left;
      font-weight: normal;
      display: none;
      margin-top: 10px;
      border: 1px solid #ccc;
    }
    
    /* Top-Right Table Panel */
    #tabelSekolah {
      position: absolute;
      top: 10px;
      right: 10px;
      width: 550px; /* Adjusted width */
      max-height: 80%;
      overflow: auto;
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.3);
      z-index: 999;
      display: none;
    }
    #tabelSekolah h3 {
      margin-top: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    #tabelSekolah .close-btn-table {
      background: #e74c3c;
      color: white;
      border: none;
      padding: 4px 10px;
      font-size: 12px;
      border-radius: 3px;
    }
    #sekolahTable {
      width: 100%;
      border-collapse: collapse;
      font-size: 12px;
    }
    #sekolahTable th, #sekolahTable td {
      padding: 6px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }
    #sekolahTable thead tr {
      background: #007cbf;
      color: white;
    }

    /* Bottom Panel */
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
      box-sizing: border-box;
    }
    .bottom-panel button {
      background: #007cbf;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 4px;
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
    }

    /* StreetView Panel */
    #streetViewPanel {
      height: 50%;
      width: 100%;
      position: absolute;
      bottom: 0;
      z-index: 1000;
      display: none;
    }

    /* RESPONSIVE STYLES */
    @media (max-width: 768px) {
      .controls, #tabelSekolah {
        width: auto;
        left: 10px;
        right: 10px;
      }
      #tabelSekolah {
        max-height: 75%;
      }
      #sekolahTable thead {
        display: none;
      }
      #sekolahTable, #sekolahTable tbody, #sekolahTable tr, #sekolahTable td {
        display: block;
        width: 100%;
        box-sizing: border-box;
      }
      #sekolahTable tr {
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 5px;
      }
      #sekolahTable td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border-bottom: none;
      }
      #sekolahTable td::before {
        content: attr(data-label);
        position: absolute;
        left: 10px;
        width: 45%;
        padding-right: 10px;
        text-align: left;
        font-weight: bold;
        color: #333;
      }
    }
  </style>
</head>
<body>

  <div class="controls">
    <select id="kabupatenSelect">
      <option value="">-- Semua Kabupaten --</option>
      <option value="Lampung Barat">Lampung Barat</option>
      <option value="Lampung Utara">Lampung Utara</option>
      <option value="KAB. LAMPUNG TIMUR">Lampung Timur</option>
      <option value="Kab. Lampung Selatan">Lampung Selatan</option>
      <option value="Lampung Tengah">Lampung Tengah</option>
      <option value="Pringsewu">Pringsewu</option>
      <option value="Pesawaran">Pesawaran</option>
      <option value="Tanggamus">Tanggamus</option>
      <option value="Pesisir Barat">Pesisir Barat</option>
      <option value="Tulang Bawang">Tulang Bawang</option>
      <option value="Tulang Bawang Barat">Tulang Bawang Barat</option>
      <option value="Mesuji">Mesuji</option>
      <option value="Way Kanan">Way Kanan</option>
      <option value="Kota Metro">Metro</option>
      <option value="KOTA BANDAR LAMPUNG">Bandar Lampung</option>
    </select>

    <input type="text" id="searchBox" placeholder="Cari Nama Sekolah...">
    <div id="suggestions"></div>
    <div id="jumlahSekolah"></div>
  </div>

  <div id="map"></div>
  <div id="streetViewPanel"></div>

  <div class="bottom-panel">
    <button id="lokasiSayaBtn">Lokasi Saya & Prediksi Zonasi</button>
    <div id="hasilPrediksi"></div>
  </div>

  <div id="tabelSekolah">
    <h3>
      Daftar Sekolah
      <button class="close-btn-table" onclick="document.getElementById('tabelSekolah').style.display='none'">Tutup</button>
    </h3>
    <table id="sekolahTable">
      <thead></thead>
      <tbody></tbody>
    </table>
  </div>


  <script>
    let map;
    let sekolahData = null;
    let allSekolah = [];
    let markers = [];
    let prediksiMarkers = [];
    let userMarker = null;
    let garisPolylines = [];
    let panorama = null;
    let currentInfoWindow = null;

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: -4.8, lng: 104.9 },
        zoom: 8,
        mapTypeId: "satellite"
      });

      map.data.loadGeoJson('/kablam.geojson');
      map.data.setStyle({
        strokeColor: '#ff0000',
        strokeWeight: 2
      });

      fetch('/geojson/sekolah')
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

        marker.addListener("click", () => {
          flyToSekolah(lat, lng, props);
        });

        markers.push(marker);
      });
    }

    function createInfoWindowContent(lat, lng, props) {
      const biayaPerSiswa = 1500000;
      const jumlahSiswa = parseInt(props.jumlah_siswa) || 0;
      const totalBiaya = biayaPerSiswa * jumlahSiswa;

      return `
        <div style="font-family: Arial, sans-serif; font-size: 13px; max-width: 260px;">
          <div style="font-weight: bold; color: #007cbf; margin-bottom: 5px;">
            ${props.nama_sekolah}
          </div>
          <div style="margin-bottom: 6px;">
            <strong>Alamat:</strong><br>
            ${props.alamat_lengkap || 'Tidak ada data'}
          </div>
          <div style="margin-bottom: 6px;">
            👨‍🏫 <strong>Guru:</strong> ${props.jumlah_guru || 'N/A'} <br>
            👨‍🎓 <strong>Siswa:</strong> ${jumlahSiswa}
          </div>
          <div style="margin-bottom: 6px;">
            💰 <strong>Biaya perSiswa:</strong> Rp ${biayaPerSiswa.toLocaleString('id-ID')}<br>
            💸 <strong>Total Operasional:</strong> Rp ${totalBiaya.toLocaleString('id-ID')}
          </div>
          <img src="${props.foto_lokal}" alt="Foto Sekolah"
            style="width: 100%; height: 80px; object-fit: cover; border-radius: 4px; margin-bottom: 6px;"
            onerror="this.style.display='none'" />
          <div style="display: flex; gap: 6px;">
            <button onclick="openStreetView(${lat}, ${lng})"
              style="flex:1; background:#007cbf; color:white; border:none; padding:6px; font-size:12px; border-radius:4px;">
              Street View
            </button>
            <button onclick="openGoogleMaps('${props.url_Maps}')"
              style="flex:1; background:#28a745; color:white; border:none; padding:6px; font-size:12px; border-radius:4px;">
              Detail
            </button>
          </div>
        </div>
      `;
    }

    document.getElementById("kabupatenSelect").addEventListener("change", e => {
      const selectedKab = e.target.value;
      const jumlahDiv = document.getElementById("jumlahSekolah");

      if (selectedKab === "") {
        loadSekolahMarkers(sekolahData.features);
        jumlahDiv.style.display = "none";
        document.getElementById("tabelSekolah").style.display = "none";
      } else {
        const filtered = sekolahData.features.filter(f => f.properties.kabupaten === selectedKab);
        loadSekolahMarkers(filtered);

        let html = `
          <div style="text-align:right;">
            <button onclick="document.getElementById('jumlahSekolah').style.display='none';"
              style="background:#e74c3c; color:#fff; border:none; padding:5px 10px; cursor:pointer; border-radius:3px;">
              Tutup
            </button>
          </div>
          <strong>${filtered.length}</strong> sekolah di <strong>${selectedKab}</strong><br><br>`;

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
        
        renderTabelSekolah(filtered);
      }
    });

    function flyToSekolah(lat, lng, props) {
      map.setCenter({ lat, lng });
      map.setZoom(15);
      
      document.getElementById('jumlahSekolah').style.display = 'none';

      if (currentInfoWindow) {
        currentInfoWindow.close();
      }

      currentInfoWindow = new google.maps.InfoWindow({
        content: createInfoWindowContent(lat, lng, props),
        position: { lat, lng }
      });
      currentInfoWindow.open(map);
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
          terdekat.slice(0, 5).forEach(s => {
            const line = new google.maps.Polyline({
              path: [{ lat, lng }, s.coords],
              strokeColor: "#ff9900",
              strokeOpacity: 1.0,
              strokeWeight: 2,
              map
            });
            garisPolylines.push(line);
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
      if (userMarker) userMarker.setMap(null);
      garisPolylines.forEach(line => line.setMap(null));
    }

    // *** FUNGSI STREETVIEW DIPERBAIKI ***
    function openStreetView(lat, lng) {
      const streetViewDiv = document.getElementById("streetViewPanel");
      streetViewDiv.style.display = "block";
      
      streetViewDiv.innerHTML = `<button onclick="closeStreetView()" style="position:absolute;top:5px;right:5px;z-index:1;background:red;color:white;border:none;padding:5px 10px;">Tutup</button>`;
      
      const panoramaDiv = document.createElement('div');
      panoramaDiv.style.width = "100%";
      panoramaDiv.style.height = "100%";
      streetViewDiv.appendChild(panoramaDiv);

      panorama = new google.maps.StreetViewPanorama(panoramaDiv, {
          position: { lat, lng },
          pov: { heading: 34, pitch: 10 },
          zoom: 1,
          addressControl: false,
          linksControl: true,
          panControl: true,
          enableCloseButton: false
      });
      map.setStreetView(panorama);
    }

    function closeStreetView() {
      if (panorama) {
        panorama.setVisible(false);
      }
      document.getElementById("streetViewPanel").style.display = "none";
      document.getElementById("streetViewPanel").innerHTML = "";
    }

    function openGoogleMaps(url) {
      if (url && url.trim() !== "" && url.startsWith("http")) {
        window.open(url, '_blank');
      } else {
        alert("Link Google Maps tidak tersedia untuk sekolah ini.");
      }
    }

    function formatRupiahSingkat(nilai) {
      if (nilai >= 1000000000) {
        return `Rp ${(nilai / 1000000000).toFixed(1)} M`;
      }
      if (nilai >= 1000000) {
        return `Rp ${(nilai / 1000000).toFixed(1)} jt`;
      }
      if (nilai >= 1000) {
        return `Rp ${(nilai / 1000).toFixed(0)} rb`;
      }
      return `Rp ${nilai}`;
    }

    // *** FUNGSI RENDER TABEL DIPERBAIKI ***
    function renderTabelSekolah(sekolahArray) {
      const tabelDiv = document.getElementById("tabelSekolah");
      const tbody = tabelDiv.querySelector("tbody");
      const thead = tabelDiv.querySelector("thead");

      // Bug diperbaiki: Hanya ada satu baris header.
      thead.innerHTML = `
        <tr>
          <th>Nama</th>
          <th>Desa</th>
          <th>Kecamatan</th>
          <th>Guru</th>
          <th>Siswa</th>
          <th>Biaya/Siswa</th>
          <th>Total Tahunan</th>
        </tr>
      `;

      tbody.innerHTML = "";
      const biayaPerSiswa = 1500000;

      if (sekolahArray.length === 0) {
        tabelDiv.style.display = "none";
        return;
      }

      sekolahArray.forEach(f => {
        const siswa = parseInt(f.properties.jumlah_siswa) || 0;
        const totalBiaya = biayaPerSiswa * siswa;
        const tr = document.createElement("tr");

        // Perubahan Kunci: Menambahkan atribut data-label untuk CSS responsif
        tr.innerHTML = `
          <td data-label="Nama">${f.properties.nama_sekolah}</td>
          <td data-label="Desa">${f.properties.desa || '-'}</td>
          <td data-label="Kecamatan">${f.properties.kecamatan || '-'}</td>
          <td data-label="Guru">${f.properties.jumlah_guru || '0'}</td>
          <td data-label="Siswa">${siswa}</td>
          <td data-label="Biaya">${formatRupiahSingkat(biayaPerSiswa)}</td>
          <td data-label="Total">${formatRupiahSingkat(totalBiaya)}</td>
        `;
        tbody.appendChild(tr);
      });
      tabelDiv.style.display = "block";
    }

    window.initMap = initMap;
  </script>
</body>
</html>