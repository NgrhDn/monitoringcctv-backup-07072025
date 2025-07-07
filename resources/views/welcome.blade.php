@extends('layouts.user_type.auth')

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard CCTV</title>
  <script>
    function toggleDaerah(id) {
      const daerahList = document.getElementById(id);
      daerahList.style.display = (daerahList.style.display === "none" || daerahList.style.display === "") ? "block" : "none";
    }

    function toggleCCTV(id, checkbox) {
      const cctv = document.getElementById(id);
      cctv.style.display = checkbox.checked ? "block" : "none";
    }

    window.onload = function() {
      const checkboxes = document.querySelectorAll('.form-check-input');
      checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
          toggleCCTV(checkbox.id.replace('checkbox-', ''), checkbox);
        }
      });
    }
  </script>

  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      background-color: #f8f9fa;
      color: #343a40;
    }

    .container-fluid {
      width: 100%;
      margin: 0;
    }

    .sidebar {
      text-align: left;
      padding: 15px;
      background-color: #f8f9fa;
      border-right: 1px solid #ddd;
      min-height: 100vh;
    }

    .sidebar .list-group-item {
      font-size: 14px;
      padding: 10px;
      cursor: pointer;
    }

    .sidebar .list-group-item:hover {
      background-color: #f1f1f1;
    }

    .sidebar .form-check {
      margin-left: 15px;
    }

    .content {
      padding: 15px;
    }

    .content .row {
      margin: 0;
      display: flex;
      flex-wrap: wrap;
    }

    .card {
      background: transparent;
      box-shadow: none;
      margin: -10px;
      font-size: 14px;
      font-family: 'Times New Roman', Times, serif;
    }

    .card-title {
      font-size: 14px;
      font-family: 'Times New Roman', Times, serif;
    }

    .wilayah-name {
      font-size: 16px;
      font-family: 'Times New Roman', Times, serif;
      margin-bottom: 10px;
      display: none;
    }

    .cctv-view {
      flex: 0 0 25%;
      max-width: 25%;
      padding: 10px;
    }

    .iframe-container {
      position: relative;
      padding-bottom: 56.25%;
      height: 0;
      overflow: hidden;
    }

    .iframe-container iframe {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      border: none;
    }

    .iframe-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    .btn-play {
      fill: #fff;
    }
  </style>
</head>

<header style="text-align: center; margin-bottom: 20px;">
  <h1>Dashboard CCTV DIY</h1>
  <p>Memantau kondisi lalu lintas di berbagai titik kota secara real-time</p>
</header>

@php
  $cctvs = DB::table('cctvs')->get();
@endphp

<div class="container-fluid" style="padding: 0;">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
      <div class="list-group">
        @php
          $groupedCctvs = $cctvs->groupBy('namaWilayah');
        @endphp

        @foreach($groupedCctvs as $wilayah => $cctvGroup)
          <a href="#{{ Str::slug($wilayah) }}" class="list-group-item list-group-item-action" onclick="toggleDaerah('{{ Str::slug($wilayah) }}')">
            {{ $wilayah }}
          </a>
          <div id="{{ Str::slug($wilayah) }}" style="display: block;">
            @foreach($cctvGroup as $cctv)
              <div class="form-check">
          <input class="form-check-input" type="checkbox" id="checkbox-{{ Str::slug($cctv->namaTitik) }}" onclick="toggleCCTV('{{ Str::slug($cctv->namaTitik) }}', this)">
          <label class="form-check-label" for="checkbox-{{ Str::slug($cctv->namaTitik) }}">
            {{ $cctv->namaTitik }}
          </label>
              </div>
            @endforeach
          </div>
        @endforeach
            </div>
          </div>

          <!-- Main Content -->
          <div class="col-md-10 content">
            <div class="row">
        @foreach($groupedCctvs as $wilayah => $cctvGroup)
          @foreach($cctvGroup as $cctv)
            <div class="cctv-view" id="{{ Str::slug($cctv->namaTitik) }}" style="display: none;">
              <div class="card">
            <h5 class="card-title text-center mb-3">{{ $cctv->namaTitik }}</h5>
            <div class="iframe-container">
              <iframe
                src="{{ $cctv->link }}"
                frameborder="0"
                allowfullscreen>
              </iframe>
              <div class="iframe-overlay" onclick="showVideo(this)">
                <svg class="btn-play" xmlns="http://www.w3.org/2000/svg" viewBox="24 24 24 24" width="80" height="80">
            <path fill="white" d="M8 5v14l11-7z"/>
                </svg>
              </div>
            </div>
              </div>
            </div>
          @endforeach
        @endforeach
            </div>
          </div>
    </div>
  </div>
</div>
