
class User {
    constructor(data) {
        this.id = data.id || null;
        this.name = data.name || null;
        this.email = data.email || null;
        this.is_admin = data.roles && data.roles[0] && data.roles[0].name === 'admin';
    }
}

import {
    AUTH_REQUEST,
    AUTH_ERROR,
    AUTH_SUCCESS,
    AUTH_LOGOUT,
    USER_LOAD,
    USER_SELF,
    USER_UPDATE,
} from "../actions/auth.actions";

import api from "../../config";

const state = {
    token: localStorage.getItem("token") || "",
    user: {},
    status: "",
};

const getters = {
    isAuthenticated: state => state.token !== '',
    isAdmin: state => state.token !== '' && state.local === 'ua',
};

const actions = {
    [AUTH_REQUEST]: ({commit, dispatch}, user) => {
        return new Promise((resolve, reject) => {
            commit(AUTH_REQUEST);
            api.post('/auth/admin/login', {email: user.email, password: user.password})
                .then(({data}) => {
                    let usr = new User(data.user);
                    localStorage.setItem('token', data.auth.access_token);
                    commit(AUTH_SUCCESS, usr, data.auth.access_token);
                    resolve(usr);
                })
                .catch(err => {
                    commit(AUTH_ERROR, err);
                    localStorage.removeItem('token');
                    reject(err);
                })
        });
    },
    [AUTH_LOGOUT]: ({commit, dispatch}) => {
        return new Promise((resolve) => {
            api.post('/auth/admin/logout').then(() => {
                resolve();
            }).catch(err => {
                reject(err);
            }).finally(() => {
                commit(AUTH_LOGOUT);
                localStorage.removeItem("token");
                localStorage.removeItem("cart");
            });
        })
    },
    [USER_LOAD]: ({commit, dispatch}) => {
        return new Promise((resolve, reject) => {
            if (state.user instanceof User) {
                resolve(state.user);
                return;
            }
            commit(USER_LOAD);
            api.get('/auth/admin/self')
                .then(({data}) => {
                    let usr = new User(data.user);
                    commit(USER_UPDATE, usr);
                    resolve(usr);
                })
                .catch(() => {
                commit(AUTH_LOGOUT);
                localStorage.removeItem("token");
                reject();
            })
        });
    },
    [USER_SELF]: ({commit}) => {
        return new Promise((resolve, reject) => {
            commit(USER_LOAD);
            api.get('/auth/admin/self')
                .then(({data}) => {
                    let usr = new User(data.user);
                    commit(USER_UPDATE, usr);
                    resolve(usr);
                })
                .catch(() => {
                    reject();
                })
        });
    },
};

const mutations = {
    [AUTH_REQUEST]: (state) => {
        state.status = "loading";
    },
    [AUTH_SUCCESS]: (state, user, token) => {
        state.status = "success";
        state.user = user;
        state.token = token;
    },
    [AUTH_ERROR]: (state) => {
        state.status = "error";
    },
    [AUTH_LOGOUT]: (state) => {
        state.token = "";
        state.user = {};
    },
    [USER_LOAD]: (state) => {
        state.status = "loading"
    },
    [USER_UPDATE]: (state, user) => {
        state.user = user;
        state.status = "success"
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
}
