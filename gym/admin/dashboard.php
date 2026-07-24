<?php
$host = 'localhost';
$user = 'gymadmin';
$pass = 'Gym@2026';
$db   = 'gymdb';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$total_members   = $conn->query("SELECT COUNT(*) as c FROM members")->fetch_assoc()['c'];
$active_members  = $conn->query("SELECT COUNT(*) as c FROM members WHERE status='active'")->fetch_assoc()['c'];
$total_enquiries = $conn->query("SELECT COUNT(*) as c FROM enquiries")->fetch_assoc()['c'];
$basic  = $conn->query("SELECT COUNT(*) as c FROM members WHERE plan='Basic'")->fetch_assoc()['c'];
$pro    = $conn->query("SELECT COUNT(*) as c FROM members WHERE plan='Pro'")->fetch_assoc()['c'];
$elite  = $conn->query("SELECT COUNT(*) as c FROM members WHERE plan='Elite'")->fetch_assoc()['c'];
$recent = $conn->query("SELECT * FROM members ORDER BY join_date DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Unique Fitness Gym</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
:root { --black:#0a0a0a; --dark:#111; --card:#1a1a1a; --gold:#c9a84c; --white:#f5f5f0; --gray:#888880; --green:#2a9d5c; --red:#e05252; }
body { font-family:'Segoe UI',sans-serif; background:var(--dark); color:var(--white); display:flex; min-height:100vh; }

/* SIDEBAR */
.sidebar { width:240px; background:var(--black); border-right:1px solid rgba(201,168,76,0.15); padding:24px 0; position:fixed; height:100vh; }
.sidebar-logo { font-size:18px; font-weight:700; color:var(--gold); padding:0 24px 24px; border-bottom:1px solid rgba(255,255,255,0.07); letter-spacing:2px; }
.sidebar-logo span { color:var(--white); }
.sidebar-menu { margin-top:24px; }
.menu-item { display:flex; align-items:center; gap:12px; padding:14px 24px; color:var(--gray); text-decoration:none; font-size:14px; transition:all 0.2s; }
.menu-item:hover, .menu-item.active { color:var(--gold); background:rgba(201,168,76,0.07); border-left:3px solid var(--gold); }
.menu-item .icon { font-size:18px; width:20px; }
.menu-label { font-size:11px; color:var(--gray); letter-spacing:2px; text-transform:uppercase; padding:16px 24px 8px; }

/* MAIN */
.main { margin-left:240px; flex:1; padding:32px; }
.page-header { margin-bottom:32px; }
.page-title { font-size:28px; font-weight:800; }
.page-sub { color:var(--gray); font-size:14px; margin-top:4px; }

/* STATS */
.stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:32px; }
.stat-card { background:var(--card); padding:24px; border:1px solid rgba(255,255,255,0.07); }
.stat-icon { font-size:28px; margin-bottom:12px; }
.stat-num { font-size:36px; font-weight:900; color:var(--gold); }
.stat-label { font-size:12px; color:var(--gray); letter-spacing:1px; text-transform:uppercase; margin-top:4px; }

/* PLAN BARS */
.plan-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:32px; }
.plan-card { background:var(--card); padding:24px; border:1px solid rgba(255,255,255,0.07); }
.plan-card h3 { font-size:16px; font-weight:600; margin-bottom:20px; color:var(--white); }
.plan-row { margin-bottom:16px; }
.plan-row-header { display:flex; justify-content:space-between; font-size:13px; margin-bottom:6px; }
.plan-name-label { color:var(--gray); }
.plan-count { color:var(--gold); font-weight:700; }
.plan-bar { height:6px; background:rgba(255,255,255,0.08); border-radius:3px; }
.plan-fill { height:6px; background:var(--gold); border-radius:3px; transition:width 0.8s; }

