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
    font-size: 16px; /* 修改表頭的字體大小 */
  }

  .table thead tr th{
    line-height: 1;
    padding:20px 5px;
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
  padding: 8px 8px;
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
$t_sql = "SELECT COUNT(1) FROM `products`";
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
    p.id AS product_id,              -- 商品 ID
    p.name AS product_name,          -- 商品名稱
    p.description AS product_description, -- 商品描述
    p.base_price AS product_price,   -- 商品基本價格
    pw.weight AS product_weight,     -- 商品重量 (如果有規格)
    c.name AS category_name,         -- 商品分類名稱
    p.image_url AS product_image,    -- 商品圖片
    p.created_at AS product_created_at -- 商品建立時間
FROM 
    Products p
LEFT JOIN 
    Product_Weights pw ON p.id = pw.product_id -- 連接商品重量表 (左連接，包含無重量規格的商品)
LEFT JOIN 
    Categories c ON p.category_id = c.id       -- 連接商品分類表
ORDER BY 
    p.id, pw.weight -- 根據商品 ID 和重量排序 (可選)
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

          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i==$page ? 'active' : '' ?>">
              <a style="height = 10px" class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

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
            <th scope="col">商品名稱</th>
            <th scope="col">商品描述</th>
            <th scope="col">器材價格</th>
            <th>器材重量(公斤)</th>
            <th>器材種類</th>
            <th>圖片連結</th>
            <th>建立時間</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= $r['product_id'] ?></td>
              <td><?= $r['product_name'] ?></td>
              <td><?= $r['product_description'] ?></td>
              <td><?= $r['product_price'] ?></td>
              <td><?= $r['product_weight'] ?></td>
              <td><?= $r['category_name'] ?></td>
              <td><?= $r['product_image'] ?></td>
              <td><?= $r['product_created_at'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

</h2>


<?php include __DIR__ . '/includes/html-content wrapper-end.php'; ?>
<?php include __DIR__ . '/includes/html-script.php'; ?>
<?php include __DIR__ . '/includes/html-footer.php'; ?>
