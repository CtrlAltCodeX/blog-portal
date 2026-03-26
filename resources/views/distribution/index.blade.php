<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Authorised Distributors</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico" />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background: linear-gradient(120deg, #eef2ff, #f8fafc);
      }

      .header {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        padding: 30px 15px;
        text-align: center;
      }
      .header h1 {
        margin: 0;
        font-size: 26px;
      }
      .subtext {
        font-size: 13px;
        margin-top: 6px;
        opacity: 0.9;
      }
      .search-box {
        margin-top: 15px;
      }
      .search-box input {
        padding: 8px 12px;
        width: 220px;
        border-radius: 25px;
        border: none;
        outline: none;
      }

      .container {
        padding: 25px;
      }
      .grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
      }

      .card {
        background: linear-gradient(145deg, #ffffff, #f1f5f9);
        border-radius: 12px;
        padding: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: 0.3s;
        position: relative;
        overflow: hidden;
        border: 1px solid transparent;
      }
      .card:hover {
        transform: translateY(-6px) scale(1.05);
        border: 1px solid transparent;
        background: linear-gradient(145deg, #e0e7ff, #dbeafe);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      }
      .card img {
        width: 100%;
        height: 50px;
        object-fit: contain;
      }
      .card h4 {
        font-size: 11px;
        margin: 6px 0;
      }

      .badge {
        font-size: 10px;
        background: linear-gradient(90deg, #22c55e, #16a34a);
        color: #fff;
        padding: 2px 6px;
        border-radius: 10px;
      }

      .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          135deg,
          rgba(79, 70, 229, 0.95),
          rgba(6, 182, 212, 0.95)
        );
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: 0.3s;
        font-size: 10px;
        padding: 5px;
      }
      .card:hover .overlay {
        opacity: 1;
      }

      .pagination {
        text-align: center;
        margin-top: 20px;
      }
      .pagination button {
        padding: 6px 10px;
        margin: 3px;
        border: none;
        border-radius: 5px;
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        cursor: pointer;
        font-size: 12px;
      }
      .pagination button.active {
        background: #111;
      }

      .lead-form {
        background: linear-gradient(145deg, #ffffff, #eef2ff);
        margin: 30px 20px;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
      }
      .lead-form input,
      .lead-form select {
        padding: 8px;
        margin: 6px;
        width: 180px;
        border-radius: 6px;
        border: 1px solid #ccc;
      }
      .lead-form a {
        padding: 8px 15px;
        border: none;
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: #fff;
        border-radius: 6px;
        cursor: pointer;
      }
      .success {
        color: green;
        font-size: 13px;
        margin-top: 10px;
      }

      .seo-box {
        margin: 20px;
        border-top: 2px solid #ddd;
      }
      .seo-header {
        cursor: pointer;
        padding: 10px;
        font-weight: 600;
        background: linear-gradient(135deg, #e0e7ff, #f0f9ff);
      }
      .seo-content {
        display: none;
        padding: 10px;
        font-size: 13px;
        color: #444;
      }

      .footer {
        background: linear-gradient(135deg, #020617, #1e293b);
        color: #fff;
        padding: 25px 15px;
      }
      .footer-menu {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
      }
      .footer-menu a {
        color: #cbd5f5;
        text-decoration: none;
        font-size: 12px;
      }
      .footer-menu a:hover {
        color: #fff;
      }
      .footer p {
        text-align: center;
        font-size: 11px;
        opacity: 0.7;
      }
    </style>
  </head>
  <body>
    <div class="header">
      <h1>Authorised Distribution Network</h1>
      <div class="subtext">
        We connect institutions with trusted publication partners across India
      </div>
      <div class="search-box">
        <input
          type="text"
          id="search"
          placeholder="Search publication..."
          onkeyup="filterCards()"
        />
      </div>
    </div>

    <div class="container">
      <div id="grid" class="grid"></div>
      <div class="pagination" id="pagination"></div>
    </div>

    <div class="lead-form">
      <h3>Get Best Deals</h3>
      <a href="#" >Submit</a>
    </div>

    <div class="seo-box">
      <div class="seo-header" onclick="toggleSEO()">
        â–¼ Know More About Our Distribution Network
      </div>
      <div class="seo-content" id="seoContent">
        We are one of the leading authorised distributors of multiple
        publication houses across India. We provide genuine books at competitive
        prices with fast delivery and trusted service.
      </div>
    </div>
    
    <div class="footer">
      <div class="footer-menu">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
      </div>
      <p>© 2026 Exam360</p>
    </div>

    <script>
      const weightVsCouriers = @json($weightVsCouriers);
      const originalData = weightVsCouriers.map((w) => {
        const discounts = [
          w.book_discount_1,
          w.book_discount_2,
          w.book_discount_3,
          w.book_discount_4
        ];

        const maxDiscount = Math.max(...discounts.map(Number));

        return {
          name: w.pub_name,
          img: w.logo_url,
          discount: maxDiscount - 3
        };
      });

      let data = [...originalData];
      const perPage = 16;
      let currentPage = 1;

      function render() {
        const grid = document.getElementById("grid");
        grid.innerHTML = "";
        const start = (currentPage - 1) * perPage;
        const items = data.slice(start, start + perPage);
        items.forEach((d) => {
          grid.innerHTML += `<div class="card">
            <img src="${d.img}" alt="${d.name} Logo">
            <h4>${d.name}</h4>
            <span class="badge">Auth</span>
            <div class="overlay"><div>ðŸ”¥ Up to ${d.discount}% OFF</div><div>ðŸ“ž 9876543210</div></div>
            </div>`;
        });
        renderPagination();
      }

      function renderPagination() {
        const total = Math.ceil(data.length / perPage);
        const el = document.getElementById("pagination");
        el.innerHTML = "";
        for (let i = 1; i <= total; i++) {
          el.innerHTML += `<button onclick="goPage(${i})" class="${i === currentPage ? "active" : ""}">${i}</button>`;
        }
      }

      function goPage(p) {
        currentPage = p;
        render();
      }

      function filterCards() {
        const q = document.getElementById("search").value.toLowerCase();
        data = originalData.filter((d) => d.name.toLowerCase().includes(q));
        currentPage = 1;
        render();
      }

      let generatedOTP = "";
      function sendOTP() {
        generatedOTP = Math.floor(1000 + Math.random() * 9000).toString();
        alert("Demo OTP: " + generatedOTP);
        document.getElementById("otpSection").style.display = "block";
      }

      function verifyOTP() {
        const entered = document.getElementById("otp").value;
        if (entered === generatedOTP) {
          document.getElementById("msg").innerText =
            "Enquiry Submitted Successfully";
        } else {
          alert("Invalid OTP");
        }
      }

      function toggleSEO() {
        const c = document.getElementById("seoContent");
        c.style.display = c.style.display === "block" ? "none" : "block";
      }

      render();
    </script>
  </body>
</html>
