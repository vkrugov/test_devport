<template>
    <div>
        <div class="row m1-2 block-shadow">
            <div class="col-lg-4">
                <div>
                    <strong>Name</strong>
                </div>
                <span>{{ user.name }}</span>
            </div>
            <div class="col-lg-2">
                <div>
                    <strong>Phone:</strong>
                </div>
                <span>{{ user.phone }}</span>
            </div>
            <div class="col-lg-2">
                <div>
                    <strong>Games Count:</strong>
                </div>
                <span>{{ user.games_count }}</span>
            </div>
            <div class="col-lg-3 text-center">
                <div class="actions">
                    <strong>Actions:</strong>
                    <div class="cian-text">
                    <span class="cursor-pointer site-link" data-title="Delete">
                        <b-icon icon="trash" aria-hidden="true"
                                v-confirm="{
                                loader: true,
                                ok: dialog => deleteUser(dialog, user.id),
                                cancel: null,
                                message: 'Are you sure?'
                        }"/>
                    </span>
                        <span class="cursor-pointer" data-title="Edit">
                        <router-link class="" :to="{name: 'updateUser', params: {id: user.id}}">
                            <b-icon icon="pencil" aria-hidden="true"/>
                        </router-link>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import api from "../../../config";

export default {
    name: "UserPreview",
    props: {
        user: {
            required: true,
            type: Object,
        }
    },
    methods: {
        deleteUser(dialog) {
            api.delete(`users/${this.user.id}`).then((response) => {
                this.$dialog.alert('Success!').then(() => {
                    this.$emit('deleted');
                });
            }).catch(() => {
                this.$dialog.alert("Something went wrong.");
            }).finally(() => {
                dialog.close()
            })

        },
    },
};
</script>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}
.block-shadow {
    background-color: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,.1);
}
</style>
<style scoped>
