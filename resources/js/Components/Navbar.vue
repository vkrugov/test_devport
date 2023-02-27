<template>
    <div>
        <b-navbar toggleable="lg" class="header">
            <b-navbar-brand>
                <router-link class="navbar-brand" :to="{name: 'home'}">
                    <img src="/images/logo.svg" alt="briz-logo">
                </router-link>
            </b-navbar-brand>

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav v-if="isAdmin">
                    <b-nav-item>
                        <router-link class="nav-link" :to="{name: 'users'}">Users</router-link>
                    </b-nav-item>
                </b-navbar-nav>
                <b-navbar-nav class="ml-auto">
                    <li class="nav-item" v-if="isAdmin">
                        <span @click="logout" class="nav-link cursor-pointer">Logout</span>
                    </li>
                    <li class="nav-item" v-else>
                        <router-link :to="{name: 'login'}" class="nav-link cursor-pointer">Login as Admin</router-link>
                    </li>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </div>
</template>

<script>

import {mapState} from "vuex";
import {AUTH_LOGOUT} from "../store/actions/auth.actions";

export default {
    name: "Navbar",
    computed: mapState({
        isAdmin: (state) => state.auth.user.is_admin,
    }),
    methods: {
        logout() {
            this.$store.dispatch(AUTH_LOGOUT)
                .then(() => {
                    this.$router.push({
                        name: 'home'
                    });
                });
        }
    },
};
</script>

<style scoped>
.nav-item, .nav-link {
    cursor: pointer;
    color: #e2e8f0 !important;
}
.header {
    background-color: #ff5b00;
}
</style>
