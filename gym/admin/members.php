<?php
$host='localhost'; $user='gymadmin'; $pass='Gym@2026'; $db='gymdb';
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$msg = '';

// ADD MEMBER
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && $_POST['action']==='add'){
  $name  = $conn->real_escape_string($_POST['name']);
  $email = $conn->real_escape_string($_POST['email']);
  $phone = $conn->real_escape_string($_POST['phone']);
  $plan  = $conn->real_escape_string($_POST['plan']);
  $date  = $conn->real_escape_string($_POST['join_date']);
  $conn->query("INSERT INTO members (name,email,phone,plan,join_date,status) VALUES ('$name','$email','$phone','$plan','$date','active')");
  $msg = 'success';
}

// DELETE
if(isset($_GET['delete'])){
  $id = (int)$_GET['delete'];
  $conn->query("DELETE FROM members WHERE id=$id");
  header("Location: members.php"); exit;
}

// TOGGLE STATUS
if(isset($_GET['toggle'])){
  $id = (int)$_GET['toggle'];
  $cur = $conn->query("SELECT status FROM members WHERE id=$id")->fetch_assoc()['status'];
  $new = $cur==='active' ? 'inactive' : 'active';
  $conn->query("UPDATE members SET status='$new' WHERE id=$id");
  header("Location: members.php"); exit;
}

