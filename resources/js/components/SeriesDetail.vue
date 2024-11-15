<template>
    <div>
        <h1>{{ series?.title }}</h1>
        <p>{{ series?.description }}</p>
        <h2>Сезони:</h2>
        <ul>
            <li v-for="season in series?.seasons" :key="season.id">
                {{ season.title }} (Сезон {{ season.season_number }})
                <ul>
                    <li v-for="episode in season.episodes" :key="episode.id">
                        {{ episode.title }} (Епізод {{ episode.episode_number }})
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data() {
        return {
            series: null,
        };
    },
    created() {
        const seriesId = this.$route.params.id;
        this.$store.dispatch('fetchSeriesById', seriesId).then(() => {
            this.series = this.$store.state.selectedSeries;
        });
    },
};
</script>
