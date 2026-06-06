<?php
session_start();
$loggedIn = isset($_SESSION['adam_admin']) && $_SESSION['adam_admin'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ADAM Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow:wght@300;400;600;700&family=Barlow+Condensed:wght@400;600;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#0a0a0a;--bg2:#111;--bg3:#1a1a1a;--card:#141414;--orange:#FF6B00;--orange2:#ff8c33;--white:#f0ece4;--gray:#888;--gray2:#333;--green:#25D366;--red:#e74c3c;--blue:#3498db;}
*{margin:0;padding:0;box-sizing:border-box;}
body{background:var(--bg);color:var(--white);font-family:'Barlow',sans-serif;overflow-x:hidden;}
::-webkit-scrollbar{width:4px;}::-webkit-scrollbar-track{background:var(--bg);}::-webkit-scrollbar-thumb{background:var(--orange);}
nav{position:fixed;top:0;left:0;right:0;z-index:1000;display:flex;align-items:center;justify-content:space-between;padding:14px 28px;background:rgba(10,10,10,.97);border-bottom:1px solid rgba(255,107,0,.15);}
.nav-logo{font-family:'Bebas Neue',sans-serif;font-size:1.6rem;letter-spacing:.3em;}
.nav-logo span{color:var(--orange);}
.nav-tag{font-family:'Barlow Condensed',sans-serif;font-size:.75rem;color:var(--orange);letter-spacing:.2em;font-weight:700;margin-left:8px;border:1px solid rgba(255,107,0,.3);padding:2px 8px;}
.nav-right{display:flex;gap:8px;}
.nbtn{background:none;border:1px solid var(--gray2);color:var(--white);padding:6px 14px;cursor:pointer;font-family:'Barlow Condensed',sans-serif;font-size:.72rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;}
.nbtn:hover{border-color:var(--orange);color:var(--orange);}
/* LOGIN */
.login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;}
.login-card{background:var(--bg2);border:1px solid var(--gray2);padding:40px;width:380px;max-width:92vw;}
.login-logo{font-family:'Bebas Neue',sans-serif;font-size:2rem;letter-spacing:.3em;text-align:center;margin-bottom:6px;}
.login-logo span{color:var(--orange);}
.login-sub{font-family:'Barlow Condensed',sans-serif;font-size:.72rem;letter-spacing:.2em;text-transform:uppercase;color:var(--gray);text-align:center;margin-bottom:28px;}
.lerr{background:rgba(231,76,60,.1);border:1px solid rgba(231,76,60,.3);color:var(--red);padding:10px 14px;font-size:.8rem;margin-bottom:14px;display:none;}
.lerr.show{display:block;}
/* LAYOUT */
.admin-wrap{display:flex;min-height:calc(100vh - 54px);margin-top:54px;}
.sidebar{width:210px;min-width:210px;background:var(--bg2);border-right:1px solid var(--gray2);padding:22px 0;position:sticky;top:54px;height:calc(100vh - 54px);overflow-y:auto;flex-shrink:0;}
.sidebar h3{font-family:'Bebas Neue',sans-serif;font-size:.9rem;letter-spacing:.2em;color:var(--gray);padding:0 16px;margin-bottom:14px;}
.snav{list-style:none;}
.snav li a{display:flex;align-items:center;gap:9px;padding:10px 16px;color:var(--gray);font-family:'Barlow Condensed',sans-serif;font-size:.85rem;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;transition:all .2s;}
.snav li a:hover{color:var(--white);background:rgba(255,107,0,.05);}
.snav li a.active{color:var(--orange);background:rgba(255,107,0,.08);border-left:2px solid var(--orange);}
.content{flex:1;padding:26px;overflow-x:hidden;min-width:0;}
.sec{display:none;}
.sec.active{display:block;}
.page-title{font-family:'Bebas Neue',sans-serif;font-size:1.9rem;letter-spacing:.05em;margin-bottom:20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
.tag-pill{font-family:'Barlow Condensed',sans-serif;font-size:.7rem;font-weight:600;letter-spacing:.1em;background:rgba(255,107,0,.12);color:var(--orange);padding:3px 10px;border:1px solid rgba(255,107,0,.3);}
/* STATS */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:13px;margin-bottom:22px;}
.stat{background:var(--card);border:1px solid var(--gray2);padding:17px 20px;position:relative;overflow:hidden;}
.stat::before{content:'';position:absolute;left:0;top:0;bottom:0;width:2px;background:var(--orange);}
.stat-lbl{font-family:'Barlow Condensed',sans-serif;font-size:.68rem;letter-spacing:.15em;text-transform:uppercase;color:var(--gray);margin-bottom:5px;}
.stat-val{font-family:'Bebas Neue',sans-serif;font-size:1.85rem;color:var(--white);line-height:1;}
.stat-sub{font-size:.67rem;color:var(--green);margin-top:3px;}
/* TABLE */
.tbl-wrap{background:var(--card);border:1px solid var(--gray2);overflow-x:auto;}
.tbl{width:100%;border-collapse:collapse;}
.tbl th{background:var(--bg3);padding:10px 14px;font-family:'Barlow Condensed',sans-serif;font-size:.68rem;font-weight:700;letter-spacing:.15em;text-transform:uppercase;color:var(--gray);text-align:left;border-bottom:1px solid var(--gray2);white-space:nowrap;}
.tbl td{padding:11px 14px;font-size:.8rem;border-bottom:1px solid rgba(51,51,51,.5);vertical-align:middle;}
.tbl tr:last-child td{border-bottom:none;}
.tbl tr:hover td{background:rgba(255,107,0,.02);}
.bdg{display:inline-block;padding:3px 8px;font-family:'Barlow Condensed',sans-serif;font-size:.63rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;}
.bdg-new{background:rgba(255,107,0,.15);color:var(--orange);border:1px solid rgba(255,107,0,.3);}
.bdg-done{background:rgba(39,174,96,.12);color:#27ae60;border:1px solid rgba(39,174,96,.3);}
.bdg-pend{background:rgba(52,152,219,.12);color:#3498db;border:1px solid rgba(52,152,219,.3);}
.bdg-cancel{background:rgba(231,76,60,.12);color:var(--red);border:1px solid rgba(231,76,60,.3);}
/* ORDER DETAIL MODAL */
.modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:3000;display:none;align-items:center;justify-content:center;padding:16px;}
.modal-bg.open{display:flex;}
.modal-box{background:var(--bg2);border:1px solid var(--gray2);width:100%;max-width:600px;max-height:90vh;overflow-y:auto;position:relative;}
.modal-head{padding:18px 22px;border-bottom:1px solid var(--gray2);display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:var(--bg2);z-index:1;}
.modal-head h2{font-family:'Bebas Neue',sans-serif;font-size:1.5rem;letter-spacing:.05em;}
.modal-head h2 span{color:var(--orange);}
.mcls{background:none;border:none;color:var(--gray);font-size:1.2rem;cursor:pointer;line-height:1;}
.mcls:hover{color:var(--white);}
.modal-body{padding:22px;}
.detail-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid rgba(51,51,51,.4);font-size:.82rem;}
.detail-row:last-child{border-bottom:none;}
.detail-key{color:var(--gray);font-family:'Barlow Condensed',sans-serif;letter-spacing:.08em;text-transform:uppercase;font-size:.7rem;}
.detail-val{font-weight:600;text-align:right;max-width:60%;}
/* FORMS */
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:13px;}
.fg{display:flex;flex-direction:column;gap:5px;}
.fg.full{grid-column:1/-1;}
.flbl{font-family:'Barlow Condensed',sans-serif;font-size:.7rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:var(--gray);}
.fin,.fsel,.ftxt{background:var(--bg3);border:1px solid var(--gray2);padding:10px 13px;color:var(--white);font-family:'Barlow',sans-serif;font-size:.85rem;transition:border-color .2s;width:100%;}
.fin:focus,.fsel:focus,.ftxt:focus{outline:none;border-color:var(--orange);}
.fsel{cursor:pointer;}
.ftxt{resize:vertical;min-height:75px;}
.form-actions{display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;}
.card{background:var(--card);border:1px solid var(--gray2);padding:20px;margin-bottom:20px;}
.card-title{font-family:'Bebas Neue',sans-serif;font-size:1.05rem;letter-spacing:.05em;margin-bottom:14px;color:var(--orange);}
/* BUTTONS */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 22px;font-family:'Barlow Condensed',sans-serif;font-size:.82rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;cursor:pointer;border:none;transition:all .2s;}
.btn-primary{background:var(--orange);color:#000;}
.btn-primary:hover{background:var(--orange2);}
.btn-outline{background:transparent;color:var(--white);border:1px solid rgba(240,236,228,.3);}
.btn-outline:hover{border-color:var(--orange);color:var(--orange);}
.btn-sm{padding:5px 12px;font-size:.68rem;}
.btn-danger{background:var(--red);color:#fff;}
.btn-success{background:#27ae60;color:#fff;}
.btn-info{background:var(--blue);color:#fff;}
.btn-wa{background:var(--green);color:#000;}
/* IMAGE UPLOAD */
/* ── IMAGE UPLOAD ── */
.upload-zone{border:2px dashed var(--gray2);padding:0;transition:all .2s;position:relative;background:var(--bg3);overflow:hidden;}
.upload-zone.drag,.upload-zone:hover{border-color:var(--orange);}
.upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;z-index:2;}
.upload-zone-inner{padding:28px;text-align:center;pointer-events:none;}
.upload-zone-icon{font-size:2.2rem;display:block;margin-bottom:10px;}
.upload-zone-title{font-family:'Barlow Condensed',sans-serif;font-size:.9rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--white);margin-bottom:4px;}
.upload-zone-sub{font-size:.75rem;color:var(--gray);}
.upload-zone-sub span{color:var(--orange);}
/* Gallery */
.img-gallery{display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;margin-top:14px;}
.img-gallery-item{position:relative;background:var(--bg3);border:2px solid var(--gray2);aspect-ratio:1;overflow:hidden;transition:border-color .2s;}
.img-gallery-item.primary-img{border-color:var(--orange);}
.img-gallery-item img{width:100%;height:100%;object-fit:cover;display:block;}
.img-gallery-item .img-overlay{position:absolute;inset:0;background:rgba(0,0,0,0);transition:background .2s;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;opacity:0;transition:all .2s;}
.img-gallery-item:hover .img-overlay{background:rgba(0,0,0,.65);opacity:1;}
.img-overlay-btn{background:none;border:1px solid rgba(255,255,255,.6);color:#fff;padding:5px 10px;cursor:pointer;font-family:'Barlow Condensed',sans-serif;font-size:.68rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;transition:all .2s;white-space:nowrap;}
.img-overlay-btn:hover{background:var(--orange);border-color:var(--orange);color:#000;}
.img-overlay-btn.danger:hover{background:var(--red);border-color:var(--red);}
.img-primary-badge{position:absolute;top:6px;left:6px;background:var(--orange);color:#000;font-family:'Barlow Condensed',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:2px 7px;z-index:1;}
.img-num-badge{position:absolute;top:6px;right:6px;background:rgba(0,0,0,.6);color:var(--gray);font-family:'Barlow Condensed',sans-serif;font-size:.6rem;padding:2px 6px;z-index:1;}
/* Upload progress */
.img-uploading{position:absolute;inset:0;background:rgba(10,10,10,.85);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;}
.upload-progress-bar{width:70%;height:3px;background:var(--gray2);border-radius:2px;overflow:hidden;}
.upload-progress-fill{height:100%;background:var(--orange);border-radius:2px;animation:progressAnim 1.5s ease-in-out infinite;}
@keyframes progressAnim{0%{width:10%;}60%{width:85%;}100%{width:95%;}}
.upload-progress-txt{font-family:'Barlow Condensed',sans-serif;font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gray);}
/* Gallery actions bar */
.gallery-actions{display:flex;align-items:center;justify-content:space-between;margin-top:10px;padding:8px 12px;background:var(--bg3);border:1px solid var(--gray2);}
.gallery-count{font-family:'Barlow Condensed',sans-serif;font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gray);}
.gallery-count span{color:var(--orange);font-size:.9rem;}
.gallery-hint{font-size:.68rem;color:var(--gray);font-style:italic;}
/* PROD GRID */
.prod-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:13px;margin-top:16px;}
.prod-card{background:var(--card);border:1px solid var(--gray2);overflow:hidden;transition:border-color .2s;}
.prod-card:hover{border-color:var(--orange);}
.prod-thumb{aspect-ratio:4/3;background:var(--bg3);display:flex;align-items:center;justify-content:center;font-size:3rem;overflow:hidden;position:relative;}
.prod-thumb img{width:100%;height:100%;object-fit:cover;}
.prod-img-count{position:absolute;bottom:4px;right:4px;background:rgba(0,0,0,.7);color:var(--orange);font-family:'Barlow Condensed',sans-serif;font-size:.6rem;font-weight:700;letter-spacing:.06em;padding:2px 6px;}
.prod-info{padding:11px;}
.prod-name{font-family:'Barlow Condensed',sans-serif;font-size:.82rem;font-weight:700;letter-spacing:.05em;text-transform:uppercase;margin-bottom:3px;}
.prod-price{font-family:'Bebas Neue',sans-serif;font-size:1rem;color:var(--orange);margin-bottom:7px;}
/* COUPON */
.coup-list{display:flex;flex-direction:column;gap:9px;margin-top:14px;}
.coup-item{background:var(--bg3);border:1px solid var(--gray2);padding:13px 16px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;}
.coup-code{font-family:'Bebas Neue',sans-serif;font-size:1.2rem;color:var(--orange);letter-spacing:.1em;}
.coup-info{font-size:.72rem;color:var(--gray);}
/* CHART */
.chart-wrap{display:flex;align-items:flex-end;gap:7px;height:130px;padding:0 3px;}
.chart-col{display:flex;flex-direction:column;align-items:center;flex:1;}
.chart-bar{background:var(--orange);width:100%;border-radius:2px 2px 0 0;position:relative;min-height:3px;}
.chart-bar:hover{background:var(--orange2);}
.chart-bar-lbl{position:absolute;top:-15px;left:50%;transform:translateX(-50%);font-size:.55rem;color:var(--orange);white-space:nowrap;}
.chart-day{font-family:'Barlow Condensed',sans-serif;font-size:.58rem;color:var(--gray);margin-top:4px;}
/* SPINNER */
.spinner{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.2);border-top-color:#fff;border-radius:50%;animation:spin .6s linear infinite;}
.spinner-dark{border:2px solid rgba(0,0,0,.2);border-top-color:#000;}
@keyframes spin{to{transform:rotate(360deg);}}
.toast{position:fixed;bottom:24px;right:24px;background:var(--orange);color:#000;padding:10px 18px;font-family:'Barlow Condensed',sans-serif;font-size:.8rem;font-weight:700;letter-spacing:.05em;z-index:9999;transform:translateY(14px);opacity:0;transition:all .3s;pointer-events:none;max-width:300px;}
.toast.show{transform:translateY(0);opacity:1;}

/* ── ADMIN RESPONSIVE ── */
@media(max-width:900px){
  .stats-grid{grid-template-columns:1fr 1fr;}
  .form-grid{grid-template-columns:1fr;}
  .sidebar{width:180px;min-width:180px;}
  .tbl th,.tbl td{padding:9px 10px;font-size:.75rem;}
}
@media(max-width:768px){
  .admin-wrap{flex-direction:column;}
  .sidebar{width:100%;min-width:unset;height:auto;position:static;padding:10px 0 0;border-right:none;border-bottom:1px solid var(--gray2);}
  .sidebar h3{display:none;}
  .snav{display:flex;overflow-x:auto;padding:0 8px 8px;gap:2px;-webkit-overflow-scrolling:touch;}
  .snav::-webkit-scrollbar{height:2px;}
  .snav li a{white-space:nowrap;flex-direction:column;font-size:.6rem;padding:8px 12px;gap:2px;border-left:none;border-bottom:2px solid transparent;}
  .snav li a.active{border-left:none;border-bottom:2px solid var(--orange);}
  .content{padding:18px;}
  .stats-grid{grid-template-columns:1fr 1fr;gap:10px;}
  .stat{padding:14px 16px;}
  .stat-val{font-size:1.6rem;}
  .page-title{font-size:1.6rem;margin-bottom:16px;}
  .tbl-wrap{overflow-x:auto;-webkit-overflow-scrolling:touch;}
  .tbl{min-width:600px;}
  .prod-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;}
  .form-grid{grid-template-columns:1fr;}
  .card{padding:16px;}
  nav{padding:12px 16px;}
}
@media(max-width:560px){
  .stats-grid{grid-template-columns:1fr 1fr;}
  .content{padding:14px;}
  .page-title{font-size:1.4rem;}
  nav .nav-right{gap:6px;}
  nav .nbtn{padding:5px 10px;font-size:.68rem;}
  .modal-box{max-height:95vh;}
  .modal-head{padding:14px 16px;}
  .modal-body{padding:16px;}
  .prod-grid{grid-template-columns:repeat(2,1fr);}
  .form-actions{flex-direction:column;}
  .form-actions .btn{width:100%;justify-content:center;}
  .coup-item{flex-direction:column;align-items:flex-start;}
}
@media(max-width:400px){
  .stats-grid{grid-template-columns:1fr;}
  .snav li a{padding:6px 10px;font-size:.58rem;}
  .tbl{min-width:520px;}
}
</style>
</head>
<body>

<?php if(!$loggedIn): ?>
<!-- ===== LOGIN ===== -->
<div class="login-wrap">
  <div class="login-card">
    <div class="login-logo">AD<span>A</span>M</div>
    <div class="login-sub">Admin Panel — Secure Login</div>
    <div class="lerr" id="lerr"></div>
    <div class="fg" style="margin-bottom:12px;"><label class="flbl">Username</label><input class="fin" type="text" id="lu" placeholder="admin"></div>
    <div class="fg" style="margin-bottom:18px;"><label class="flbl">Password</label><input class="fin" type="password" id="lp" placeholder="••••••••" onkeydown="if(event.key==='Enter')doLogin()"></div>
    <button class="btn btn-primary" style="width:100%;" onclick="doLogin()" id="loginBtn">Login →</button>
    <p style="font-size:.7rem;color:var(--gray);text-align:center;margin-top:11px;">Default: admin / adam2026</p>
    <a href="../" class="btn btn-outline btn-sm" style="width:100%;margin-top:9px;display:flex;">← Back to Store</a>
  </div>
</div>

<?php else: ?>
<!-- ===== ADMIN ===== -->
<nav>
  <div><span class="nav-logo">AD<span>A</span>M</span><span class="nav-tag">ADMIN</span></div>
  <div class="nav-right">
    <a href="../" class="nbtn">← Store</a>
    <button class="nbtn" onclick="doLogout()">Logout</button>
  </div>
</nav>

<div class="admin-wrap">
  <div class="sidebar">
    <h3>Navigation</h3>
    <ul class="snav">
      <li><a class="active" onclick="nav('dashboard',this)">📊 Dashboard</a></li>
      <li><a onclick="nav('orders',this)">📦 Orders</a></li>
      <li><a onclick="nav('products',this)">👕 Products</a></li>
      <li><a onclick="nav('addproduct',this)">➕ Add Product</a></li>
      <li><a onclick="nav('coupons',this)">🏷️ Coupons</a></li>
      <li><a onclick="nav('customers',this)">👥 Customers</a></li>
      <li><a onclick="nav('reviews',this)">⭐ Reviews</a></li>
      <li><a onclick="nav('settings',this)">⚙️ Settings</a></li>
    </ul>
  </div>

  <div class="content">

    <!-- DASHBOARD -->
    <div class="sec active" id="s-dashboard">
      <div class="page-title">Dashboard <span class="tag-pill" id="dashDate"></span></div>
      <div class="stats-grid">
        <div class="stat"><div class="stat-lbl">Total Sales</div><div class="stat-val" id="dSales">৳—</div><div class="stat-sub">Delivered orders</div></div>
        <div class="stat"><div class="stat-lbl">Orders</div><div class="stat-val" id="dOrders">—</div><div class="stat-sub" id="dNewOrders">Loading...</div></div>
        <div class="stat"><div class="stat-lbl">Products</div><div class="stat-val" id="dProds">—</div><div class="stat-sub">In database</div></div>
        <div class="stat"><div class="stat-lbl">Customers</div><div class="stat-val" id="dCusts">—</div><div class="stat-sub">Registered</div></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
        <div class="card">
          <div class="card-title">📈 Weekly Sales (BDT)</div>
          <div class="chart-wrap" id="salesChart"></div>
          <div style="display:flex;justify-content:space-between;margin-top:4px;padding:0 3px;" id="chartDays"></div>
        </div>
        <div class="card"><div class="card-title">📦 Recent Orders</div><div id="recentOrders">Loading...</div></div>
      </div>
    </div>

    <!-- ORDERS -->
    <div class="sec" id="s-orders">
      <div class="page-title">Orders <span class="tag-pill" id="ordersCount">0</span></div>
      <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;align-items:center;">
        <select class="fsel" style="width:auto;" id="orderFilter" onchange="loadOrders()">
          <option value="all">All Orders</option>
          <option value="new">🔔 New</option>
          <option value="pending">⏳ Pending</option>
          <option value="done">✅ Delivered</option>
          <option value="cancel">❌ Cancelled</option>
        </select>
        <button class="btn btn-primary btn-sm" onclick="addSampleOrder()">+ Sample Order</button>
      </div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>#</th><th>Customer</th><th>Address</th><th>Items</th><th>Subtotal</th><th>Delivery</th><th>Total</th><th>Payment</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody id="ordersBody"><tr><td colspan="8" style="text-align:center;padding:28px;color:var(--gray);">Loading orders...</td></tr></tbody>
        </table>
      </div>
    </div>

    <!-- ORDER DETAIL MODAL -->
    <div class="modal-bg" id="orderDetailModal">
      <div class="modal-box">
        <div class="modal-head"><h2>Order <span id="modalOrderCode">—</span></h2><button class="mcls" onclick="closeOrderDetail()">✕</button></div>
        <div class="modal-body" id="modalOrderBody"></div>
      </div>
    </div>

    <!-- PRODUCTS -->
    <div class="sec" id="s-products">
      <div class="page-title">Products <span class="tag-pill" id="prodCount">0</span></div>
      <div class="prod-grid" id="prodGrid"></div>
    </div>

    <!-- ADD/EDIT PRODUCT -->
    <div class="sec" id="s-addproduct">
      <div class="page-title" id="addProdTitle">Add Product</div>
      <div class="card">
        <input type="hidden" id="edit-id">
        <div class="form-grid">
          <div class="fg"><label class="flbl">Name *</label><input class="fin" id="np-name" placeholder="Classic Black Tee"></div>
          <div class="fg"><label class="flbl">Category *</label>
            <select class="fsel" id="np-cat">
              <option value="tshirt">T-Shirt</option><option value="pants">Pants</option>
              <option value="polo">Polo</option><option value="hoodie">Hoodie</option><option value="other">Other</option>
            </select>
          </div>
          <div class="fg"><label class="flbl">Price (৳) *</label><input class="fin" type="number" id="np-price" placeholder="699"></div>
          <div class="fg"><label class="flbl">Old Price (৳)</label><input class="fin" type="number" id="np-old" placeholder="999 (optional)"></div>
          <div class="fg"><label class="flbl">Badge</label><input class="fin" id="np-badge" placeholder="HOT / NEW / SALE"></div>
          <div class="fg"><label class="flbl">Emoji (fallback)</label><input class="fin" id="np-emoji" value="👕"></div>
          <div class="fg"><label class="flbl">Stock</label><input class="fin" type="number" id="np-stock" value="100"></div>
          <div class="fg"><label class="flbl">Status</label>
            <select class="fsel" id="np-active"><option value="1">Active</option><option value="0">Hidden</option></select>
          </div>
          <div class="fg full">
            <label class="flbl">Available Sizes <span style="color:var(--gray);font-size:.65rem;text-transform:none;letter-spacing:0;">(click to add/remove)</span></label>
            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;" id="sizeBtnGroup">
              <!-- preset size buttons -->
            </div>
            <div style="display:flex;gap:8px;margin-bottom:8px;">
              <select class="fsel" style="width:auto;flex:1;" id="sizePresetSel">
                <optgroup label="Clothing (S-XXL)">
                  <option value="S">S</option><option value="M">M</option><option value="L">L</option>
                  <option value="XL">XL</option><option value="XXL">XXL</option><option value="XXXL">XXXL</option>
                </optgroup>
                <optgroup label="Pants (waist)">
                  <option value="28">28</option><option value="30">30</option><option value="32">32</option>
                  <option value="34">34</option><option value="36">36</option><option value="38">38</option><option value="40">40</option>
                </optgroup>
                <optgroup label="Shoes (UK)">
                  <option value="6">UK 6</option><option value="7">UK 7</option><option value="8">UK 8</option>
                  <option value="9">UK 9</option><option value="10">UK 10</option><option value="11">UK 11</option>
                </optgroup>
                <optgroup label="Custom">
                  <option value="FREE SIZE">Free Size</option><option value="ONE SIZE">One Size</option>
                </optgroup>
              </select>
              <button class="btn btn-outline btn-sm" onclick="addSizeFromSelect()">+ Add Size</button>
            </div>
            <input class="fin" id="np-sizes-hidden" placeholder="Or type: S, M, L, XL (comma separated)" oninput="syncSizesFromInput(this.value)">
            <div style="font-size:.68rem;color:var(--gray);margin-top:5px;">Selected: <span id="sizesPreview" style="color:var(--orange);">None</span></div>
          </div>
          <div class="fg full"><label class="flbl">Description</label><textarea class="ftxt" id="np-desc" placeholder="Short description..."></textarea></div>
          <div class="fg full">
            <label class="flbl">Product Images <span style="color:var(--gray);font-size:.62rem;text-transform:none;letter-spacing:0;">— Upload multiple photos. First image = main photo shown to customers.</span></label>
            <div class="upload-zone" id="uploadZone">
              <input type="file" id="imgInput" accept="image/jpeg,image/png,image/webp,image/gif" multiple onchange="handleUpload(this.files)">
              <div class="upload-zone-inner">
                <span class="upload-zone-icon">📸</span>
                <div class="upload-zone-title">Click to browse or drag & drop</div>
                <div class="upload-zone-sub">JPG · PNG · WEBP · Max <span>5MB</span> per image · <span>Unlimited</span> photos</div>
              </div>
            </div>
            <div class="img-gallery" id="imgGallery"></div>
            <div class="gallery-actions" id="galleryActions" style="display:none;">
              <div class="gallery-count"><span id="imgCount">0</span> image(s) uploaded</div>
              <div style="display:flex;gap:8px;align-items:center;">
                <span class="gallery-hint">Hover image to reorder or delete</span>
                <button class="btn btn-sm btn-danger" onclick="clearAllImages()" style="padding:4px 10px;font-size:.65rem;">✕ Clear All</button>
              </div>
            </div>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" onclick="saveProduct()" id="saveProdBtn">+ Save Product</button>
          <button class="btn btn-outline" onclick="clearProdForm()">Clear</button>
        </div>
      </div>
    </div>

    <!-- COUPONS -->
    <div class="sec" id="s-coupons">
      <div class="page-title">Coupons</div>
      <div class="card">
        <div class="card-title">Create New Coupon</div>
        <div class="form-grid">
          <div class="fg"><label class="flbl">Code</label><input class="fin" id="cp-code" placeholder="ADAM2FREE" style="text-transform:uppercase;"></div>
          <div class="fg"><label class="flbl">Type</label>
            <select class="fsel" id="cp-type">
              <option value="free_delivery">Free Delivery</option>
              <option value="percent">Percentage %</option>
              <option value="fixed">Fixed Amount ৳</option>
            </select>
          </div>
          <div class="fg"><label class="flbl">Value</label><input class="fin" type="number" id="cp-val" placeholder="0"></div>
          <div class="fg"><label class="flbl">Min Items Required</label><input class="fin" type="number" id="cp-min" value="1"></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary" onclick="addCoupon()">+ Create Coupon</button></div>
      </div>
      <div class="coup-list" id="coupList"></div>
    </div>

    <!-- CUSTOMERS -->
    <div class="sec" id="s-customers">
      <div class="page-title">Customers <span class="tag-pill" id="custCount">0</span></div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>#</th><th>Name</th><th>Phone</th><th>Location</th><th>Orders</th><th>Total Spent</th><th>Action</th></tr></thead>
          <tbody id="custBody"></tbody>
        </table>
      </div>
    </div>

    <!-- REVIEWS -->
    <div class="sec" id="s-reviews">
      <div class="page-title">Reviews</div>
      <div class="tbl-wrap">
        <table class="tbl">
          <thead><tr><th>Customer</th><th>Product</th><th>Rating</th><th>Review</th><th>Status</th><th>Action</th></tr></thead>
          <tbody id="revBody"></tbody>
        </table>
      </div>
    </div>

    <!-- SETTINGS -->
    <div class="sec" id="s-settings">
      <div class="page-title">Settings</div>
      <div class="card">
        <div class="card-title">Store Info</div>
        <div class="form-grid">
          <div class="fg"><label class="flbl">Store Name</label><input class="fin" id="st-name" value="ADAM Men's Fashion"></div>
          <div class="fg"><label class="flbl">WhatsApp Number</label><input class="fin" id="st-wa" value="01675760715"></div>
          <div class="fg full"><label class="flbl">Description</label><textarea class="ftxt" id="st-desc">Premium men's fashion in Bangladesh.</textarea></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary" onclick="saveSettings()">Save Settings</button></div>
      </div>
      <div class="card">
        <div class="card-title">Change Admin Password</div>
        <div class="form-grid">
          <div class="fg"><label class="flbl">Current Password</label><input class="fin" type="password" id="old-p"></div>
          <div class="fg"><label class="flbl">New Password</label><input class="fin" type="password" id="new-p"></div>
        </div>
        <div class="form-actions"><button class="btn btn-primary" onclick="changePass()">Change Password</button></div>
      </div>
    </div>

  </div><!-- content -->
</div><!-- admin-wrap -->
<?php endif; ?>

<div class="toast" id="toast"></div>

<script>
// Auto-detect API path
const API=(function(){
  const p=window.location.pathname;
  // admin is one level deep (e.g. /admin/ or /subfolder/admin/)
  const parts=p.replace(/\/+$/,'').split('/');
  parts.pop(); // remove 'admin' or 'index.php'
  return parts.join('/')+'/api';
})();
let uploadedImages=[];
let waNum='01675760715';

async function api(ep,method='GET',body=null){
  try{
    const o={method,headers:{'Content-Type':'application/json'},credentials:'include'};
    if(body) o.body=JSON.stringify(body);
    const r=await fetch(API+'/'+ep,o);
    return await r.json();
  }catch(e){return{success:false,error:e.message};}
}

// ===== AUTH =====
async function doLogin(){
  const btn=document.getElementById('loginBtn');
  btn.innerHTML='<span class="spinner spinner-dark"></span> Logging in...';btn.disabled=true;
  const res=await api('auth','POST',{username:document.getElementById('lu').value.trim(),password:document.getElementById('lp').value});
  btn.innerHTML='Login →';btn.disabled=false;
  if(res.success){location.reload();}
  else{const e=document.getElementById('lerr');e.textContent=res.error||'Login failed';e.classList.add('show');}
}
async function doLogout(){await api('auth','DELETE');location.reload();}

// ===== NAV =====
function nav(sec,el){
  document.querySelectorAll('.sec').forEach(s=>s.classList.remove('active'));
  document.getElementById('s-'+sec).classList.add('active');
  document.querySelectorAll('.snav a').forEach(a=>a.classList.remove('active'));
  if(el)el.classList.add('active');
  const map={dashboard:loadDashboard,orders:loadOrders,products:loadProducts,coupons:loadCoupons,customers:loadCustomers,reviews:loadReviews};
  if(map[sec])map[sec]();
}

// ===== DASHBOARD =====
async function loadDashboard(){
  document.getElementById('dashDate').textContent=new Date().toLocaleDateString('en-BD',{weekday:'short',year:'numeric',month:'short',day:'numeric'});
  const res=await api('stats');
  if(!res.success)return;
  const d=res.data;
  document.getElementById('dSales').textContent='৳'+Number(d.total_sales).toLocaleString();
  document.getElementById('dOrders').textContent=d.total_orders;
  document.getElementById('dNewOrders').textContent=d.new_orders+' new · '+d.pending_orders+' pending';
  document.getElementById('dProds').textContent=d.total_products;
  document.getElementById('dCusts').textContent=d.total_customers;
  // Chart
  const vals=d.weekly_sales.map(w=>w.sales);
  const max=Math.max(...vals,1);
  document.getElementById('salesChart').innerHTML=vals.map(v=>`
    <div class="chart-col">
      <div class="chart-bar" style="height:${Math.round((v/max)*120)}px;">
        <span class="chart-bar-lbl">৳${Math.round(v/1000)}K</span>
      </div>
    </div>`).join('');
  document.getElementById('chartDays').innerHTML=d.weekly_sales.map(w=>
    `<span class="chart-day">${w.day}</span>`).join('');
  // Recent orders
  document.getElementById('recentOrders').innerHTML=d.recent_orders.length?d.recent_orders.map(o=>`
    <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--gray2);font-size:.78rem;">
      <div>
        <div style="font-weight:600;">${o.customer_name}</div>
        <div style="color:var(--gray);font-size:.68rem;">${o.order_code||'#'+o.id} · ${o.created_at?o.created_at.split(' ')[0]:''}</div>
      </div>
      <div style="text-align:right;">
        <div style="color:var(--orange);font-family:'Bebas Neue',sans-serif;font-size:.9rem;">৳${Number(o.total).toLocaleString()}</div>
        <span class="bdg bdg-${o.status==='done'?'done':o.status==='new'?'new':o.status==='cancel'?'cancel':'pend'}">${o.status}</span>
      </div>
    </div>`).join(''):'<p style="color:var(--gray);font-size:.8rem;padding:10px 0;">No orders yet.</p>';
}

// ===== ORDERS =====
async function loadOrders(){
  const filter=document.getElementById('orderFilter')?.value||'all';
  const res=await api('orders?status='+filter);
  const orders=res.data||[];
  document.getElementById('ordersCount').textContent=orders.length;
  if(!orders.length){
    document.getElementById('ordersBody').innerHTML='<tr><td colspan="8" style="text-align:center;color:var(--gray);padding:28px;">No orders found.</td></tr>';
    return;
  }
  document.getElementById('ordersBody').innerHTML=orders.map(o=>{
    const items=Array.isArray(o.items)?o.items.map(i=>i.name+(i.size?' ('+i.size+')':'')+(i.qty>1?' x'+i.qty:'')).join(', '):(typeof o.items==='string'?o.items:'');
    return `<tr>
      <td style="color:var(--orange);font-weight:600;cursor:pointer;white-space:nowrap;" onclick="viewOrder(${o.id})">${o.order_code||'#'+o.id}</td>
      <td>
        <div style="font-weight:600;font-size:.82rem;">${o.customer_name}</div>
        <div style="font-size:.68rem;color:var(--green);cursor:pointer;" onclick="window.open('https://wa.me/88${o.customer_phone}','_blank')">${o.customer_phone}</div>
      </td>
      <td style="font-size:.72rem;color:var(--gray);">${o.district||'—'}${o.city?'<br><span style="font-size:.65rem;">'+o.city+'</span>':''}</td>
      <td style="font-size:.72rem;max-width:120px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${items}">${items}</td>
      <td style="font-family:'Bebas Neue',sans-serif;font-size:.95rem;">৳${Number(o.subtotal||o.total).toLocaleString()}</td>
      <td style="font-size:.78rem;white-space:nowrap;${Number(o.delivery_charge||80)===80?'color:#27ae60;':'color:var(--orange);'}">৳${Number(o.delivery_charge||80)}</td>
      <td style="font-family:'Bebas Neue',sans-serif;font-size:1rem;color:var(--orange);font-weight:700;">৳${Number(o.total).toLocaleString()}</td>
      <td><span class="bdg bdg-pend" style="font-size:.6rem;">${o.payment_method}</span></td>
      <td>
        <select class="fsel" style="width:auto;padding:4px 8px;font-size:.68rem;" onchange="updateStatus(${o.id},this.value)">
          <option value="new" ${o.status==='new'?'selected':''}>🔔 New</option>
          <option value="pending" ${o.status==='pending'?'selected':''}>⏳ Pending</option>
          <option value="done" ${o.status==='done'?'selected':''}>✅ Delivered</option>
          <option value="cancel" ${o.status==='cancel'?'selected':''}>❌ Cancelled</option>
        </select>
      </td>
      <td>
        <div style="display:flex;gap:4px;flex-wrap:wrap;">
          <button class="btn btn-sm btn-wa" data-name="${encodeURIComponent(o.customer_name)}" data-code="${o.order_code||'#'+o.id}" onclick="openWAFromBtn(this)">💬</button>
          <button class="btn btn-sm btn-info" onclick="viewOrder(${o.id})">View</button>
          <button class="btn btn-sm btn-danger" onclick="deleteOrder(${o.id})">✕</button>
        </div>
      </td>
    </tr>`;
  }).join('');
}

async function viewOrder(id){
  const res=await api('orders?status=all');
  const o=res.data?.find(x=>x.id===id);
  if(!o)return;
  const items=Array.isArray(o.items)?o.items:[];
  document.getElementById('modalOrderCode').textContent=o.order_code||'#'+o.id;
  document.getElementById('modalOrderBody').innerHTML=`
    <div class="detail-row"><span class="detail-key">Customer</span><span class="detail-val">${o.customer_name}</span></div>
    <div class="detail-row"><span class="detail-key">Mobile</span><span class="detail-val" style="color:var(--green);cursor:pointer;" onclick="window.open('https://wa.me/88${o.customer_phone}','_blank')">${o.customer_phone}</span></div>
    ${o.customer_email?`<div class="detail-row"><span class="detail-key">Email</span><span class="detail-val">${o.customer_email}</span></div>`:''}
    ${o.customer_address?`<div class="detail-row"><span class="detail-key">Street</span><span class="detail-val">${o.customer_address}</span></div>`:''}
    ${o.city?`<div class="detail-row"><span class="detail-key">City</span><span class="detail-val">${o.city}</span></div>`:''}
    ${o.district?`<div class="detail-row"><span class="detail-key">District</span><span class="detail-val">${o.district}${o.postcode?' — '+o.postcode:''}</span></div>`:''}
    <div class="detail-row"><span class="detail-key">Payment</span><span class="detail-val" style="color:var(--orange);font-weight:700;">${o.payment_method}</span></div>
    <div class="detail-row"><span class="detail-key">Status</span><span class="detail-val"><span class="bdg bdg-${o.status==='done'?'done':o.status==='new'?'new':o.status==='cancel'?'cancel':'pend'}">${o.status}</span></span></div>
    <div class="detail-row"><span class="detail-key">Date</span><span class="detail-val">${o.created_at||'—'}</span></div>
    ${o.coupon_used?`<div class="detail-row"><span class="detail-key">Coupon</span><span class="detail-val" style="color:#27ae60;">${o.coupon_used}</span></div>`:''}
    ${o.notes?`<div class="detail-row"><span class="detail-key">Notes</span><span class="detail-val">${o.notes}</span></div>`:''}
    <div style="margin:14px 0 8px;font-family:'Barlow Condensed',sans-serif;font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);">Items Ordered</div>
    ${items.map(i=>`
      <div class="detail-row">
        <span class="detail-key">${i.name}${i.size?' <span style="color:var(--orange)">('+i.size+')</span>':''} × ${i.qty||1}</span>
        <span class="detail-val">৳${(Number(i.price||0)*Number(i.qty||1)).toLocaleString()}</span>
      </div>`).join('')}
    <div style="border-top:1px solid var(--gray2);margin-top:10px;padding-top:10px;">
      ${o.subtotal?`<div class="detail-row" style="font-size:.78rem;"><span class="detail-key">Subtotal</span><span>৳${Number(o.subtotal||0).toLocaleString()}</span></div>`:''}
      ${Number(o.discount)>0?`<div class="detail-row" style="font-size:.78rem;"><span class="detail-key" style="color:#27ae60;">Discount</span><span style="color:#27ae60;">-৳${Number(o.discount).toLocaleString()}</span></div>`:''}
      <div class="detail-row" style="font-size:.78rem;"><span class="detail-key">Delivery (${o.district?'':'?'} ${Number(o.delivery_charge||80)===80?'Dhaka':'Outside Dhaka'})</span><span>৳${Number(o.delivery_charge||80).toLocaleString()}</span></div>
    </div>
    <div class="detail-row" style="border-top:2px solid var(--orange);margin-top:8px;padding-top:8px;">
      <span class="detail-key" style="color:var(--orange);font-size:.82rem;letter-spacing:.1em;">GRAND TOTAL</span>
      <span class="detail-val" style="font-family:'Bebas Neue',sans-serif;font-size:1.5rem;color:var(--orange);">৳${Number(o.total).toLocaleString()}</span>
    </div>
    <div style="display:flex;gap:10px;margin-top:18px;flex-wrap:wrap;">
      <select class="fsel" style="flex:1;" id="modalStatus">
        <option value="new" ${o.status==='new'?'selected':''}>🔔 New</option>
        <option value="pending" ${o.status==='pending'?'selected':''}>⏳ Pending</option>
        <option value="done" ${o.status==='done'?'selected':''}>✅ Delivered</option>
        <option value="cancel" ${o.status==='cancel'?'selected':''}>❌ Cancelled</option>
      </select>
      <button class="btn btn-primary" onclick="updateStatus(${o.id},document.getElementById('modalStatus').value);loadOrders();closeOrderDetail();">Update Status</button>
      <button class="btn btn-wa" data-name="${encodeURIComponent(o.customer_name)}" data-code="${o.order_code||'#'+o.id}" onclick="openWAFromBtn(this)">💬 WhatsApp</button>
    </div>`;
  document.getElementById('orderDetailModal').classList.add('open');
}
function closeOrderDetail(){document.getElementById('orderDetailModal').classList.remove('open');}
function openWA(name,code){
  window.open('https://wa.me/88'+waNum+'?text='+encodeURIComponent('Hi '+name+'! Your ADAM order '+code+' update: '),'_blank');
}
function openWAFromBtn(btn){
  const name=decodeURIComponent(btn.dataset.name||'Customer');
  const code=btn.dataset.code||'';
  openWA(name,code);
}
function openWACust(btn){
  const phone=btn.dataset.phone;
  const name=decodeURIComponent(btn.dataset.name||'');
  window.open('https://wa.me/88'+phone+'?text='+encodeURIComponent('Hello '+name+'! We have new arrivals at ADAM! Check out: '+window.location.origin),'_blank');
}

async function updateStatus(id,status){
  const res=await api('orders/'+id,'PUT',{status});
  if(res.success){showToast('✓ Status updated to: '+status);}
  else showToast('❌ '+(res.error||'Update failed'));
}
async function deleteOrder(id){
  if(!confirm('Delete this order?'))return;
  await api('orders/'+id,'DELETE');
  loadOrders();showToast('Order deleted.');
}
async function addSampleOrder(){
  const names=['Tariq M.','Billal H.','Sumon K.','Rafi A.','Imran S.'];
  const pays=['bKash','Nagad','COD','WhatsApp'];
  const n=names[Math.floor(Math.random()*names.length)];
  await api('orders','POST',{
    customer_name:n,customer_phone:'017'+Math.floor(10000000+Math.random()*89999999),
    address:'Dhaka, Bangladesh',
    items:[{name:'Classic Black Tee',qty:1,price:699},{name:'Track Pants',qty:1,price:999}],
    total:1698,payment_method:pays[Math.floor(Math.random()*pays.length)],
  });
  loadOrders();showToast('✓ Sample order added!');
}

// ===== PRODUCTS =====
async function loadProducts(){
  const res=await api('products?active=all');
  const list=res.data||[];
  document.getElementById('prodCount').textContent=list.length;
  document.getElementById('prodGrid').innerHTML=list.length?list.map(p=>`
    <div class="prod-card">
      <div class="prod-thumb">${p.images&&p.images[0]?`<img src="${p.images[0]}" alt="${p.name}">`:p.emoji||'👕'}</div>
      <div class="prod-info">
        <div class="prod-name">${p.name}</div>
        <div class="prod-price">৳${Number(p.price).toLocaleString()}</div>
        <div style="font-size:.67rem;color:var(--gray);margin-bottom:5px;">${p.cat}${p.badge?' · '+p.badge:''}${p.active==0?' · HIDDEN':''}</div>
        ${p.sizes&&p.sizes.length?`<div style="display:flex;gap:4px;flex-wrap:wrap;margin-bottom:7px;">${p.sizes.map(s=>`<span style="background:rgba(255,107,0,.1);border:1px solid rgba(255,107,0,.25);color:var(--orange);padding:1px 6px;font-family:'Barlow Condensed',sans-serif;font-size:.6rem;letter-spacing:.06em;text-transform:uppercase;">${s}</span>`).join('')}</div>`:'<div style="font-size:.65rem;color:var(--gray);margin-bottom:7px;">No sizes set</div>'}
        <div style="display:flex;gap:5px;flex-wrap:wrap;">
          <button class="btn btn-sm btn-info" onclick="editProduct(${p.id})">Edit</button>
          <button class="btn btn-sm btn-danger" onclick="deleteProd(${p.id})">Delete</button>
        </div>
      </div>
    </div>`).join(''):'<div style="color:var(--gray);padding:20px;grid-column:1/-1;">No products yet. Add one!</div>';
}

// ============================================================
//  MULTI-IMAGE UPLOAD — full gallery with reorder, primary, delete
// ============================================================
async function handleUpload(files){
  if(!files||!files.length) return;
  const fileArr = Array.from(files).filter(f=>f.type.startsWith('image/'));
  if(!fileArr.length){ showToast('Please select image files only!'); return; }
  showToast(`Uploading ${fileArr.length} image(s)...`);

  // Add placeholder slots for each file being uploaded
  const startIdx = uploadedImages.length;
  fileArr.forEach((_,i)=>{ uploadedImages.push(null); }); // null = uploading
  renderGallery();

  let successCount = 0;
  await Promise.all(fileArr.map(async (file, i) => {
    const realIdx = startIdx + i;
    try {
      // Compress large images client-side before upload
      const compressed = await compressImage(file, 1200, 0.85);
      const fd = new FormData();
      fd.append('image', compressed, file.name);
      const res  = await fetch(API+'/upload', {method:'POST', credentials:'include', body:fd});
      const data = await res.json();
      if(data.success){
        uploadedImages[realIdx] = data.data.url;
        successCount++;
      } else {
        uploadedImages.splice(realIdx, 1);
        showToast('❌ '+(data.error||'Upload failed: '+file.name));
      }
    } catch(e) {
      uploadedImages.splice(realIdx, 1);
      showToast('❌ Error uploading: '+file.name);
    }
    renderGallery();
  }));

  // Remove any remaining nulls
  uploadedImages = uploadedImages.filter(x=>x!==null);
  renderGallery();
  if(successCount > 0) showToast(`✓ ${successCount} image(s) uploaded successfully!`);
  // Reset file input so same files can be re-selected
  document.getElementById('imgInput').value = '';
}

// Client-side image compression
function compressImage(file, maxWidth, quality){
  return new Promise(resolve=>{
    if(file.size < 500*1024){ resolve(file); return; } // skip if under 500KB
    const reader = new FileReader();
    reader.onload = e=>{
      const img = new Image();
      img.onload = ()=>{
        const canvas = document.createElement('canvas');
        let w=img.width, h=img.height;
        if(w>maxWidth){ h=Math.round(h*(maxWidth/w)); w=maxWidth; }
        canvas.width=w; canvas.height=h;
        canvas.getContext('2d').drawImage(img,0,0,w,h);
        canvas.toBlob(blob=>resolve(new File([blob],file.name,{type:'image/jpeg'})),'image/jpeg',quality);
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
}

function renderGallery(){
  const gallery = document.getElementById('imgGallery');
  const actions = document.getElementById('galleryActions');
  const countEl = document.getElementById('imgCount');

  const realImages = uploadedImages.filter(x=>x!==null);
  if(countEl) countEl.textContent = realImages.length;
  if(actions) actions.style.display = uploadedImages.length ? 'flex' : 'none';

  if(!uploadedImages.length){
    gallery.innerHTML='';
    return;
  }

  gallery.innerHTML = uploadedImages.map((src,i)=>{
    if(src === null){
      // Uploading placeholder
      return `<div class="img-gallery-item">
        <div class="img-uploading">
          <div class="upload-progress-bar"><div class="upload-progress-fill"></div></div>
          <div class="upload-progress-txt">Uploading...</div>
        </div>
      </div>`;
    }
    const isPrimary = i===0;
    return `<div class="img-gallery-item ${isPrimary?'primary-img':''}" id="imgItem${i}" draggable="true"
      ondragstart="dragStart(event,${i})" ondragover="dragOver(event)" ondrop="dropImg(event,${i})" ondragleave="dragLeave(event)">
      ${isPrimary?'<div class="img-primary-badge">⭐ Main</div>':''}
      <div class="img-num-badge">${i+1}</div>
      <img src="${src}" alt="product ${i+1}" loading="lazy">
      <div class="img-overlay">
        ${!isPrimary?`<button class="img-overlay-btn" onclick="setMainImg(${i})">⭐ Set as Main</button>`:''}
        <button class="img-overlay-btn" onclick="previewFullImg('${src}')">🔍 Preview</button>
        ${i>0?`<button class="img-overlay-btn" onclick="moveImgLeft(${i})">← Move Left</button>`:''}
        ${i<uploadedImages.length-1?`<button class="img-overlay-btn" onclick="moveImgRight(${i})">→ Move Right</button>`:''}
        <button class="img-overlay-btn danger" onclick="removeImg(${i})">🗑 Remove</button>
      </div>
    </div>`;
  }).join('');
}

// Drag & drop reorder
let draggedIdx = null;
function dragStart(e,i){ draggedIdx=i; e.dataTransfer.effectAllowed='move'; e.currentTarget.style.opacity='.4'; }
function dragOver(e){ e.preventDefault(); e.dataTransfer.dropEffect='move'; e.currentTarget.style.borderColor='var(--orange)'; }
function dragLeave(e){ e.currentTarget.style.borderColor=''; }
function dropImg(e,targetIdx){
  e.preventDefault(); e.currentTarget.style.borderColor='';
  if(draggedIdx===null||draggedIdx===targetIdx) return;
  const moved = uploadedImages.splice(draggedIdx,1)[0];
  uploadedImages.splice(targetIdx,0,moved);
  draggedIdx=null; renderGallery();
}

function setMainImg(i){
  const img = uploadedImages.splice(i,1)[0];
  uploadedImages.unshift(img);
  renderGallery(); showToast('⭐ Main image updated!');
}
function moveImgLeft(i){
  if(i===0) return;
  [uploadedImages[i-1],uploadedImages[i]]=[uploadedImages[i],uploadedImages[i-1]];
  renderGallery();
}
function moveImgRight(i){
  if(i>=uploadedImages.length-1) return;
  [uploadedImages[i],uploadedImages[i+1]]=[uploadedImages[i+1],uploadedImages[i]];
  renderGallery();
}
function removeImg(i){ uploadedImages.splice(i,1); renderGallery(); showToast('Image removed.'); }
function clearAllImages(){ if(!confirm('Remove all uploaded images?')) return; uploadedImages=[]; renderGallery(); }

function previewFullImg(src){
  const d=document.createElement('div');
  d.style.cssText='position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:zoom-out;padding:20px;';
  d.innerHTML=`<img src="${src}" style="max-width:100%;max-height:90vh;object-fit:contain;border:1px solid var(--gray2);">`;
  d.onclick=()=>d.remove();
  document.body.appendChild(d);
}

// ===== SIZES MANAGEMENT =====
let selectedSizes = [];

function renderSizeBtns(){
  const commonSizes = ['S','M','L','XL','XXL','28','30','32','34','36'];
  const all = [...new Set([...commonSizes, ...selectedSizes])];
  const group = document.getElementById('sizeBtnGroup');
  if(!group) return;
  group.innerHTML = all.map(s => `
    <button type="button"
      class="size-preset-btn"
      style="padding:6px 13px;font-family:'Barlow Condensed',sans-serif;font-size:.8rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;border:1px solid;transition:all .2s;background:${selectedSizes.includes(s)?'var(--orange)':'var(--bg3)'};border-color:${selectedSizes.includes(s)?'var(--orange)':'var(--gray2)'};color:${selectedSizes.includes(s)?'#000':'var(--gray)'};"
      onclick="toggleSizeBtn('${s}')">${s}</button>
  `).join('');
  document.getElementById('np-sizes-hidden').value = selectedSizes.join(', ');
  document.getElementById('sizesPreview').textContent = selectedSizes.length ? selectedSizes.join(', ') : 'None';
}

function toggleSizeBtn(s){
  const idx = selectedSizes.indexOf(s);
  if(idx>-1) selectedSizes.splice(idx,1); else selectedSizes.push(s);
  renderSizeBtns();
}

function addSizeFromSelect(){
  const val = document.getElementById('sizePresetSel').value;
  if(val && !selectedSizes.includes(val)){ selectedSizes.push(val); renderSizeBtns(); }
}

function syncSizesFromInput(val){
  selectedSizes = val.split(',').map(s=>s.trim().toUpperCase()).filter(s=>s.length>0);
  renderSizeBtns();
}

async function saveProduct(){
  const name=document.getElementById('np-name').value.trim();
  const price=parseFloat(document.getElementById('np-price').value);
  if(!name||!price){showToast('Name and price required!');return;}
  const btn=document.getElementById('saveProdBtn');
  btn.innerHTML='<span class="spinner spinner-dark"></span> Saving...';btn.disabled=true;
  const data={
    name,cat:document.getElementById('np-cat').value,price,
    old_price:document.getElementById('np-old').value||null,
    badge:document.getElementById('np-badge').value.toUpperCase()||null,
    emoji:document.getElementById('np-emoji').value||'👕',
    description:document.getElementById('np-desc').value,
    stock:parseInt(document.getElementById('np-stock').value)||100,
    active:parseInt(document.getElementById('np-active').value),
    images:uploadedImages,
    sizes:selectedSizes,
  };
  const id=document.getElementById('edit-id').value;
  const res=id?await api('products/'+id,'PUT',data):await api('products','POST',data);
  btn.innerHTML='+ Save Product';btn.disabled=false;
  if(res.success){showToast(id?'✓ Product updated!':'✓ Product saved to database!');clearProdForm();}
  else showToast('❌ '+(res.error||'Save failed'));
}

async function editProduct(id){
  const res=await api('products?active=all');
  const p=res.data?.find(x=>x.id===id);if(!p)return;
  document.getElementById('np-name').value=p.name;
  document.getElementById('np-cat').value=p.cat;
  document.getElementById('np-price').value=p.price;
  document.getElementById('np-old').value=p.old_price||'';
  document.getElementById('np-badge').value=p.badge||'';
  document.getElementById('np-emoji').value=p.emoji||'👕';
  document.getElementById('np-desc').value=p.description||'';
  document.getElementById('np-stock').value=p.stock||100;
  document.getElementById('np-active').value=p.active;
  document.getElementById('edit-id').value=id;
  uploadedImages=p.images?[...p.images]:[];renderGallery();
  selectedSizes=p.sizes?[...p.sizes]:[];
  setTimeout(()=>renderSizeBtns(),100);
  document.getElementById('addProdTitle').textContent='Edit Product';
  document.getElementById('saveProdBtn').textContent='✓ Update Product';
  nav('addproduct',document.querySelectorAll('.snav a')[3]);
  showToast('Editing: '+p.name);
}

function clearProdForm(){
  ['np-name','np-price','np-old','np-badge','np-desc','np-sizes-hidden'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
  document.getElementById('np-emoji').value='👕';
  document.getElementById('np-stock').value='100';
  document.getElementById('np-active').value='1';
  document.getElementById('edit-id').value='';
  document.getElementById('addProdTitle').textContent='Add Product';
  document.getElementById('saveProdBtn').textContent='+ Save Product';
  uploadedImages=[];renderGallery();
  selectedSizes=[];renderSizeBtns();
}

async function deleteProd(id){
  if(!confirm('Delete this product permanently?'))return;
  await api('products/'+id,'DELETE');
  loadProducts();showToast('Product deleted.');
}

// Drag & drop
document.addEventListener('DOMContentLoaded',()=>{
  const zone=document.getElementById('uploadZone');
  if(!zone)return;
  zone.addEventListener('dragover',e=>{e.preventDefault();zone.classList.add('drag');});
  zone.addEventListener('dragleave',e=>{if(!zone.contains(e.relatedTarget))zone.classList.remove('drag');});
  zone.addEventListener('drop',e=>{
    e.preventDefault();zone.classList.remove('drag');
    handleUpload(e.dataTransfer.files);
  });
  loadDashboard();
  renderSizeBtns();
  // Load WA number from settings
  api('settings?key=whatsapp').then(r=>{if(r.success&&r.data.value)waNum=r.data.value;});
});

// ===== COUPONS =====
async function loadCoupons(){
  const res=await api('coupons');
  document.getElementById('coupList').innerHTML=res.data?.length?res.data.map(c=>`
    <div class="coup-item">
      <div>
        <div class="coup-code">${c.code}</div>
        <div class="coup-info">${c.type==='free_delivery'?'Free Delivery':c.type==='percent'?c.value+'% off':'৳'+c.value+' off'} · Min ${c.min_items} item(s) · Used: ${c.uses||0}x</div>
      </div>
      <div style="display:flex;gap:7px;align-items:center;flex-wrap:wrap;">
        <span class="bdg ${c.active==1?'bdg-done':'bdg-cancel'}">${c.active==1?'Active':'Off'}</span>
        <button class="btn btn-sm btn-outline" onclick="toggleCoup(${c.id},${c.active==1?0:1})">${c.active==1?'Disable':'Enable'}</button>
        <button class="btn btn-sm btn-danger" onclick="deleteCoup(${c.id})">✕</button>
      </div>
    </div>`).join(''):'<p style="color:var(--gray);padding:14px 0;font-size:.82rem;">No coupons yet.</p>';
}
async function addCoupon(){
  const code=document.getElementById('cp-code').value.trim().toUpperCase();
  if(!code){showToast('Enter coupon code!');return;}
  const res=await api('coupons','POST',{code,type:document.getElementById('cp-type').value,value:document.getElementById('cp-val').value||0,min_items:document.getElementById('cp-min').value||1});
  if(res.success){document.getElementById('cp-code').value='';loadCoupons();showToast('✓ Coupon created!');}
  else showToast('❌ '+(res.error||'Failed'));
}
async function toggleCoup(id,active){await api('coupons/'+id,'PUT',{active});loadCoupons();}
async function deleteCoup(id){if(!confirm('Delete coupon?'))return;await api('coupons/'+id,'DELETE');loadCoupons();}

// ===== CUSTOMERS =====
async function loadCustomers(){
  const res=await api('customers');
  const list=res.data||[];
  document.getElementById('custCount').textContent=list.length;
  document.getElementById('custBody').innerHTML=list.length?list.map((c,i)=>`
    <tr>
      <td style="color:var(--gray);">${i+1}</td>
      <td style="font-weight:600;">${c.name||'—'}</td>
      <td style="color:var(--green);cursor:pointer;" onclick="window.open('https://wa.me/88${c.phone}','_blank')">${c.phone}</td>
      <td style="color:var(--gray);">${c.location||'—'}</td>
      <td><span class="bdg bdg-pend">${c.total_orders||0}</span></td>
      <td style="font-family:'Bebas Neue',sans-serif;font-size:1rem;color:var(--orange);">৳${Number(c.total_spent||0).toLocaleString()}</td>
      <td style="display:flex;gap:4px;">
        <button class="btn btn-sm btn-wa" data-phone="${c.phone}" data-name="${encodeURIComponent(c.name||'')}" onclick="openWACust(this)">💬</button>
        <button class="btn btn-sm btn-danger" onclick="deleteCust(${c.id})">✕</button>
      </td>
    </tr>`).join(''):'<tr><td colspan="7" style="text-align:center;color:var(--gray);padding:26px;">No customers yet.</td></tr>';
}
async function deleteCust(id){if(!confirm('Delete customer?'))return;await api('customers/'+id,'DELETE');loadCustomers();}

// ===== REVIEWS =====
async function loadReviews(){
  const res=await api('reviews?status=all');
  document.getElementById('revBody').innerHTML=res.data?.length?res.data.map(r=>`
    <tr>
      <td style="font-weight:600;">${r.customer_name}</td>
      <td style="color:var(--gray);font-size:.75rem;">${r.product_name||'—'}</td>
      <td style="color:var(--orange);">${'★'.repeat(r.rating)}</td>
      <td style="font-size:.75rem;max-width:180px;">${r.review_text}</td>
      <td><span class="bdg ${r.status==='approved'?'bdg-done':'bdg-pend'}">${r.status}</span></td>
      <td style="display:flex;gap:4px;">
        <button class="btn btn-sm btn-success" onclick="updateRev(${r.id},'approved')" ${r.status==='approved'?'disabled':''}>Approve</button>
        <button class="btn btn-sm btn-danger" onclick="deleteRev(${r.id})">Delete</button>
      </td>
    </tr>`).join(''):'<tr><td colspan="6" style="text-align:center;color:var(--gray);padding:26px;">No reviews yet.</td></tr>';
}
async function updateRev(id,status){await api('reviews/'+id,'PUT',{status});loadReviews();}
async function deleteRev(id){await api('reviews/'+id,'DELETE');loadReviews();}

// ===== SETTINGS =====
async function saveSettings(){
  const wa=document.getElementById('st-wa').value.trim();
  const res=await api('settings','POST',{store_name:document.getElementById('st-name').value,whatsapp:wa,store_desc:document.getElementById('st-desc').value});
  if(res.success){waNum=wa;showToast('✓ Settings saved!');}
  else showToast('❌ '+(res.error||'Failed'));
}
async function changePass(){
  const old=document.getElementById('old-p').value;
  const np=document.getElementById('new-p').value;
  if(np.length<4){showToast('Password too short!');return;}
  const res=await api('settings','POST',{admin_pass:np,old_pass:old});
  if(res.success){document.getElementById('old-p').value='';document.getElementById('new-p').value='';showToast('✓ Password changed!');}
  else showToast('❌ '+(res.error||'Wrong current password'));
}

function showToast(msg){
  const t=document.getElementById('toast');
  t.textContent=msg;t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'),2800);
}
</script>
</body>
</html>