$members = $conn->query("SELECT * FROM members ORDER BY id DESC");
$total   = $conn->query("SELECT COUNT(*) as c FROM members")->fetch_assoc()['c'];
$action  = isset($_GET['action']) ? $_GET['action'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Members - Unique Fitness Gym Admin</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
:root { --black:#0a0a0a; --dark:#111; --card:#1a1a1a; --gold:#c9a84c; --white:#f5f5f0; --gray:#888880; --green:#2a9d5c; --red:#e05252; }
body { font-family:'Segoe UI',sans-serif; background:var(--dark); color:var(--white); display:flex; min-height:100vh; }
.sidebar { width:240px; background:var(--black); border-right:1px solid rgba(201,168,76,0.15); padding:24px 0; position:fixed; height:100vh; }
.sidebar-logo { font-size:18px; font-weight:700; color:var(--gold); padding:0 24px 24px; border-bottom:1px solid rgba(255,255,255,0.07); letter-spacing:2px; }
.sidebar-logo span { color:var(--white); }
.menu-label { font-size:11px; color:var(--gray); letter-spacing:2px; text-transform:uppercase; padding:16px 24px 8px; }
.menu-item { display:flex; align-items:center; gap:12px; padding:14px 24px; color:var(--gray); text-decoration:none; font-size:14px; }
.menu-item:hover, .menu-item.active { color:var(--gold); background:rgba(201,168,76,0.07); border-left:3px solid var(--gold); }
.menu-item .icon { font-size:18px; width:20px; }
.main { margin-left:240px; flex:1; padding:32px; }
.page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:32px; }
.page-title { font-size:28px; font-weight:800; }
.page-sub { color:var(--gray); font-size:14px; margin-top:4px; }
.btn { background:var(--gold); color:var(--black); padding:12px 24px; border:none; font-size:13px; font-weight:700; letter-spacing:1px; text-transform:uppercase; cursor:pointer; text-decoration:none; display:inline-block; }
.btn-outline { background:transparent; color:var(--gold); border:1px solid var(--gold); }
.btn-red { background:var(--red); color:var(--white); padding:6px 12px; font-size:11px; border:none; cursor:pointer; font-weight:700; }
.btn-gray { background:rgba(255,255,255,0.1); color:var(--white); padding:6px 12px; font-size:11px; border:none; cursor:pointer; font-weight:700; }

/* MODAL FORM */
.modal-bg { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:100; align-items:center; justify-content:center; }
.modal-bg.open { display:flex; }
.modal { background:var(--card); padding:40px; width:500px; border:1px solid rgba(201,168,76,0.2); }
.modal h2 { font-size:20px; margin-bottom:24px; color:var(--gold); }
.form-group { margin-bottom:16px; }
.form-group label { display:block; font-size:12px; color:var(--gray); letter-spacing:1px; text-transform:uppercase; margin-bottom:6px; }
.form-group input, .form-group select { width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:var(--white); padding:12px 14px; font-size:14px; font-family:inherit; outline:none; }
.form-row { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.success-msg { background:rgba(42,157,92,0.15); border:1px solid var(--green); color:var(--green); padding:12px 16px; margin-bottom:20px; font-size:14px; }

/* TABLE */
.table-card { background:var(--card); border:1px solid rgba(255,255,255,0.07); }
.table-header { padding:20px 24px; border-bottom:1px solid rgba(255,255,255,0.07); display:flex; justify-content:space-between; align-items:center; }
table { width:100%; border-collapse:collapse; }
th { padding:12px 20px; text-align:left; font-size:11px; color:var(--gray); letter-spacing:1px; text-transform:uppercase; border-bottom:1px solid rgba(255,255,255,0.07); }
td { padding:14px 20px; font-size:14px; border-bottom:1px solid rgba(255,255,255,0.04); }
.badge { padding:4px 10px; border-radius:3px; font-size:11px; font-weight:700; }
.badge-active { background:rgba(42,157,92,0.15); color:var(--green); }
.badge-inactive { background:rgba(224,82,82,0.15); color:var(--red); }
.badge-Basic { background:rgba(136,136,128,0.15); color:var(--gray); }
.badge-Pro { background:rgba(201,168,76,0.15); color:var(--gold); }
.badge-Elite { background:rgba(201,168,76,0.25); color:var(--gold); }
.actions { display:flex; gap:8px; }
</style>
</head>
<body>

<div class="sidebar">
  <div class="sidebar-logo">UNIQUE <span>FITNESS</span></div>
  <div class="sidebar-menu">
    <div class="menu-label">Main</div>
    <a href="dashboard.php" class="menu-item"><span class="icon">📊</span> Dashboard</a>
    <a href="members.php" class="menu-item active"><span class="icon">👥</span> Members</a>
    <a href="enquiries.php" class="menu-item"><span class="icon">📩</span> Enquiries</a>
    <a href="plans.php" class="menu-item"><span class="icon">💳</span> Plans</a>
    <div class="menu-label">Site</div>
    <a href="/index.html" class="menu-item"><span class="icon">🌐</span> View Website</a>
  </div>
</div>

<div class="main">
  <div class="page-header">
    <div>
      <div class="page-title">Members</div>
      <div class="page-sub">Total: <?= $total ?> members</div>
    </div>
    <button class="btn" onclick="document.getElementById('addModal').classList.add('open')">+ Add Member</button>
  </div>

  <?php if($msg==='success'): ?>
  <div class="success-msg">✓ Member added successfully!</div>
  <?php endif; ?>

  <div class="table-card">
    <div class="table-header">
      <div style="font-size:16px;font-weight:600;">All Members</div>
    </div>
    <table>
      <thead>
        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Plan</th><th>Join Date</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php while($row = $members->fetch_assoc()): ?>
        <tr>
          <td style="font-weight:500;"><?= htmlspecialchars($row['name']) ?></td>
          <td style="color:var(--gray)"><?= htmlspecialchars($row['email']) ?></td>
          <td style="color:var(--gray)"><?= htmlspecialchars($row['phone']) ?></td>
          <td><span class="badge badge-<?= $row['plan'] ?>"><?= $row['plan'] ?></span></td>
          <td style="color:var(--gray)"><?= $row['join_date'] ?></td>
          <td><span class="badge badge-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
          <td>
            <div class="actions">
              <a href="?toggle=<?= $row['id'] ?>" class="btn-gray" style="padding:6px 12px;font-size:11px;font-weight:700;text-decoration:none;color:var(--white);">Toggle</a>
              <a href="?delete=<?= $row['id'] ?>" class="btn-red" style="text-decoration:none;color:var(--white);" onclick="return confirm('Delete this member?')">Delete</a>
            </div>
          </td>
        </tr>
        <?php endwhile; ?>
        <?php if($total==0): ?>
        <tr><td colspan="7" style="text-align:center;color:var(--gray);padding:40px;">No members yet. Add your first member!</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ADD MODAL -->
<div class="modal-bg" id="addModal">
  <div class="modal">
    <h2>Add New Member</h2>
    <form method="POST">
      <input type="hidden" name="action" value="add">
      <div class="form-row">
        <div class="form-group"><label>Full Name</label><input type="text" name="name" required placeholder="Rahul Sharma"></div>
        <div class="form-group"><label>Phone</label><input type="tel" name="phone" placeholder="+91 98765 43210"></div>
      </div>
      <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="rahul@email.com"></div>
      <div class="form-row">
        <div class="form-group">
          <label>Plan</label>
          <select name="plan">
            <option>Basic</option>
            <option>Pro</option>
            <option>Elite</option>
          </select>
        </div>
        <div class="form-group"><label>Join Date</label><input type="date" name="join_date" value="<?= date('Y-m-d') ?>"></div>
      </div>
      <div style="display:flex;gap:12px;margin-top:8px;">
        <button type="submit" class="btn">Add Member</button>
        <button type="button" class="btn btn-outline" onclick="document.getElementById('addModal').classList.remove('open')">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  <?php if($action==='add'): ?>
  document.getElementById('addModal').classList.add('open');
  <?php endif; ?>
  document.getElementById('addModal').addEventListener('click', function(e){ if(e.target===this) this.classList.remove('open'); });
</script>
</body>
</html>
