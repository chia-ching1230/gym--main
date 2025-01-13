<?php require __DIR__ . '/includes/init.php';?>
<?php
$title = "新增器材";
$pageName = "products_add"; 
?>
<?php include __DIR__ . '/includes/html-header.php'; ?>
<?php include __DIR__ . '/includes/html-sidebar.php'; ?>
<?php include __DIR__ . '/includes/html-layout-navbar.php'; ?>
<?php include __DIR__ . '/includes/html-content wrapper-start.php'; ?>

<div class="col-xxl">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">新增器材</h5> 
        <small class="text-muted float-end"> <a href="products.php" class="nav-link">回到器材列表</a>
        </small>
      </div>
      <div class="card-body">
        <form onsubmit="sendData(event)">
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-code">器材編號(必填)</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-code" placeholder="例如:P001" name="product_code">
              <div id="codeError" class="color-danger my-2"></div>
            </div>
            
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-name">器材名稱(必填)</label>
            <div class="col-sm-10">
              <input type="text" class="form-control " id="basic-default-name" name="product_name" placeholder="product" >
              <div id="nameError" class="color-danger my-2"></div>
            </div>
            
          </div>
          <div class="row mb-2">
            <label class="col-sm-2 col-form-label" for="basic-default-description">器材描述(必填)</label>
            <div class="col-sm-10">
              <textarea id="basic-default-description" class="form-control" placeholder="適合中等強度力量訓練。"  rows="3" name="description" require></textarea>
              <div id="descriptionError"></div>
            </div>
          </div>
          <div class="row" >
            <div class="col-12">
                <small class="text-muted float-end" id="textCount">0 個字</small>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-checkbox" >器材種類</label >
            <div class="col-sm-10">
                <div class="row">
                    <div class="col-sm-6">
                        <select id="sendNotification" class="form-select" name="category_name">
                            <option value="健身用品" selected="">健身用品</option>
                            <option value="拳擊用品">拳擊用品</option>
                            <option value="瑜珈輔具">瑜珈輔具</option>
                        </select>
                    </div>
                </div>
                
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-title">器材重量</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-title" placeholder="10(若器材有重量規格分類再填寫，若無則空白)" name="weight">
              <div id="titleError" class="color-danger my-2"></div>
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-price">器材價格(必填)</label>
            <div class="col-sm-10">
              <input type="number" class="form-control" id="basic-default-price" placeholder="150" name="price" require>
              <div id="priceError" class="color-danger my-2"></div>
            </div>
            
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-url">圖片連結(必填)</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-url" placeholder="images/treadmill.jpg" name="image_url" require>
              <div id="urlError" class="color-danger my-2"></div>
            </div>
            
          </div>
          <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">確定</button>
            <button type="reset" class="btn btn-outline-secondary">重設</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  

<?php include __DIR__ . '/includes/html-content wrapper-end.php'; ?>
<!-- modal success -->
<div class="modal fade" tabindex="-1" style="display: none;" aria-hidden="true" id="success-modal">
    <div class="modal-dialog modal-sm" role="document" >
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel2">新增結果</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <!-- alert -->
            <div class="alert alert-primary" role="alert">
            成功!
            </div>
        </div>
        <div class="modal-footer">
          <a href="./products.php" class="nav-link">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> 
            <i class="fa-solid fa-door-open me-4"></i>回到列表</a>
            </button>
        </div>
    </div>
    </div>
</div>

<script>
    const code = document.querySelector('#basic-default-code')
    const name = document.querySelector('#basic-default-name')
    const url = document.querySelector('#basic-default-url')
    const price = document.querySelector('#basic-default-price')
    const description = document.querySelector('#basic-default-description')
    const textCount = document.querySelector('#textCount')
    
    description.addEventListener('input', () => {
    textCount.innerHTML = `${description.value.length} 個字`;
    });
    
    const sendData = e=>{
        e.preventDefault();
        code.classList.remove('btn-outline-danger')
        name.classList.remove('btn-outline-danger')
        description.classList.remove('btn-outline-danger')
        price.classList.remove('btn-outline-danger')
        url.classList.remove('btn-outline-danger')
        textCount.classList.remove('btn-outline-danger')
        
        let isPass = true 

        const codePattern = /^P\d{3}$/;
        if (code.value.length <= 0) {
            isPass = false;
            document.querySelector('#codeError').innerHTML = '此欄為必填';
            code.classList.add('btn-outline-danger');
        } else if (!codePattern.test(code.value)) {
            isPass = false;
            document.querySelector('#codeError').innerHTML = '格式錯誤 (須為P加上三位數)';
            code.classList.add('btn-outline-danger');
        }
        if(name.value.length <= 0){
            isPass=false;
            document.querySelector('#nameError').innerHTML ='此欄為必填'
            name.classList.add('btn-outline-danger')
        }
        if(description.value.length <= 0){
            isPass=false;
            document.querySelector('#descriptionError').innerHTML ='此欄為必填'
            description.classList.add('btn-outline-danger')
        }
        if(price.value.length <= 0){
            isPass=false;
            document.querySelector('#priceError').innerHTML ='此欄為必填'
            price.classList.add('btn-outline-danger')
        }
        if(url.value.length <= 10){
            isPass=false;
            document.querySelector('#urlError').innerHTML ='至少需填入10個字元'
            url.classList.add('btn-outline-danger')
        }

        
        if (isPass) {
          const fd = new FormData(document.forms[0]);
          const myModal = new bootstrap.Modal('#success-modal')
          fetch(`product-add-api.php`, {
            method: 'POST',
            body: fd
            }).then(r => r.json())
            .then(obj => {
            console.log(obj);
            if (!obj.success && obj.error) {
                alert(obj.error)
            }
            if (obj.success) {
                myModal.show()
            }
            }).catch(console.warn);
        }
    }
</script>
<?php include __DIR__ . '/includes/html-script.php'; ?>
<?php include __DIR__ . '/includes/html-footer.php'; ?>