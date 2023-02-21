<template>
    <div class="container">
        <div>
            <router-link class="float-right site-btn-over btn m-1" :to="{name: 'users'}">Close</router-link>
        </div>
        <h3>
            Create new Customer User
        </h3>
        <div>
            <user-form :user="user"></user-form>
        </div>
    </div>
</template>

<script>
import UserForm from "./UserForm";

export default {
    name: "CreateUser",
    components: {
        UserForm,
    },
    data() {
        return {
            user: {
                name: '',
                phone: '',
            }
        }
    }
}
</script>

<style scoped>

</style>
