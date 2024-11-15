import { createStore } from 'vuex';
import axios from 'axios';

export default createStore({
    state: {
        series: [],
        selectedSeries: null,
    },
    mutations: {
        setSeries(state, series) {
            state.series = series;
        },
        setSelectedSeries(state, series) {
            state.selectedSeries = series;
        },
    },
    actions: {
        fetchSeries({ commit }) {
            return axios.get('/series')
                .then(response => commit('setSeries', response.data))
                .catch(err => console.error(err));
        },
        fetchSeriesById({ commit }, id) {
            return axios.get(`/series/${id}`)
                .then(response => commit('setSelectedSeries', response.data))
                .catch(err => console.error(err));
        },
    },
});
