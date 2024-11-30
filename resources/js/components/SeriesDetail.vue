<template>
    <div>
        <h1>{{ series?.title }}</h1>
        <p>{{ series?.description }}</p>

        <h2>Сезони:</h2>
        <b-form-group label="Оберіть сезон">
            <b-form-select v-model="selectedSeason" :options="seasonsOptions" @change="loadEpisodes" />
        </b-form-group>

        <h3 v-if="selectedSeason">Епізоди:</h3>
        <ul v-if="episodes.length > 0">
            <li v-for="episode in episodes" :key="episode.id">
                <a href="#" @click.prevent="playEpisode(episode)">
                    {{ episode.title }} (Епізод {{ episode.episode_number }})
                </a>
            </li>
        </ul>
        <p v-else>Немає доступних епізодів для цього сезону.</p>

        <div v-if="currentEpisode">
            <h3>Відтворення: {{ currentEpisode.title }}</h3>
            <VideoPlayer :videoUrl="currentEpisode.video_url" />
        </div>
    </div>
</template>

<script>
import VideoPlayer from './VideoPlayer.vue';
import axios from 'axios';

export default {
    components: {
        VideoPlayer,
    },
    data() {
        return {
            series: null,
            selectedSeason: null,
            episodes: [],
            currentEpisode: null,
        };
    },
    computed: {
        seasonsOptions() {
            return [
                { value: null, text: 'Оберіть сезон' },
                ...(this.series?.seasons.map(season => ({
                    value: season.id,
                    text: `${season.title} (Сезон ${season.season_number})`,
                })) || [])
            ];
        }
    },
    created() {
        const seriesId = this.$route.params.id;
        this.fetchSeries(seriesId);
    },
    methods: {
        async fetchSeries(seriesId) {
            try {
                const response = await axios.get(`/api/series/${seriesId}`);
                this.series = response.data;
                this.selectedSeason = null; // Очищення вибору
            } catch (error) {
                console.error('Помилка завантаження серіалу:', error);
            }
        },
        onSeasonChange(value) {
            console.log('Season changed to:', value);
            if (value) {
                this.loadEpisodes();
            }
        },
        async loadEpisodes() {
            console.log('Season ID selected:', this.selectedSeason);
            if (!this.selectedSeason) return;

            try {
                const response = await axios.get(`/api/seasons/${this.selectedSeason}/episodes`);
                this.episodes = response.data;
            } catch (error) {
                console.error('Помилка завантаження епізодів:', error);
            }
        },
        playEpisode(episode) {
            this.currentEpisode = episode;
        },
    },
};
</script>