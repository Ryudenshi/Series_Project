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
                <b-form-group label="ID серіалу">
                    <b-form-input type="number" v-model="seriesId" required></b-form-input>
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
                <b-form-group label="ID сезону">
                    <b-form-input type="number" v-model="seasonId" required></b-form-input>
                </b-form-group>
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
            seriesId: null, // ID серіалу для додавання сезону
            seasonId: null, // ID сезону для додавання епізоду
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
        const response = await axios.post('/api/series', formData, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`,
                'Content-Type': 'multipart/form-data',
            },
        });

        this.$store.dispatch('fetchSeries'); // Оновлення списку серіалів
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
            try {
                await axios.post(`/api/seasons/${this.seasonId}/episodes`, {
                    title: this.episodeTitle,
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
