<template>
    <div class="client-layout-wrapper">
        <Topclient />
        <main class="main-content">
            <router-view />
        </main>
        <Botclient />
    </div>
</template>

<script>
import Topclient from '../components/client/Topclient.vue';
import Botclient from '../components/client/Botclient.vue';

// Import CSS/JS assets
import "../../assets/js/bootstrap.bundle.min.js";
import "../../assets/js/jquery.min.js";
import "../../assets/plugins/metismenu/js/metisMenu.min.js";
import "../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js";
import "../../assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js";
import "../../assets/plugins/chartjs/js/Chart.min.js";
import "../../assets/plugins/chartjs/js/Chart.extension.js";
import "../../assets/js/index.js";
import "../../assets/js/app.js";

export default {
    name: 'ClientLayout',
    components: {
        Topclient,
        Botclient
    }
}
</script>

<style>
/* Toàn bộ các style CSS cần thiết cho layout client */
@import "../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css";
@import "../../assets/plugins/metismenu/css/metisMenu.min.css";
@import "../../assets/css/pace.min.css";
@import "../../assets/css/bootstrap.min.css";
@import "../../assets/css/bootstrap-extended.css";
@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap");
@import "../../assets/css/app.css";
@import "../../assets/css/icons.css";
@import "../../assets/css/dark-theme.css";
@import "../../assets/css/semi-dark.css";
@import "../../assets/css/header-colors.css";
@import "toastr/build/toastr.css";

/* Ẩn các thanh cuộn tùy chỉnh và thanh cuộn mặc định nếu cần */
html::-webkit-scrollbar, 
body::-webkit-scrollbar,
.simplebar-track,
.ps__rail-x,
.ps__rail-y {
    display: none !important;
}

html, body {
    -ms-overflow-style: none !important;  /* IE and Edge */
    scrollbar-width: none !important;  /* Firefox */
    overflow-x: hidden;
}

/* Fix lỗi padding và margin từ template Rocker */
body {
    background-color: #fff !important;
}

.client-layout-wrapper {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.main-content {
    flex: 1;
}

/* Đảm bảo trang chủ không bị che khuất bởi Header trong suốt */
.landing-page {
    margin-top: 0 !important;
}
</style>