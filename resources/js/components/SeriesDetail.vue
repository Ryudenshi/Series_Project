<template>
    <div class="series-detail">
        <div class="detail-header">
            <img :src="series?.poster ? `/my_series_app/storage/app/public/${series.poster}` : defaultPoster" alt="Постер серіалу"
                class="series-poster" />
            <div class="series-info">
                <h1>{{ series?.title }}</h1>
                <p class="series-description">{{ series?.description }}</p>
            </div>
        </div>

        <div class="seasons-section">
            <h2>Seasons:</h2>
            <b-form-group label="Оберіть сезон" class="season-select-container">
                <b-form-select v-model="selectedSeason" :options="seasonsOptions" class="season-select" />
            </b-form-group>
        </div>

        <div v-if="selectedSeason" class="episodes-section">
            <h3>Episodes:</h3>
            <div class="episode-list" v-if="episodes.length > 0">
                <div v-for="episode in episodes" :key="episode.id" class="episode-card">
                    <button class="episode-btn" @click.prevent="playEpisode(episode)">
                        {{ episode.episode_number }}
                    </button>
                </div>
            </div>
            <p v-else>This season have no episodes yet</p>
        </div>

        <div v-if="currentEpisode" class="current-episode">
            <h3>Video: {{ currentEpisode.title }}</h3>
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
            defaultPoster: 'https://via.placeholder.com/150?text=No+Poster',
            series: null,
            selectedSeason: null,
            seasonsOptions: [{ value: null, text: 'Chouse season' }],
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
        const seriesId = this.$route.params.id;
        this.fetchSeries(seriesId);
        this.fetchSeasons(seriesId);
    },
    methods: {
        async fetchSeries(seriesId) {
            try {
                const response = await axios.get(`/api/series/${seriesId}`);
                this.series = response.data;
                
            } catch (error) {
                console.error('Series loading failed:', error);
            }
        },
        async validateImageUrl(url) {
            try {
                const response = await fetch(url, { method: 'HEAD' });
                return response.ok;
            } catch {
                return false;
            }
        },
        async fetchSeasons(seriesId) {
            try {
                const response = await axios.get(`/api/series/${seriesId}/seasons`);
                this.seasonsOptions = [
                    { value: null, text: 'Season' },
                    ...response.data.map(season => ({
                        value: season.id,
                        text: `${season.title} (Season ${season.season_number})`,
                    })),
                ];
            } catch (error) {
                console.error('Season loading failed:', error);
                this.seasonsOptions = [{ value: null, text: 'Chouse season' }];
            }
        },
        async loadEpisodes() {
            if (!this.selectedSeason) return;

            try {
                const response = await axios.get(`/api/seasons/${this.selectedSeason}/episodes`);
                this.episodes = response.data;
            } catch (error) {
                console.error('Episode loading failed:', error);
                this.episodes = [];
            }
        },
        playEpisode(episode) {
            this.currentEpisode = episode;
        },
    },
};
</script>

<style scoped>
/* Загальні стилі */
.series-detail {
    background-color: #333;
    color: white;
    padding: 20px;
}

.detail-header {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.series-poster {
    width: 200px;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}

.series-info {
    max-width: 60%;
}

h1 {
    color: #f1c40f;
    font-size: 2rem;
}

.series-description {
    color: #ccc;
    font-size: 1.1rem;
    margin-top: 0.5rem;
}

.seasons-section {
    margin-top: 2rem;
}

.season-select-container {
    width: auto;
    margin: left;
    max-width: 300px;
}

.season-select {
    background-color: #444;
    color: #fff;
}

.episodes-section {
    margin-top: 2rem;
}

.episode-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.episode-card {
    background-color: #444;
    border-radius: 10px;
    padding: 10px;
    width: 70px;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.episode-btn {
    background-color: #f1c40f;
    color: #333;
    border: none;
    font-size: 1.2rem;
    font-weight: bold;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.episode-btn:hover {
    background-color: #e67e22;
}

.current-episode {
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .detail-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .series-info {
        max-width: 100%;
    }

    .episode-list {
        flex-direction: column;
    }

    .episode-card {
        width: 50px;
        height: 50px;
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 1.5rem;
    }

    .series-description {
        font-size: 1rem;
    }

    .episode-card {
        width: 40px;
        height: 40px;
    }

    .episode-btn {
        font-size: 1rem;
    }
}
</style>