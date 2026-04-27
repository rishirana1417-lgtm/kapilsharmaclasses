<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../config.php';

// Handle status updates
if(isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE demo_requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    if($status === 'Demo Given' && !$request['qr_email_sent']) {
        // Send QR email
        sendQREmail($pdo->query("SELECT * FROM demo_requests WHERE id = $id")->fetch());
        $pdo->prepare("UPDATE demo_requests SET qr_email_sent = 1 WHERE id = ?")->execute([$id]);
    }
}

// Fetch all requests
$stmt = $pdo->query("SELECT * FROM demo_requests ORDER BY created_at DESC");
$requests = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-dashboard">
    <div class="admin-header">
        <h1>Kapil Sharma Classes - Admin</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
    
    <div class="table-container">
        <h2>Demo Class Requests (<?php echo count($requests); ?>)</h2>
        <a href="export.php" class="export-btn">Export CSV</a>
        
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Batch</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($requests as $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['name']); ?></td>
                    <td><?php echo htmlspecialchars($request['email']); ?></td>
                    <td><?php echo htmlspecialchars($request['phone']); ?></td>
                    <td><?php echo htmlspecialchars($request['course']); ?></td>
                    <td><?php echo $request['batch_preference']; ?></td>
                    <td><?php echo $request['preferred_time']; ?></td>
                    <td class="status-<?php echo strtolower($request['status']); ?>">
                        <?php echo $request['status']; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($request['created_at'])); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $request['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php echo $request['status']=='Pending'?'selected':'';?>>Pending</option>
                                <option value="Demo Given" <?php echo $request['status']=='Demo Given'?'selected':'';?>>Demo Given</option>
                                <option value="Joined Offline" <?php echo $request['status']=='Joined Offline'?'selected':'';?>>Joined Offline</option>
                                <option value="Joined Online" <?php echo $request['status']=='Joined Online'?'selected':'';?>>Joined Online</option>
                                <option value="Not Interested" <?php echo $request['status']=='Not Interested'?'selected':'';?>>Not Interested</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>