<template>
    <div id="main-app">
        <navbar></navbar>
        <div class="site-content bg-light">
            <router-view></router-view>
        </div>
        <app-footer></app-footer>
    </div>
</template>

<script>
import Navbar from "./Navbar";
import Footer from "./Footer";

export default {
    name: "App",
    components: {
        Navbar,
        appFooter: Footer,
    },
};
</script>

<style scoped>
.site-content {
    background-color: #fbfbfb;
    min-height: 700px;
    padding-top: 20px;
    padding-bottom: 50px;
}
</style>
