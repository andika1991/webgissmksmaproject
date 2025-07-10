<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Geoportal Pendidikan SMA/SMK Lampung</title>

  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Roboto', sans-serif;
      background: #f8f9fa;
      color: #333;
    }

    header {
      width: 100%;
      background: #0d253f;
      color: #fff;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 20px;
    }

    .logo {
      font-weight: bold;
      color: #00b4d8;
      text-decoration: none;
      font-size: 1.3rem;
    }

    nav {
      display: flex;
      align-items: center;
      transition: max-height 0.3s ease-out;
    }

    nav a {
      color: #fff;
      text-decoration: none;
      margin-left: 20px;
      font-size: 1rem;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #00b4d8;
    }

    .hamburger {
      display: none;
      width: 25px;
      height: 20px;
      position: relative;
      cursor: pointer;
    }

    .hamburger span {
      background: #fff;
      height: 2px;
      width: 100%;
      position: absolute;
      left: 0;
      transition: 0.3s;
    }

    .hamburger span:nth-child(1) {
      top: 0;
    }

    .hamburger span:nth-child(2) {
      top: 9px;
    }

    .hamburger span:nth-child(3) {
      top: 18px;
    }

    .hamburger.active span:nth-child(1) {
      transform: rotate(45deg);
      top: 9px;
    }

    .hamburger.active span:nth-child(2) {
      opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
      transform: rotate(-45deg);
      top: 9px;
    }

    .hero {
      min-height: 100vh;
      background: linear-gradient(rgba(13,37,63,0.6), rgba(13,37,63,0.8)),
        url('https://cdn.pixabay.com/photo/2020/04/28/08/48/technology-5103370_1280.jpg') center/cover no-repeat;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #fff;
      padding: 100px 20px 20px;
    }

    .hero-content {
      max-width: 650px;
      animation: fadeIn 1s ease-out;
    }

    .hero h1 {
      font-size: 2.5rem;
      color: #00b4d8;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: 1.1rem;
      color: #ddd;
      margin-bottom: 25px;
    }

    .hero a.btn {
      background: #00b4d8;
      color: #fff;
      text-decoration: none;
      padding: 12px 28px;
      border-radius: 30px;
      font-size: 1rem;
      transition: background 0.3s;
      display: inline-block;
    }

    .hero a.btn:hover {
      background: #0077b6;
    }

    .hero a.btnsmk {
      background: #d8bb00;
      color: #fff;
      text-decoration: none;
      padding: 12px 28px;
      border-radius: 30px;
      font-size: 1rem;
      transition: background 0.3s;
      display: inline-block;
    }

    .hero a.btn:hover {
      background: #b65e00;
    }
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .stats-section {
      padding: 50px 20px;
      background: #fff;
      text-align: center;
    }

    .stats-section h2 {
      color: #0d253f;
      font-size: 2rem;
      margin-bottom: 30px;
    }

    .chart-container {
      max-width: 100%;
      margin: 0 auto;
    }

    canvas {
      width: 100% !important;
      height: auto !important;
      max-height: 250px;
    }

    @media (max-width: 768px) {
      nav {
        position: absolute;
        top: 60px;
        left: 0;
        background: #0d253f;
        width: 100%;
        flex-direction: column;
        overflow: hidden;
        max-height: 0;
      }

      nav.active {
        max-height: 200px;
      }

      nav a {
        margin: 12px 0;
        font-size: 0.95rem;
        text-align: center;
      }

      .hamburger {
        display: block;
      }

      .hero h1 {
        font-size: 1.8rem;
      }

      .hero p {
        font-size: 1rem;
      }
    }

    
  </style>
</head>
<body>

  <header>
    <a href="#" class="logo">GIS SMA/SMK</a>
    <nav id="nav">
      <a href="/infosma">SMA</a>
      <a href="/infosmk">SMK</a>
      <a href="/peta">Peta</a>
    </nav>
    <div class="hamburger" id="hamburger">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </header>
  <main>
    @yield('content')
  </main>