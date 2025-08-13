
</head>
<body>

  <!-- Main Content -->
  <main>
    <?= $this->include('layouts/header'); ?>
    <!-- Hero Section -->
    <section id="home" class="hero-section">
      <div class="container">
        <div class="hero-content">
          <div class="hero-text">
            <h1>Selamat Datang di Sistem Monitoring PLTA Saguling</h1>
            <p class="lead">
              Efisiensi, keamanan, dan transparansi operasional pembangkit listrik tenaga air berbasis teknologi digital.
            </p>
            <a href="/menu" class="btn btn-primary btn-cta">
              <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sistem
            </a>
          </div>
          <div class="hero-image">
            <img src="img/slide1.jpg" alt="Monitoring Bendungan Saguling" class="img-fluid">
          </div>
        </div>
      </div>
    </section>

    <!-- Visi & Misi -->
    <section id="visi-misi" class="section vision-mission">
      <div class="container">
        <div class="section-header">
          <h2><i class="bi bi-stars"></i> Visi & Misi Perusahaan</h2>
          <div class="divider"></div>
        </div>
        
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="card vision-card">
              <div class="card-body">
                <div class="card-icon">
                  <i class="bi bi-eye"></i>
                </div>
                <h3>Visi</h3>
                <p>
                  Menjadi perusahaan penyedia jasa operasi dan pemeliharaan pembangkit listrik terkemuka di Asia Tenggara 
                  yang berbasis teknologi digital, berkelanjutan, dan ramah lingkungan.
                </p>
              </div>
            </div>
          </div>
          
          <div class="col-lg-6">
            <div class="card mission-card">
              <div class="card-body">
                <div class="card-icon">
                  <i class="bi bi-bullseye"></i>
                </div>
                <h3>Misi</h3>
                <ul class="mission-list">
                  <li><i class="bi bi-check-circle"></i> Memberikan pelayanan operasi dan pemeliharaan yang andal dan efisien</li>
                  <li><i class="bi bi-check-circle"></i> Mendorong digitalisasi dan transformasi teknologi</li>
                  <li><i class="bi bi-check-circle"></i> Mengutamakan keselamatan dan kelestarian lingkungan</li>
                  <li><i class="bi bi-check-circle"></i> Mengembangkan SDM yang profesional dan berintegritas</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Tentang Kami -->
    <section id="tentang" class="section about-section">
      <div class="container">
        <div class="section-header">
          <h2><i class="bi bi-building"></i> Tentang Kami</h2>
          <div class="divider"></div>
        </div>
        
        <div class="row align-items-center">
          <div class="col-lg-8">
            <p class="about-text">
              PT Indonesia Power Saguling merupakan unit operasi dari PT Indonesia Power, anak perusahaan PLN (Persero), 
              yang bertanggung jawab atas pengelolaan dan pemeliharaan Pembangkit Listrik Tenaga Air (PLTA) Saguling 
              dengan kapasitas 700 MW. Lokasi strategis di Kabupaten Bandung Barat menjadikan PLTA ini salah satu 
              penopang utama kelistrikan Jawa-Bali.
            </p>
            <p class="about-text">
              Dengan sistem monitoring berbasis digital, kami memastikan operasional pembangkit berjalan optimal, 
              aman, dan dapat dipantau secara real-time oleh tim teknis maupun manajemen.
            </p>
            
            <div class="badges-container">
              <span class="badge"><i class="bi bi-lightning-charge"></i> PLTA Saguling</span>
              <span class="badge"><i class="bi bi-lightning"></i> 700 MW</span>
              <span class="badge"><i class="bi bi-cpu"></i> Digital Monitoring</span>
            </div>
          </div>
          
          <div class="col-lg-4">
            <div class="about-image">
              <img src="img/power-plant.jpg" alt="PLTA Saguling" class="img-fluid rounded">
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="section contact-section">
      <div class="container">
        <div class="section-header">
          <h2><i class="bi bi-headset"></i> Hubungi Kami</h2>
          <div class="divider"></div>
        </div>
        
        <div class="row g-4">
          <div class="col-md-6">
            <div class="contact-info">
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                  <h4>Alamat Kantor</h4>
                  <p>
                    Komplek PLN Cioray<br>
                    Tromol Pos No. 7, Rajamandala<br>
                    Kecamatan Cipongkor, Kabupaten Bandung Barat<br>
                    Jawa Barat 40554
                  </p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="bi bi-building"></i>
                </div>
                <div>
                  <h4>Unit</h4>
                  <p>
                    PT PLN Indonesia Power<br>
                    Saguling POMU (Power Generation and O&M Services Unit)
                  </p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="bi bi-telephone"></i>
                </div>
                <div>
                  <h4>Telepon</h4>
                  <p>(022) 6868 1234</p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="bi bi-envelope"></i>
                </div>
                <div>
                  <h4>Email</h4>
                  <p>info.saguling@indonesiapower.co.id</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="map-container">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.829944027793!2d107.4479489748169!3d-6.85863999324794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e7d7d7c7f5c1%3A0x5a5f5d5a5a5a5a5a!2sPLTA%20Saguling%20(PT%20Indonesia%20Power%20-%20Saguling%20POMU)!5e0!3m2!1sid!2sid!4v1720000000000!5m2!1sid!2sid"
                width="100%"
                height="100%"
                style="border:0; border-radius: 8px;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?= $this->include('layouts/footer'); ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('current-year').textContent = new Date().getFullYear();
  </script>
</body>
</html>