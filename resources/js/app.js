import Vue from 'vue'
import axios from 'axios';
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue';
import ClickConfirm from 'click-confirm/src/ClickConfirm.vue'
import VueNotification from "@kugatsu/vuenotification";

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';

import App from './master/App';

import AlunoIndex from './aluno/AlunoIndex';
import CursoIndex from './curso/CursoIndex';
import ProfessorIndex from './professor/ProfessorIndex';
import NotFound from './master/NotFound';

Vue.use(VueRouter)
Vue.use(BootstrapVue);
Vue.use(VueNotification);

Vue.component('clickConfirm', ClickConfirm);

//axios.create({ baseURL: 'http://*****' });

Vue.prototype.$http = axios;

const router = new VueRouter({
    base: '/desafio2',
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'aluno-index',
            component: AlunoIndex
        },
        {
            path: '/curso',
            name: 'curso-index',
            component: CursoIndex,
        },
        {
            path: '/professor',
            name: 'professor-index',
            component: ProfessorIndex,
        },
        {
            path: '*',
            name: 'NotFound',
            component: NotFound,
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});