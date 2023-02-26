<template>
    <div class="container mt-2 login-page">
        <b-form @submit.prevent="register" class="container  w-75">
            <h1 class="text-center">
                Registration
            </h1>
            <b-form-group label="Username" class="text-left">
                <b-form-input
                    v-model="name"
                    name="name"
                    type="text"
                    class="form-control"
                    placeholder="Username"
                    :class="{'is-invalid' : errors.name}"
                    @input="delete errors.name"
                >
                </b-form-input>
                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
            </b-form-group>
            <b-form-group label="Phone (UA)" class="text-left">
                <b-form-input
                    v-model="phone"
                    name="phone"
                    type="text"
                    class="form-control"
                    :class="{'is-invalid' : errors.phone}"
                    placeholder="Phone in UA format (+380660006622)"
                    @input="delete errors.phone"
                >
                </b-form-input>
                <div class="invalid-feedback" v-if="errors.phone">{{ errors.phone }}</div>
            </b-form-group>
            <b-button type="submit" class="btn btn-dark btn-block">Register</b-button>
        </b-form>
    </div>
</template>

<script>
import api from "../config";

export default {
    name: "Register",
    data() {
        return {
            name: null,
            phone: null,
            errors: {}
        };
    },
    methods: {
        register() {
            api.post('auth/user/register', {
                name: this.name,
                phone: this.phone,
            }).then((response) => {
                let newGame = response.data.game;
                if (newGame && newGame.url) {
                    this.$router.push({name: 'game', params: {game: newGame.url}});
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
            }) ;
        },
    },
};
</script>

<style scoped>

</style>
