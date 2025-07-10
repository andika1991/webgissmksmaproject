  @extends('layouts.app')
  @section('content')
  <section class="hero">
    <div class="hero-content">
      <h1>Selamat Datang di Web GIS Pendidikan SMA/SMK Lampung</h1>
      <p>Jelajahi data geospasial sekolah SMA/SMK di Provinsi Lampung dalam peta interaktif.</p>
      <a href="/peta" class="btn">Lihat Peta SMA</a>
      <br><br>
         <a href="/petasmk" class="btnsmk">Lihat Peta SMK</a>
    </div>
  </section>

  <section class="stats-section">
    <h2>Jumlah Sekolah di Lampung <br> (Data Dapodik Kemdikbud)</h2>
    <div class="chart-container">
      <canvas id="schoolChart"></canvas>
    </div>
  </section>

  <script>
    const hamburger = document.getElementById('hamburger');
    const nav = document.getElementById('nav');

    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('active');
      nav.classList.toggle('active');
    });

    // Simple bar chart using Canvas
    const canvas = document.getElementById('schoolChart');
    const ctx = canvas.getContext('2d');

    const data = [
      { label: 'SMA', count: 241, color: '#00b4d8' },
      { label: 'SMK', count: 112, color: '#0077b6' }
    ];

    const maxCount = Math.max(...data.map(item => item.count));
    const barWidth = 30;
    const barGap = 50;
    const chartHeight = 180;
    const startX = 60;
    const startY = 220;

    canvas.width = 350;
    canvas.height = 250;

    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.font = "14px Roboto";
    ctx.textAlign = "center";

    data.forEach((item, index) => {
      const barHeight = (item.count / maxCount) * chartHeight;
      const x = startX + index * (barWidth + barGap);
      const y = startY - barHeight;

      ctx.fillStyle = item.color;
      ctx.fillRect(x, y, barWidth, barHeight);

      ctx.fillStyle = "#333";
      ctx.fillText(item.label, x + barWidth/2, startY + 20);
      ctx.fillText(item.count, x + barWidth/2, y - 10);
    });
  </script>

</body>
</html>
@endsection