/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuetify from 'vuetify';
import PostListComponent from './components/PostListComponent';
import PostCreateComponent from './components/PostCreateComponent';
import PostShowComponent from './components/PostShowComponent';
import TagListComponent from './components/TagListComponent';

require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);
Vue.use(Vuetify);
Vue.component('FormInput', require('./components/form/Input.vue').default);
Vue.component('PostCard', require('./components/partials/PostCard.vue').default);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: PostListComponent
        },
        {
            path: '/home',
            name: 'post.list',
            component: PostListComponent
        },
        {
            path: '/posts/create',
            name: 'post.create',
            component: PostCreateComponent
        },
        {
            path: '/posts/:postId',
            name: 'post.show',
            component: PostShowComponent,
            props: (route) => ({postId: Number(route.params.postId)})
        },
        {
            path: '/tags',
            name: 'tag.list',
            component: TagListComponent
        }
    ]
});

axios.interceptors.response.use(
    response => response,
    async error => {
        switch (error.response?.status) {
            case 401:
                window.location.href = '/login';
                break;
            default:
                break;
        }
    }
);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import SideBar from './components/SideBar';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {
        SideBar,
    },
    router,
    vuetify: new Vuetify({
        iconfont: 'mdi',
    }),
});
