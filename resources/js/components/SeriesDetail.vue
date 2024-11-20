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
                        <a href="#" @click.prevent="playEpisode(episode)">
                            {{ episode.title }} (Епізод {{ episode.episode_number }})
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <div v-if="currentEpisode">
            <h3>Відтворення: {{ currentEpisode.title }}</h3>
            <VideoPlayer :videoUrl="currentEpisode.video_url" />
        </div>
    </div>
</template>

<script>
import VideoPlayer from './VideoPlayer.vue';

export default {
    components: {
        VideoPlayer,
    },
    data() {
        return {
            series: null,
            currentEpisode: null,
        };
    },
    created() {
        const seriesId = this.$route.params.id;
        this.$store.dispatch('fetchSeriesById', seriesId).then(() => {
            this.series = this.$store.state.selectedSeries;
        });
    },
    methods: {
        playEpisode(episode) {
            this.currentEpisode = episode;
        },
    },
};
</script>
