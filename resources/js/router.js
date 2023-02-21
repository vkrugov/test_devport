import VueRouter from "vue-router";
import store from './store';
import Home from "./Pages/Home";
import NotFound from "./Components/NotFound";
import {AUTH_LOGOUT, USER_LOAD} from "./store/actions/auth.actions";

import api from "./config";
import Users from "./Pages/Admin/Users/Users";
import Game from "./Pages/Customer/Game";
import Login from "./Pages/Login";
import EditUser from "./Pages/Admin/Users/EditUser";
import CreateUser from "./Pages/Admin/Users/CreateUser";

const isAdmin = (to, from, next) => {
    if (store.getters.isAuthenticated) {
        if (store.state.auth.user.id && store.state.auth.user.is_admin) {
            next();
        } else {
            api.get('/auth/admin/self')
                .then(({data}) => {
                    let user = data.user
                    if (user.roles && user.roles[0] && user.roles[0].name === 'admin') {
                        next();
                    } else {
                        next('/');
                    }
                }).catch(er => {
                next('/');
            })
        }
    } else {
        next('/admin-login');
    }
};


const router =  new VueRouter ({
    routes: [
        {
            path: '/',
            component: Home,
            name: 'home',
        },
        {
            path: '/login',
            component: Login,
            name: 'login',
        },
        {
            path: '/games/:game',
            component: Game,
            name: 'game',
        },
        {
            path: '/admin/users',
            component: Users,
            name: 'users',
            beforeEnter: isAdmin
        },
        {
            path: '/admin/users/:id',
            component: EditUser,
            name: 'updateUser',
            beforeEnter: isAdmin
        },
        {
            path: '/admin/users/create',
            component: CreateUser,
            name: 'createUser',
            beforeEnter: isAdmin
        },
        {
            path: '/*',
            component: NotFound,
            name: 'notfound',
        },
    ],
    mode: 'history'
});

router.beforeEach((to, from, next) => {
    window.scrollTo(0, 0);
    if (store.getters.isAuthenticated) {
        store.dispatch(USER_LOAD)
            .catch(() => {
                next('/')
            });
    } else {
        store.commit(AUTH_LOGOUT);
    }

    next();
});

export default router
