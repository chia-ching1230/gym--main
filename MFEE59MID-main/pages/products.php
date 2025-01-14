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
  line-height: 1.5;
  font-size: 18px;
}

.table tbody tr td {
  padding: 10px 10px;
}
</style>


<?php
$perPage = 15; # 每一頁有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header('Location: ?page=1'); # 跳轉頁面 (後端), 也稱為 redirect (轉向)
  exit; # 離開 (結束) 程式 (以下的程式都不會執行)
}

$keyword = empty($_GET['keyword']) ? '' : $_GET['keyword'];

$where = ' WHERE 1 '; 
if($keyword){
  $keyword_ = $pdo->quote("%{$keyword}%"); 
  $where .= " AND name LIKE $keyword_";
}

$t_sql = "SELECT COUNT(1) FROM `products` $where";
# 總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
# 總頁數
$totalPages = ceil($totalRows / $perPage);

$rows = []; # 設定預設值
if ($totalRows > 0) {
  if ($page > $totalPages) {
    // ob_clean();
    # 用戶要看的頁碼超出範圍, 跳到最後一頁
    header('Location: ?page=' . $totalPages);
    exit;
  }

# 取第一頁的資料
$sql = sprintf(
"SELECT 
    p.id AS product_id, -- 商品 ID
    product_code,            -- 商品號碼  
    p.name,          -- 商品名稱
    p.description, -- 商品描述
    p.category_name,   -- 商品分類名稱
    p.weight,     -- 商品重量 (如果有規格)
    base_price,     -- 商品價格
    p.image_url,    -- 商品圖片
    p.created_at -- 商品建立時間
FROM 
    Products p
%s  
ORDER BY 
    p.id, p.weight -- 根據商品 ID 和重量排序 (可選)
LIMIT %s, %s", 
$where,
($page - 1) * $perPage,  
$perPage);
$rows = $pdo->query($sql)->fetchAll(); # 取得該分頁的資料
}
?>
<div class="card pb-5">
  <div class="row">
    <div class="col-10">
      <h4 class="card-header">器材列表</h4>
    </div>
    <div class="col-2 card-header d-flex align-items-center justify-content-center fs-4">
      <a href="products_add.php" class="nav-link">
        <span class="d-none d-sm-block"> 
        <i class="fa-solid fa-square-plus fa-xl mx-3 fs-4"></i>新增器材</span>
      </a>
    </div>
  </div>
<div class="container">
<div class="row mt-4">
    <div class="col d-flex justify-content-between">
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
      <!-- 搜尋 -->
      <div class="col-lg-3 me-5  d-flex align-items-center justify-content-end">
        <form class="d-flex  ">
            <div class="input-group">
                  <button class="input-group-text">
                    <i class="tf-icons bx bx-search"></i>
                  </button>
              <input type="search" class="form-control" placeholder="Search..." name="keyword" 
              value="<?=empty($_GET['keyword'])?'':htmlentities($_GET['keyword'])?>">
            </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <table class="table">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th >#id</th>
            <th>器材編號</th>
            <th>器材名稱</th>
            <th>器材描述</th>
            <th>器材種類</th>
            <th>器材重量(公斤)</th>
            <th>器材價格</th>
            <th>圖片連結</th>
            <th>建立時間</th>
            <th>編輯</th>
            <th>刪除</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= $r['product_id'] ?></td>
              <td><?= $r['product_code'] ?></td>
              <td><?= $r['name'] ?></td>
              <td><?= $r['description'] ?></td>
              <td><?= $r['category_name'] ?></td>
              <td><?= $r['weight'] ?></td>
              <td><?= $r['base_price'] ?></td>
              <td><?= $r['image_url'] ?></td>
              <td><?= $r['created_at'] ?></td>
              <td><a class="dropdown-item" href="products_edit.php?product_id=<?= $r['product_id'] ?>">
                <i class="bx bx-edit-alt me-1"></i></a>
              </td>
              <td><a class="dropdown-item"  href="javascript:" onclick="deleteOne(event)">
                <i class="bx bx-trash me-1"></i></a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div> 

<!-- modal delete -->
<div class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true" id="delete-modal">
    <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h4 class="modal-title" id="exampleModalLabel2">確定是否刪除</h4>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary me-3" data-bs-dismiss="modal" id="yesgo"> 
            <i class="fa-solid fa-circle-check me-3"></i>是
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> <a href="./products.php" class="nav-link">
            <i class="fa-solid fa-circle-xmark me-3"></i>否</a>
            </button>
        </div>
    </div>
    </div>
</div>

<?php include __DIR__ . '/includes/html-content wrapper-end.php'; ?>
<?php include __DIR__ . '/includes/html-script.php'; ?>
<script>
  
  const deleteOne = e=>{
          e.preventDefault();
          const tr = e.target.closest('tr')
          const [td_product_id, ,td_name] = tr.querySelectorAll('td');
          const productid = td_product_id.innerHTML
          const name = td_name.innerHTML
          const delModal = new bootstrap.Modal('#delete-modal')
          delModal.show()
          document.querySelector('#exampleModalLabel2').innerHTML=`是否要刪除編號為${productid}，名稱為${name}的器材`
          document.querySelector('#yesgo').addEventListener('click',function(){
            location.href=`product-del-api.php?product_id=${productid}`
          })
      }
</script>
<?php include __DIR__ . '/includes/html-footer.php'; ?>
