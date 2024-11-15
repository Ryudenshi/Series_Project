<template>
    <div>
        <h1>Серіали</h1>
        <ul>
            <li v-for="series in seriesList" :key="series.id">
                <router-link :to="`/series/${series.id}`">{{ series.title }}</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    computed: {
        seriesList() {
            return this.$store.state.series;
        },
    },
    created() {
        this.$store.dispatch('fetchSeries');
    },
};
</script>
