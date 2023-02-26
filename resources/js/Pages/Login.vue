<template>
    <div class="container mt-2 login-page">
        <b-form @submit.prevent="signIn" class="container w-75">
            <h1 class="text-center">
                Login as Admin
            </h1>
            <b-form-group label="Email" class="text-left">
                <b-form-input
                    v-model="email"
                    name="name"
                    type="text"
                    class="form-control"
                    placeholder="Email"
                    :class="{'is-invalid' : errors.email}"
                    @input="delete errors.email"
                >
                </b-form-input>
                <div class="invalid-feedback" v-if="errors.email">{{ errors.email }}</div>
            </b-form-group>
            <b-form-group label="Password" class="text-left">
                <b-form-input
                    v-model="password"
                    name="phone"
                    type="password"
                    class="form-control"
                    :class="{'is-invalid' : errors.password}"
                    placeholder="Password"
                    @input="delete errors.password"
                >
                </b-form-input>
                <div class="invalid-feedback" v-if="errors.password">{{ errors.password }}</div>
            </b-form-group>
            <b-button type="submit" class="btn btn-dark btn-block">Login</b-button>
        </b-form>
        <pre class="pt-5">
            Login: admin@admin.com
            Password: 123456
        </pre>
    </div>
</template>

<script>
import {AUTH_REQUEST} from "../store/actions/auth.actions";

export default {
    name: "Login",
    data() {
        return {
            email: "",
            password: "",
            errors: {}
        };
    },
    methods: {
        signIn() {
            this.$store.dispatch(AUTH_REQUEST, {email: this.email, password: this.password})
                .then((user) => {
                    if (user.is_admin) {
                        this.$router.push({
                            name: 'users'
                        });
                    } else {
                        this.$router.push({
                            name: 'home'
                        });
                    }
                }).catch((error) => {
                if (!_.isEmpty(error.response.data.errors)) {
                    let errors = error.response.data.errors;
                    let localErrors = {}
                    for (let error in errors) {
                        localErrors[error] = errors[error][0];
                    }
                    this.errors = localErrors
                }
            });
        }
    },
};
</script>

<style scoped>

</style>
