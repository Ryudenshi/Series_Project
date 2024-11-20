import { createRouter, createWebHistory } from 'vue-router';
import SeriesList from '../components/SeriesList.vue';
import SeriesDetail from '../components/SeriesDetail.vue';
import Login from '../components/Login.vue';
import Register from '../components/Register.vue';

const routes = [
    { path: '/login', component: Login },
    { path: '/register', component: Register },
    { path: '/', component: SeriesList },
    { path: '/series/:id', component: SeriesDetail },
];

const router = createRouter({
    history: createWebHistory('/my_series_app/public/'),
    routes:[
        { path: '/login', component: Login },
        { path: '/register', component: Register },
        { path: '/', component: SeriesList },
        { path: '/series/:id', component: SeriesDetail },
    ]
});

export default router;
