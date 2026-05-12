<template>
    <div class="wrapper">
		<div class="header-wrapper">
			<header>
               <TopRocker></TopRocker> 
			</header>
          <MenuRocker></MenuRocker>
		</div>
		<div class="page-wrapper">
			<div class="page-content">
				<router-view></router-view>

			</div>
		</div>
		<div class="overlay toggle-icon"></div>
		<a href="javaScript:;" class="back-to-top"><i
				class='bx bxs-up-arrow-alt'></i></a>
		
        <BotRocker></BotRocker>
		
	</div>
</template>
<script>
    import "../../assets/js/bootstrap.bundle.min.js";
	import "../../assets/js/jquery.min.js";
	import "../../assets/plugins/simplebar/js/simplebar.min.js";
	import "../../assets/plugins/metismenu/js/metisMenu.min.js";
	// import "../../assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js";
	import "../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js";
	import "../../assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js";
	import "../../assets/plugins/chartjs/js/Chart.min.js";
	import "../../assets/plugins/chartjs/js/Chart.extension.js";
	import "../../assets/js/index.js";
	import "../../assets/js/app.js";
    import TopRocker from '../components/TopRocker.vue';
    import BotRocker from '../components/BotRocker.vue';
import MenuRocker from "../components/MenuRocker.vue";
        
export default {
    components: {
        TopRocker,
        BotRocker,
        MenuRocker
    }
}
</script>
<style>
/* @import "../../assets/images/favicon-32x32.png"; */
@import "../../assets/plugins/vectormap/jquery-jvectormap-2.0.2.css";
@import "../../assets/plugins/simplebar/css/simplebar.css";
@import "../../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css";
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
</style>