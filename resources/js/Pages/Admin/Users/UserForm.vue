<template>
    <div class="mt-5">
        <b-form @submit.prevent="submitUser" ref="signInForm" data-vv-scope="signInForm">
            <div class="row">
                <b-form-group label="Name" class="col-md-6">
                    <b-form-input
                        v-model="user.name"
                        name="title"
                        type="text"
                        class="form-control"
                        placeholder="Name"
                        :class="{'is-invalid' : errors.name}"
                        @input="delete errors.name"
                    >
                    </b-form-input>
                    <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}</div>
                </b-form-group>
                <b-form-group label="Phone (UA)" class="col-md-6">
                    <b-form-input
                        v-model="user.phone"
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
            </div>
            <div class="text-left mt-2">
                <b-button class="btn site-btn-over"
                          type="submit"
                >Submit
                </b-button>
            </div>
        </b-form>
    </div>
</template>

<script>

import api from "../../../config";

export default {
    name: "UserForm",
    props: {
        user: {
            required: true,
        }
    },
    data() {
        return {
            errors: {},
        };
    },
    methods: {
        setData() {
            let data = {
                name: this.user.profile.name,
                phone: this.user.profile.phone,
            }

            if (!_.isUndefined(this.user.id )) {
                return data
            }

            let formData = new FormData();

            for ( let key in data ) {
                formData.append(key, data[key]);
            }

            return formData;
        },
        submitUser() {
            this.$emit('submit', this.user);

            if (this.user.id) {
                this.update();
            } else {
                this.create();
            }

        },
        create() {
                api.post('users', {
                    name: this.user.name,
                    phone: this.user.phone,
                }).then(() => {
                    this.$dialog.alert('Success!').then(() => {
                        this.$router.push({name: 'users'})
                    });
                }).catch(error => {
                    this.handleError(error);
                });
        },
        update() {
            api.put(`users/${this.user.id}`, {
                name: this.user.name,
                phone: this.user.phone,
            }).then(() => {
                this.$dialog.alert('Success!').then(() => {
                    this.$router.push({name: 'users'})
                });
            }).catch(error => {
                this.handleError(error);
            });
        },
        handleError(error) {
            if (!_.isEmpty(error.response.data.errors)) {
                let errors = error.response.data.errors;
                let localErrors = {}
                for (let error in errors) {
                    localErrors[error] = errors[error][0];
                }
                this.errors = localErrors
            }
            this.$dialog.alert('Error !');
        }
    }
};
</script>

<style scoped>

</style>
