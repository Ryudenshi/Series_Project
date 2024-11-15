import { createRouter, createWebHistory } from 'vue-router';
import SeriesList from '../components/SeriesList.vue';
import SeriesDetail from '../components/SeriesDetail.vue';

const routes = [
    { path: '/', component: SeriesList },
    { path: '/series/:id', component: SeriesDetail },
];

const router = createRouter({
    history: createWebHistory('/my_series_app/public/'),
    routes,
});

export default router;
