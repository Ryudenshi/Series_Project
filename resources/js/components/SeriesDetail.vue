<template>
    <div>
        <h1>{{ series?.title }}</h1>
        <p>{{ series?.description }}</p>

        <h2>Сезони:</h2>
        <b-form-group label="Оберіть сезон">
            <b-form-select v-model="selectedSeason" :options="seasonsOptions" />
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
            <VideoPlayer v-if="currentEpisode" :videoUrl="currentEpisode.video_url" />
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
            seasonsOptions: [{ value: null, text: 'Оберіть сезон' }],
            episodes: [],
            currentEpisode: null,
        };
    },
    watch: {
        selectedSeason(newSeason) {
            if (newSeason) {
                this.loadEpisodes();
            }
        },
    },
    created() {
        const seriesId = this.$route.params.id; // Отримання ID серіалу з маршруту
        this.fetchSeries(seriesId); // Завантаження серіалу
        this.fetchSeasons(seriesId); // Завантаження сезонів
    },
    methods: {
        async fetchSeries(seriesId) {
            try {
                const response = await axios.get(`/api/series/${seriesId}`);
                this.series = response.data; // Збереження інформації про серіал
            } catch (error) {
                console.error('Помилка завантаження серіалу:', error);
            }
        },
        async fetchSeasons(seriesId) {
            try {
                const response = await axios.get(`/api/series/${seriesId}/seasons`);
                this.seasonsOptions = [
                    { value: null, text: 'Оберіть сезон' },
                    ...response.data.map(season => ({
                        value: season.id,
                        text: `${season.title} (Сезон ${season.season_number})`,
                    })),
                ];
            } catch (error) {
                console.error('Помилка завантаження сезонів:', error);
                this.seasonsOptions = [{ value: null, text: 'Оберіть сезон' }];
            }
        },
        async loadEpisodes() {
            if (!this.selectedSeason) return;

            try {
                const response = await axios.get(`/api/seasons/${this.selectedSeason}/episodes`);
                this.episodes = response.data; // Збереження епізодів
            } catch (error) {
                console.error('Помилка завантаження епізодів:', error);
                this.episodes = [];
            }
        },
        playEpisode(episode) {
            this.currentEpisode = episode; // Встановлення поточного епізоду
        },
    },
};
</script>

<style scoped>
h1,
h2,
h3 {
    margin-bottom: 1rem;
}

ul {
    list-style-type: none;
    padding: 0;
}

li {
    margin: 0.5rem 0;
}
</style>
