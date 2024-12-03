<template>
    <div>
        <h1>Адмін-панель</h1>

        <!-- Кнопка для додавання серіалу -->
        <b-button variant="primary" @click="openSeriesModal">Додати серіал</b-button>

        <!-- Кнопка для додавання сезону -->
        <b-button variant="info" @click="openSeasonModal">Додати сезон до серіалу</b-button>

        <!-- Кнопка для додавання епізоду -->
        <b-button variant="warning" @click="openEpisodeModal">Додати епізод до сезону</b-button>

        <!-- Модальне вікно для додавання серіалу -->
        <b-modal v-model="showSeriesModal" title="Додати серіал">
            <b-form @submit.prevent="addSeries">
                <b-form-group label="Назва серіалу">
                    <b-form-input v-model="seriesTitle" required></b-form-input>
                </b-form-group>
                <b-form-group label="Опис">
                    <b-form-textarea v-model="seriesDescription" required></b-form-textarea>
                </b-form-group>
                <b-form-group label="Постер">
                    <input type="file" @change="handleFileChange" accept="image/*" class="form-control" />
                </b-form-group>
                <b-button type="submit" variant="success">Додати</b-button>
            </b-form>
        </b-modal>

        <!-- Модальне вікно для додавання сезону -->
        <b-modal v-model="showAddSeasonModal" title="Додати сезон до серіалу">
            <b-form @submit.prevent="addSeason">
                <b-form-group label="Серіал">
                    <b-form-select v-model="seriesId" :options="seriesOptions" required></b-form-select>
                </b-form-group>
                <b-form-group label="Назва сезону">
                    <b-form-input v-model="seasonTitle" required></b-form-input>
                </b-form-group>
                <b-form-group label="Номер сезону">
                    <b-form-input type="number" v-model="seasonNumber" required></b-form-input>
                </b-form-group>
                <b-button type="submit" variant="success">Додати сезон</b-button>
            </b-form>
        </b-modal>

        <!-- Модальне вікно для додавання епізоду -->
        <b-modal v-model="showAddEpisodeModal" title="Додати епізод до сезону">
            <b-form @submit.prevent="addEpisode">
                <!-- Вибір серіалу -->
                <b-form-group label="Серіал">
                    <b-form-select 
                        v-model="seriesId" 
                        :options="seriesOptions" 
                        @change="fetchSeasons" 
                        required
                    />
                </b-form-group>

                <!-- Вибір сезону -->
                <b-form-group label="Сезон" v-if="seasonsOptions.length > 0">
                    <b-form-select 
                        v-model="seasonId" 
                        :options="seasonsOptions" 
                        required
                    />
                </b-form-group>

                <!-- Номер епізоду -->
                <b-form-group label="Номер епізоду">
                    <b-form-input type="number" v-model="episodeNumber" required />
                </b-form-group>
                <!-- Назва епізоду -->
                <b-form-group label="Назва епізоду">
                    <b-form-input v-model="episodeTitle" required />
                </b-form-group>
                <!-- Відео -->
                <b-form-group label="Відео">
                    <input 
                        type="file" 
                        @change="handleVideoChange" 
                        accept="video/*" 
                        class="form-control" 
                    />
                    <b-form-input v-model="episodeVideoUrl" readonly />
                </b-form-group>

                <b-button type="submit" variant="success">Додати епізод</b-button>
            </b-form>
        </b-modal>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            seriesOptions: [],
            seasonsOptions: [],
            showSeriesModal: false,
            showAddSeasonModal: false,
            showAddEpisodeModal: false,
            seriesTitle: '',
            seriesDescription: '',
            poster: null,
            seasonTitle: '',
            seasonNumber: '',
            episodeTitle: '',
            episodeVideoUrl: '',
            seriesId: null,
            seasonId: null,
            episodeNumber: '',
        };
    },
    methods: {
        openSeriesModal() {
            this.showSeriesModal = true;
        },
        openSeasonModal() {
            this.showAddSeasonModal = true;
        },
        openEpisodeModal() {
            this.showAddEpisodeModal = true;
        },

        async fetchSeriesOptions() {
            try {
                const response = await axios.get('/api/series');
                this.seriesOptions = response.data.map(series => ({
                    value: series.id,
                    text: series.title,
                }));
            } catch (error) {
                console.error('Помилка при завантаженні серіалів:', error);
                alert('Не вдалося завантажити серіали.');
            }
        },

        async fetchSeasons() {
            if (!this.seriesId) return;

            try {
                const response = await axios.get(`/api/series/${this.seriesId}/seasons`);
                this.seasonsOptions = response.data.map(season => ({
                    value: season.id,
                    text: `${season.title} (Сезон ${season.season_number})`,
                }));
            } catch (error) {
                console.error('Помилка завантаження сезонів:', error);
                this.seasonsOptions = [];
            }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.poster = file;
            } else {
                this.poster = null;
            }
        },

        async addSeries() {
            let formData = new FormData();
            formData.append('title', this.seriesTitle);
            formData.append('description', this.seriesDescription);
            if (this.poster) {
                formData.append('poster', this.poster);
            }

            try {
                await axios.post('/api/series', formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'multipart/form-data',
                    },
                });

                this.fetchSeriesOptions();
                this.seriesTitle = '';
                this.seriesDescription = '';
                this.poster = null;
                this.showSeriesModal = false;
                alert('Серіал успішно додано!');
            } catch (error) {
                console.error(error);
                alert('Сталася помилка при додаванні серіалу.');
            }
        },

        async handleVideoChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            let formData = new FormData();
            formData.append('video', file);

            try {
                const response = await axios.post('/api/upload-video', formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'multipart/form-data',
                    },
                });
                this.episodeVideoUrl = response.data.path;
                alert('Відео успішно завантажено!');
            } catch (error) {
                console.error('Помилка завантаження відео:', error);
                alert('Не вдалося завантажити відео.');
            }
        },

        async addSeason() {
            try {
                await axios.post(`/api/series/${this.seriesId}/seasons`, {
                    title: this.seasonTitle,
                    season_number: this.seasonNumber,
                }, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                this.showAddSeasonModal = false;
                alert('Сезон успішно додано!');
            } catch (error) {
                console.error(error.response.data.message);
                alert('Помилка при додаванні сезону');
            }
        },

        async addEpisode() {
            if (!this.episodeVideoUrl) {
                alert('Будь ласка, завантажте відео перед додаванням епізоду.');
                return;
            }

            try {
                await axios.post(`/api/seasons/${this.seasonId}/episodes`, {
                    title: this.episodeTitle,
                    episode_number: this.episodeNumber,
                    video_url: this.episodeVideoUrl,
                }, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                });

                this.showAddEpisodeModal = false;
                alert('Епізод успішно додано!');
            } catch (error) {
                console.error(error.response.data.message);
                alert('Помилка при додаванні епізоду');
            }
        },
    },
    created() {
        this.fetchSeriesOptions();
    },
};
</script>

<style scoped>
h1 {
    margin-bottom: 1rem;
}
.b-button {
    margin: 5px;
}
</style>
