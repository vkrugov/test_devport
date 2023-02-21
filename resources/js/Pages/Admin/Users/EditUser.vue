<template>
    <div>
        <div class="container">
            <div>
                <router-link class="float-right site-btn-over btn m-1" :to="{name: 'users'}">Close</router-link>
            </div>
            <h3>
                Update Customer User
            </h3>
            <div v-if="user && user.id">
                <user-form :user="user"></user-form>
            </div>
        </div>
    </div>
</template>

<script>

import UserForm from "./UserForm";
import api from "../../../config";

export default {
    name: "EditUser",
    components: {
        UserForm,
    },
    data() {
        return {
            user: {}
        };
    },
    methods: {
        loadUser() {
            let id = this.$route.params.id

            if (id) {
                api.get(`users/${id}`).then((response) => {
                    this.user = response.data.user
                }).catch(error => {
                    if (error.response.status === 404) {
                        this.$router.push({name: 'notfound'});
                    } else {
                        this.$dialog.alert('Something went wrong. Please, try later.');
                    }
                })

            }
        }
    },
    created() {
        this.loadUser();
    }
}
</script>

<style scoped>

</style>
