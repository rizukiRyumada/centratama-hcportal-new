<style>
  /* preloader */
  .preloader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 9999;
      /* background-color: #1089FF; */
      background-image: url('../../../assets/img/background.jpg');
      background-size: cover;
  }
  .preloader .loading {
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%,-50%);
      font: 14px arial;
  }
</style>

<div class="preloader card loading">
  <div class="d-flex h-100 m-0 px-auto w-100"> <!-- this container make the element to vertically and horizontally centered -->
    <div class="d-flex justify-content-center align-self-center w-100 m-0">
      <img src="<?= base_url("assets/") ?>img/loading.svg"  width="80" height="80">
    </div>
  </div>    
</div>