/* RECENT TABLE */
.table-card { background:var(--card); border:1px solid rgba(255,255,255,0.07); }
.table-header { padding:20px 24px; border-bottom:1px solid rgba(255,255,255,0.07); display:flex; justify-content:space-between; align-items:center; }
.table-title { font-size:16px; font-weight:600; }
.btn-sm { background:var(--gold); color:var(--black); padding:8px 16px; border:none; font-size:12px; font-weight:700; letter-spacing:1px; text-transform:uppercase; cursor:pointer; text-decoration:none; }
table { width:100%; border-collapse:collapse; }
th { padding:12px 24px; text-align:left; font-size:11px; color:var(--gray); letter-spacing:1px; text-transform:uppercase; border-bottom:1px solid rgba(255,255,255,0.07); }
td { padding:14px 24px; font-size:14px; border-bottom:1px solid rgba(255,255,255,0.04); }
.badge { padding:4px 10px; border-radius:3px; font-size:11px; font-weight:700; letter-spacing:1px; }
.badge-active { background:rgba(42,157,92,0.15); color:var(--green); }
.badge-inactive { background:rgba(224,82,82,0.15); color:var(--red); }
.badge-basic { background:rgba(136,136,128,0.15); color:var(--gray); }
.badge-pro { background:rgba(201,168,76,0.15); color:var(--gold); }
.badge-elite { background:rgba(201,168,76,0.25); color:var(--gold); }
</style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-logo">UNIQUE <span>FITNESS</span></div>
  <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="dashboard.php" class="menu-item active"><span class="icon">📊</span> Dashboard</a>
    <a href="members.php" class="menu-item"><span class="icon">👥</span> Members</a>
    <a href="enquiries.php" class="menu-item"><span class="icon">📩</span> Enquiries</a>
    <a href="plans.php" class="menu-item"><span class="icon">💳</span> Plans</a>
    <div class="menu-label">Site</div>
    <a href="/index.html" class="menu-item"><span class="icon">🌐</span> View Website</a>
  </div>
</div>

<div class="main">
  <div class="page-header">
    <div class="page-title">Dashboard</div>
    <div class="page-sub">Welcome back! Here's what's happening at Unique Fitness Gym.</div>
  </div>

  <div class="stats-grid">
    <div class="stat-card"><div class="stat-icon">👥</div><div class="stat-num"><?= $total_members ?></div><div class="stat-label">Total Members</div></div>
    <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-num"><?= $active_members ?></div><div class="stat-label">Active Members</div></div>
    <div class="stat-card"><div class="stat-icon">📩</div><div class="stat-num"><?= $total_enquiries ?></div><div class="stat-label">Enquiries</div></div>
    <div class="stat-card"><div class="stat-icon">💰</div><div class="stat-num">₹<?= number_format(($basic*799)+($pro*1499)+($elite*2499)) ?></div><div class="stat-label">Monthly Revenue</div></div>
  </div>

  <div class="plan-grid">
    <div class="plan-card">
      <h3>Members by Plan</h3>
      <?php $max = max($basic,$pro,$elite,1); ?>
      <div class="plan-row">
        <div class="plan-row-header"><span class="plan-name-label">Basic (₹799)</span><span class="plan-count"><?= $basic ?></span></div>
        <div class="plan-bar"><div class="plan-fill" style="width:<?= ($basic/$max)*100 ?>%"></div></div>
      </div>
      <div class="plan-row">
        <div class="plan-row-header"><span class="plan-name-label">Pro (₹1,499)</span><span class="plan-count"><?= $pro ?></span></div>
        <div class="plan-bar"><div class="plan-fill" style="width:<?= ($pro/$max)*100 ?>%"></div></div>
      </div>
      <div class="plan-row">
        <div class="plan-row-header"><span class="plan-name-label">Elite (₹2,499)</span><span class="plan-count"><?= $elite ?></span></div>
        <div class="plan-bar"><div class="plan-fill" style="width:<?= ($elite/$max)*100 ?>%"></div></div>
      </div>
    </div>
    <div class="plan-card">
      <h3>Quick Actions</h3>
      <a href="members.php?action=add" class="btn-sm" style="display:block;text-align:center;margin-bottom:12px;">+ Add New Member</a>
      <a href="enquiries.php" class="btn-sm" style="display:block;text-align:center;background:transparent;color:var(--gold);border:1px solid var(--gold);">View Enquiries</a>
    </div>
  </div>

  <div class="table-card">
    <div class="table-header">
      <div class="table-title">Recent Members</div>
      <a href="members.php" class="btn-sm">View All</a>
    </div>
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Plan</th><th>Join Date</th><th>Status</th></tr></thead>
      <tbody>
        <?php while($row = $recent->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td style="color:var(--gray)"><?= htmlspecialchars($row['email']) ?></td>
          <td style="color:var(--gray)"><?= htmlspecialchars($row['phone']) ?></td>
          <td><span class="badge badge-<?= strtolower($row['plan']) ?>"><?= $row['plan'] ?></span></td>
          <td style="color:var(--gray)"><?= $row['join_date'] ?></td>
          <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
        </tr>
        <?php endwhile; ?>
        <?php if($total_members == 0): ?>
        <tr><td colspan="6" style="text-align:center;color:var(--gray);padding:32px;">No members yet. <a href="members.php?action=add" style="color:var(--gold);">Add first member →</a></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
