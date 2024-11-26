<template>
    <div>
        <h1>Адмін-панель</h1>
        
        <!-- Кнопка для додавання серіалу -->
        <b-button variant="primary" @click="showAddSeriesModal">Додати серіал</b-button>

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
        <b-modal v-model="showAddSeasonModal" title="Додати сезон">
            <b-form @submit.prevent="addSeason">
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
        <b-modal v-model="showAddEpisodeModal" title="Додати епізод">
            <b-form @submit.prevent="addEpisode">
                <b-form-group label="Назва епізоду">
                    <b-form-input v-model="episodeTitle" required></b-form-input>
                </b-form-group>
                <b-form-group label="Відео URL">
                    <b-form-input v-model="episodeVideoUrl" required></b-form-input>
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
            seriesId: null, // ID серіалу після його створення
            seasonId: null, // ID сезону після його створення
        };
    },
    methods: {
        // Відкриваємо модальне вікно для додавання серіалу
        showAddSeriesModal() {
            this.showSeriesModal = true;
        },

        // Обробка вибору файлу для постера
        handleFileChange(event) {
            this.poster = event.target.files[0];
        },

        // Додаємо серіал
        async addSeries() {
            let formData = new FormData();
            formData.append('title', this.seriesTitle);
            formData.append('description', this.seriesDescription);
            if (this.poster) {
                formData.append('poster', this.poster);
            }

            try {
                const response = await axios.post('/api/series', formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'multipart/form-data',
                    },
                });

                // Зберігаємо ID серіалу для наступних операцій
                this.seriesId = response.data.id;

                this.showSeriesModal = false;
                this.showAddSeasonModal = true; // Відкриваємо форму для додавання сезону

                alert('Серіал успішно додано!');
            } catch (error) {
                console.error(error.response.data.message);
                alert('Помилка при додаванні серіалу');
            }
        },

        // Додаємо сезон
        async addSeason() {
            try {
                const response = await axios.post(`/api/series/${this.seriesId}/seasons`, {
                    title: this.seasonTitle,
                    season_number: this.seasonNumber,
                }, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    }
                });

                // Зберігаємо ID сезону для додавання епізодів
                this.seasonId = response.data.id;

                this.showAddSeasonModal = false;
                this.showAddEpisodeModal = true; // Відкриваємо форму для додавання епізоду

                alert('Сезон успішно додано!');
            } catch (error) {
                console.error(error.response.data.message);
                alert('Помилка при додаванні сезону');
            }
        },

        // Додаємо епізод
        async addEpisode() {
            try {
                const response = await axios.post(`/api/seasons/${this.seasonId}/episodes`, {
                    title: this.episodeTitle,
                    video_url: this.episodeVideoUrl,
                }, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    }
                });

                this.showAddEpisodeModal = false;

                alert('Епізод успішно додано!');
            } catch (error) {
                console.error(error.response.data.message);
                alert('Помилка при додаванні епізоду');
            }
        },
    },
};
</script>

<style scoped>
h1 {
    margin-bottom: 1rem;
}

.b-button {
    margin-top: 10px;
}

.b-form-group {
    margin-bottom: 15px;
}
</style>
