<?php
$host='localhost'; $user='gymadmin'; $pass='Gym@2026'; $db='gymdb';
$conn = new mysqli($host,$user,$pass,$db);

if(isset($_GET['delete'])){
  $id=(int)$_GET['delete'];
  $conn->query("DELETE FROM enquiries WHERE id=$id");
  header("Location: enquiries.php"); exit;
}

$enquiries = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC");
$total = $conn->query("SELECT COUNT(*) as c FROM enquiries")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Enquiries - Unique Fitness Gym Admin</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
:root { --black:#0a0a0a; --dark:#111; --card:#1a1a1a; --gold:#c9a84c; --white:#f5f5f0; --gray:#888880; --red:#e05252; }
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
.table-card { background:var(--card); border:1px solid rgba(255,255,255,0.07); }
.table-header { padding:20px 24px; border-bottom:1px solid rgba(255,255,255,0.07); font-size:16px; font-weight:600; }
table { width:100%; border-collapse:collapse; }
th { padding:12px 20px; text-align:left; font-size:11px; color:var(--gray); letter-spacing:1px; text-transform:uppercase; border-bottom:1px solid rgba(255,255,255,0.07); }
td { padding:14px 20px; font-size:14px; border-bottom:1px solid rgba(255,255,255,0.04); vertical-align:top; }
.badge { padding:4px 10px; border-radius:3px; font-size:11px; font-weight:700; background:rgba(201,168,76,0.15); color:var(--gold); }
.btn-red { background:var(--red); color:var(--white); padding:6px 12px; font-size:11px; border:none; cursor:pointer; font-weight:700; text-decoration:none; }
.msg-text { color:var(--gray); font-size:13px; max-width:200px; }
</style>
</head>
<body>
<div class="sidebar">
  <div class="sidebar-logo">UNIQUE <span>FITNESS</span></div>
  <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="dashboard.php" class="menu-item"><span class="icon">📊</span> Dashboard</a>
    <a href="members.php" class="menu-item"><span class="icon">👥</span> Members</a>
    <a href="enquiries.php" class="menu-item active"><span class="icon">📩</span> Enquiries</a>
    <a href="plans.php" class="menu-item"><span class="icon">💳</span> Plans</a>
    <div class="menu-label">Site</div>
    <a href="/index.html" class="menu-item"><span class="icon">🌐</span> View Website</a>
  </div>
</div>
<div class="main">
  <div class="page-title">Enquiries</div>
  <div class="page-sub">Total: <?= $total ?> enquiries received</div>
  <div class="table-card">
    <div class="table-header">All Enquiries</div>
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Program</th><th>Message</th><th>Date</th><th>Action</th></tr></thead>
      <tbody>
        <?php while($row=$enquiries->fetch_assoc()): ?>
        <tr>
          <td style="font-weight:500"><?= htmlspecialchars($row['name']) ?></td>
          <td style="color:var(--gray)"><?= htmlspecialchars($row['email']) ?></td>
          <td style="color:var(--gray)"><?= htmlspecialchars($row['phone']) ?></td>
          <td><span class="badge"><?= htmlspecialchars($row['program']) ?></span></td>
          <td><div class="msg-text"><?= htmlspecialchars(substr($row['message'],0,80)) ?>...</div></td>
          <td style="color:var(--gray);font-size:12px"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
          <td><a href="?delete=<?= $row['id'] ?>" class="btn-red" onclick="return confirm('Delete this enquiry?')">Delete</a></td>
        </tr>
        <?php endwhile; ?>
        <?php if($total==0): ?>
        <tr><td colspan="7" style="text-align:center;color:var(--gray);padding:40px;">No enquiries yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
