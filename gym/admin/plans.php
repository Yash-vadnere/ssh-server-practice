<?php
$host='localhost'; $user='gymadmin'; $pass='Gym@2026'; $db='gymdb';
$conn = new mysqli($host,$user,$pass,$db);
$basic = $conn->query("SELECT COUNT(*) as c FROM members WHERE plan='Basic'")->fetch_assoc()['c'];
$pro   = $conn->query("SELECT COUNT(*) as c FROM members WHERE plan='Pro'")->fetch_assoc()['c'];
$elite = $conn->query("SELECT COUNT(*) as c FROM members WHERE plan='Elite'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Plans - Unique Fitness Gym Admin</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
:root { --black:#0a0a0a; --dark:#111; --card:#1a1a1a; --gold:#c9a84c; --white:#f5f5f0; --gray:#888880; }
body { font-family:'Segoe UI',sans-serif; background:var(--dark); color:var(--white); display:flex; min-height:100vh; }
.sidebar { width:240px; background:var(--black); border-right:1px solid rgba(201,168,76,0.15); padding:24px 0; position:fixed; height:100vh; }
.sidebar-logo { font-size:18px; font-weight:700; color:var(--gold); padding:0 24px 24px; border-bottom:1px solid rgba(255,255,255,0.07); letter-spacing:2px; }
.sidebar-logo span { color:var(--white); }
.menu-label { font-size:11px; color:var(--gray); letter-spacing:2px; text-transform:uppercase; padding:16px 24px 8px; }
.menu-item { display:flex; align-items:center; gap:12px; padding:14px 24px; color:var(--gray); text-decoration:none; font-size:14px; }
.menu-item:hover, .menu-item.active { color:var(--gold); background:rgba(201,168,76,0.07); border-left:3px solid var(--gold); }
.menu-item .icon { font-size:18px; width:20px; }
.main { margin-left:240px; flex:1; padding:32px; }
.page-title { font-size:28px; font-weight:800; margin-bottom:4px; }
.page-sub { color:var(--gray); font-size:14px; margin-bottom:32px; }
.plans-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:24px; }
.plan-card { background:var(--card); padding:32px; border:1px solid rgba(255,255,255,0.07); }
.plan-card.featured { border-color:var(--gold); }
.plan-name { font-size:13px; color:var(--gray); letter-spacing:2px; text-transform:uppercase; margin-bottom:12px; }
.plan-price { font-size:44px; font-weight:900; color:var(--white); margin-bottom:4px; }
.plan-members { font-size:14px; color:var(--gold); margin-bottom:20px; }
.plan-revenue { font-size:13px; color:var(--gray); padding:12px 0; border-top:1px solid rgba(255,255,255,0.07); }
.plan-revenue span { color:var(--white); font-weight:700; }
.features { list-style:none; margin:16px 0; }
.features li { padding:8px 0; font-size:14px; color:var(--gray); border-bottom:1px solid rgba(255,255,255,0.04); display:flex; align-items:center; gap:8px; }
.features li::before { content:'✓'; color:var(--gold); font-weight:700; }
</style>
</head>
<body>
<div class="sidebar">
  <div class="sidebar-logo">UNIQUE <span>FITNESS</span></div>
  <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="dashboard.php" class="menu-item"><span class="icon">📊</span> Dashboard</a>
    <a href="members.php" class="menu-item"><span class="icon">👥</span> Members</a>
    <a href="enquiries.php" class="menu-item"><span class="icon">📩</span> Enquiries</a>
    <a href="plans.php" class="menu-item active"><span class="icon">💳</span> Plans</a>
    <div class="menu-label">Site</div>
    <a href="/index.html" class="menu-item"><span class="icon">🌐</span> View Website</a>
  </div>
</div>
<div class="main">
  <div class="page-title">Membership Plans</div>
  <div class="page-sub">Overview of all plans and member counts</div>
  <div class="plans-grid">
    <div class="plan-card">
      <div class="plan-name">Basic</div>
      <div class="plan-price">₹799</div>
      <div class="plan-members">👥 <?= $basic ?> members</div>
      <ul class="features">
        <li>Gym floor access</li>
        <li>Locker room access</li>
        <li>1 fitness assessment</li>
        <li>Group classes (2/week)</li>
      </ul>
      <div class="plan-revenue">Monthly Revenue: <span>₹<?= number_format($basic*799) ?></span></div>
    </div>
    <div class="plan-card featured">
      <div class="plan-name">Pro ⭐ Most Popular</div>
      <div class="plan-price">₹1,499</div>
      <div class="plan-members">👥 <?= $pro ?> members</div>
      <ul class="features">
        <li>All Basic features</li>
        <li>Unlimited group classes</li>
        <li>4 PT sessions/month</li>
        <li>Diet consultation</li>
        <li>Steam & sauna access</li>
      </ul>
      <div class="plan-revenue">Monthly Revenue: <span>₹<?= number_format($pro*1499) ?></span></div>
    </div>
    <div class="plan-card">
      <div class="plan-name">Elite</div>
      <div class="plan-price">₹2,499</div>
      <div class="plan-members">👥 <?= $elite ?> members</div>
      <ul class="features">
        <li>All Pro features</li>
        <li>Unlimited PT sessions</li>
        <li>Custom meal plan</li>
        <li>Body composition tracking</li>
        <li>Guest passes (2/month)</li>
      </ul>
      <div class="plan-revenue">Monthly Revenue: <span>₹<?= number_format($elite*2499) ?></span></div>
    </div>
  </div>
</div>
</body>
</html>
