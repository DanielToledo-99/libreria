
<style>

    .loading{
        display: none;
    }
    .center {
      height: 100vh;
      width: 100vw;  
      justify-content: center;
      align-items: center;
      background: rgba(216, 39, 39, 0.8);
      z-index: 1030;
      position: absolute;
    }
    .wave {
      width: 5px;
      height: 100px;
      background: linear-gradient(45deg, #2f90d1, #fff);
      /* background: linear-gradient(45deg, #c42425, #fff); */
      margin: 10px;
      animation: wave 1s linear infinite;
      border-radius: 20px;
    }
    .wave:nth-child(2) {
      animation-delay: 0.1s;
    }
    .wave:nth-child(3) {
      animation-delay: 0.2s;
    }
    .wave:nth-child(4) {
      animation-delay: 0.3s;
    }
    .wave:nth-child(5) {
      animation-delay: 0.4s;
    }
    .wave:nth-child(6) {
      animation-delay: 0.5s;
    }
    .wave:nth-child(7) {
      animation-delay: 0.6s;
    }
    .wave:nth-child(8) {
      animation-delay: 0.7s;
    }
    .wave:nth-child(9) {
      animation-delay: 0.8s;
    }
    .wave:nth-child(10) {
      animation-delay: 0.9s;
    }
    
    @keyframes wave {
      0% {
        transform: scale(0);
      }
      50% {
        transform: scale(1);
      }
      100% {
        transform: scale(0);
      }
    }
    
    
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 1040;
    }
    
    </style>
    
    <div class="loading overlay">
      <div class="loading center">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
      </div>
    </div>
    
    
    
    
    