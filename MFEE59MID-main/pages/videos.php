<?php require __DIR__ . '/includes/init.php';?>
<?php include __DIR__ . '/includes/html-header.php'; ?>
<?php include __DIR__ . '/includes/html-sidebar.php'; ?>
<?php include __DIR__ . '/includes/html-layout-navbar.php'; ?>
<?php include __DIR__ . '/includes/html-content wrapper-start.php'; ?>
<style>
  table {
    font-size: 14px; /* 修改整個表格的字體大小 */
  }
  th {
    font-size: 18px; /* 修改表頭的字體大小 */
  }
.pagination .page-link {
  border-radius: 50%;
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
}

/* 縮短表格行高 */
.table tbody tr {
  line-height: 1.2;
}

.table tbody tr td {
  padding: 10px 10px;
}
</style>
<h2>
<?php
$perPage = 25; # 每一頁有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); # 跳轉頁面 (後端), 也稱為 redirect (轉向)
  exit; # 離開 (結束) 程式 (以下的程式都不會執行)
}
$t_sql = "SELECT COUNT(1) FROM `videos`";
# 總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
# 總頁數
$totalPages = ceil($totalRows / $perPage);

$rows = []; # 設定預設值
if ($totalRows > 0) {
  if ($page > $totalPages) {
    ob_clean();
    # 用戶要看的頁碼超出範圍, 跳到最後一頁
    header('Location: ?page=' . $totalPages);
    exit;
  }
# 取第一頁的資料
$sql = sprintf("SELECT 
    Videos.VideoID AS video_id, 
    Videos.title AS video_title, 
    Videos.description AS video_description, 
    videos_categories.name AS category_name,
    VideoURL,
    videos.UploadedAt,
    videos.UpdatedAt
FROM 
    Videos
JOIN 
    videos_categories
ON 
    Videos.CategoryID = videos_categories.CategoryID
ORDER BY 
    video_id
LIMIT %s, %s", 
($page - 1) * $perPage,  $perPage);
$rows = $pdo->query($sql)->fetchAll(); # 取得該分頁的資料
}
?>
<div class="container">
<div class="row mt-4">
    <div class="col">
      <nav aria-label="Page navigation example">
        <ul class="pagination">
        <!-- 上頁按鈕 -->
        <li class="page-item <?= $page==1 ? 'disabled' : '' ?>">
        <a class="page-link" href="?page=1">
              <i class="fa-solid fa-angles-left"></i>
            </a>
          </li>
          <li class="page-item <?= $page==1 ? 'disabled' : '' ?>">
          <a class="page-link" href="?page=<?= $page - 1 ?>">
              <i class="fa-solid fa-angle-left"></i>
            </a>
          </li>
<!-- 頁籤按鈕 -->
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li style="height=20px;" class="page-item <?= $i==$page ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>
          
          <!-- 下頁按鈕 -->
          <li class="page-item <?= $page==$totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $page + 1 ?>">
              <i class="fa-solid fa-angle-right"></i>
            </a>
          </li>
          <li class="page-item <?= $page==$totalPages ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $totalPages ?>">
              <i class="fa-solid fa-angles-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col">#id</th>
            <th scope="col">影片標題</th>
            <th scope="col">影片內容</th>
            <th scope="col">影片連結</th>
            <th>影片類型</th>
            <th>建立時間</th>
            <th>更新時間</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= $r['video_id'] ?></td>
              <td><?= $r['video_title'] ?></td>
              <td><?= $r['video_description'] ?></td>
              <td><?= $r['VideoURL'] ?></td>
              <td><?= $r['category_name'] ?></td>
              <td><?= $r['UploadedAt'] ?></td>
              <td><?= $r['UpdatedAt'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

</h2>


<?php include __DIR__ . '/includes/html-content wrapper-end.php'; ?>
<?php include __DIR__ . '/includes/html-script.php'; ?>
<?php include __DIR__ . '/includes/html-footer.php'; ?>
