<template>
    <div class="container">
        <h1>
            Users
        </h1>
        <div class="row pt-3">
            <div class="col-lg-6">
                <b-input type="text" v-model="filters.searchInput" />
            </div>
            <div class="col-lg-2">
                <button class="btn btn-dark btn-block" @click="search">Search</button>
            </div>
            <div class="col-lg-2">
                <button class="btn btn-secondary btn-block" @click="clearSearch">Clear</button>
            </div>
            <div class="col-lg-2">
                <router-link :to="{name: 'createUser'}" class="btn btn-info" @click="clearSearch">Create New</router-link>
            </div>
        </div>
        <hr>
        <div class="row pb-2">
            <b-form-select
                @change="getWithSort"
                v-model="sort"
                :options="sortOptions"
                class="pull-right"
                value-field="item"
                text-field="name"
                disabled-field="notEnabled"
            ></b-form-select>
        </div>
        <div v-if="users.length" v-for="user in users" class="mb-2">
            <UserPreview
                :user="user"
                @deleted="loadUsers"
            ></UserPreview>
        </div>
        <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center justify-content-lg-center">
                    <li v-if="meta.current_page > 1" class="cursor-pointer"><span
                        class="page-link" @click="getByPage(meta.current_page - 1)"
                        aria-label="Previous"><span class="color-black" aria-hidden="true">«</span></span></li>
                    <li v-for="key in meta.last_page"
                        v-show="key >= meta.current_page - 4 && key <= meta.current_page + 4"
                        class="cursor-pointer">
                        <span
                            class="page-link color-black"
                            :class="{'active-pagination' : key === meta.current_page}"
                            @click="getByPage(key)"
                        > {{ key }} </span>
                    </li>
                    <li v-if="meta.current_page < meta.last_page" class="cursor-pointer"><span
                        class="page-link" @click="getByPage(meta.current_page + 1)"
                        aria-label="Next"><span class="color-black"
                                                aria-hidden="true">»</span></span></li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>

import UserPreview from "./UserPreview";
import api from "../../../config";

export default {
    name: "AllOrders",
    components: {UserPreview},
    data() {
        return {
            users: [],
            limit: 10,
            page: 1,
            offset: 0,
            meta: {},
            filters: {
                searchInput: '',
            },
            sort: "-created_at",
            sortOptions: [
                { item: '-name', name: 'Name Z-A' },
                { item: 'name', name: 'Name A-Z' },
                { item: '-created_at', name: 'Newest first'},
                { item: 'created_at', name: 'Oldest first' },
                { item: 'games_count', name: 'Came count: from min' },
                { item: '-games_count', name: 'Came count: from max' },
            ],
        };
    },
    methods: {
        clearSearch() {
            this.filters = {
                searchInput: '',
            };
            this.search();
        },
        getWithSort() {
            this.page = 1;
            this.loadUsers()
        },
        search() {
            this.page = 1;
            this.loadUsers()
        },
        getByPage(page) {
           this.page = page;
           this.loadUsers();
        },
        loadUsers() {
            this.offset = (this.page * this.limit) - this.limit;
            let params = {
                filters: this.filters,
                sort: this.sort,
                offset: this.offset,
                limit: this.limit
            };

            let url = '/users';
            let strParams = '?'
            let stringify = require('qs-stringify')
            strParams += stringify(params);
            api.get(url + strParams).then((response) => {
                this.users = response.data.users.data;
                this.meta = response.data.users;
            });
        },
    },
    mounted() {
        this.loadUsers();
    }
}
</script>

<style scoped>
.page-link {
    cursor: pointer;
}
.active-pagination {
    background: dimgray;
    color: white;
}
.active-pagination:hover {
    background: dimgray;
    color: white;
}
</style>
