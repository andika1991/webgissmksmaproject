@extends('layouts.app')

@section('content')
<style>
  .map-image-container {
    width: 100%;
    max-width: 800px;
    margin: 50px auto;
    text-align: center;
  }

  .map-image {
    width: 100%;
    height: auto;
    opacity: 0;
    animation: fadeIn 2s forwards;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.3);
    cursor: pointer;
    transition: transform 0.3s ease;
  }
  .map-image:hover {
    transform: scale(1.02);
  }

  @keyframes fadeIn {
    to {
      opacity: 1;
    }
  }

  .caption {
    margin-top: 15px;
    font-size: 1.2rem;
    color: #555;
    font-weight: 500;
  }

  /* Responsive full-width on mobile */
  @media (max-width: 600px) {
    .map-image-container {
      margin: 20px auto;
      max-width: 100%;
      padding: 0 10px;
    }
  }

  /* Popup modal fullscreen */
  .modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.8);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .modal-overlay.active {
    display: flex;
  }

  .modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
  }

  .modal-content img {
    width: 100%;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 0 25px rgba(0,0,0,0.7);
  }

  .close-btn {
    position: absolute;
    top: -15px;
    right: -15px;
    background: #d62828;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 18px;
    cursor: pointer;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
  }
.kabupaten-maps-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 30px;
  }

  .kabupaten-card {
    background: #fff;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
    cursor: pointer;
  }

  .kabupaten-card:hover {
    transform: scale(1.03);
  }

  .kabupaten-card img {
    width: 100%;
    border-radius: 6px;
    height: auto;
  }

  .kabupaten-card p {
    margin-top: 10px;
    font-weight: 600;
    color: #0d253f;
  }
</style>

<div class="map-image-container">
<br><br><br><br>
  <div class="caption">
    Peta Persebaran SMA di Provinsi Lampung
  </div>
    <img 
    id="mapImage"
    src="{{ asset('sma/gradualsmalampung.jpeg') }}" 
    alt="Peta Persebaran SMA di Provinsi Lampung" 
    class="map-image"
  />
</div>


<div class="kabupaten-section">
  <h2>Peta Persebaran SMA Per Kabupaten/Kota</h2>

  <div class="kabupaten-maps-grid">
    <div class="kabupaten-card">
      <img src="{{ asset('sma/peta_kabupaten/lampung_selatan.jpeg') }}" alt="Lampung Selatan">
      <p>Lampung Selatan</p>
    </div>
    <div class="kabupaten-card">
      <img src="{{ asset('sma/peta_kabupaten/lampung_tengah.jpeg') }}" alt="Lampung Tengah">
      <p>Lampung Tengah</p>
    </div>
    <div class="kabupaten-card">
      <img src="{{ asset('sma/smabandarlampung.jpeg') }}" alt="Bandar Lampung">
      <p>Bandar Lampung</p>
    </div>
    <div class="kabupaten-card">
      <img src="{{ asset('sma/peta_kabupaten/tanggamus.jpeg') }}" alt="Tanggamus">
      <p>Tanggamus</p>
    </div>
    <div class="kabupaten-card">
      <img src="{{ asset('sma/peta_kabupaten/lampung_timur.jpeg') }}" alt="Lampung Timur">
      <p>Lampung Timur</p>
    </div>
  </div>
</div>

<script>
  const mapImage = document.getElementById('mapImage');
  const modalOverlay = document.getElementById('modalOverlay');
  const closeModal = document.getElementById('closeModal');

  mapImage.addEventListener('click', () => {
    modalOverlay.classList.add('active');
  });

  closeModal.addEventListener('click', () => {
    modalOverlay.classList.remove('active');
  });

  // Tutup modal jika klik di luar gambar
  modalOverlay.addEventListener('click', (e) => {
    if(e.target === modalOverlay) {
      modalOverlay.classList.remove('active');
    }
  });


  mediumZoom('.zoomable-image', {
    background: 'rgba(0,0,0,0.8)',
    scrollOffset: 40,
    margin: 24,
  })

</script>
@endsection
