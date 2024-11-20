<template>
    <div>
        <h1>Адмін-панель</h1>
        <b-button variant="primary" @click="showAddSeriesModal">Додати серіал</b-button>
        <b-modal v-model="showSeriesModal" title="Додати серіал">
            <b-form @submit.prevent="addSeries">
                <b-form-group label="Назва серіалу">
                    <b-form-input v-model="seriesTitle" required></b-form-input>
                </b-form-group>
                <b-form-group label="Опис">
                    <b-form-textarea v-model="seriesDescription" required></b-form-textarea>
                </b-form-group>
                <b-form-group label="Постер">
                    <b-form-file v-model="poster" accept="image/*" />
                </b-form-group>
                <b-button type="submit" variant="success">Додати</b-button>
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
            seriesTitle: '',
            seriesDescription: '',
            poster: null,
        };
    },
    methods: {
        showAddSeriesModal() {
            this.showSeriesModal = true;
        },
        async addSeries() {
            let formData = new FormData();
            formData.append('title', this.seriesTitle);
            formData.append('description', this.seriesDescription);
            if (this.poster) {
                formData.append('poster', this.poster);
            }

            try {
                const response = await axios.post('/series', formData, {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                        'Content-Type': 'multipart/form-data',
                    },
                });

                this.showSeriesModal = false;
                alert('Серіал успішно додано!');
            } catch (error) {
                console.error(error.response.data.message);
                alert('Сталася помилка!');
            }
        },
    },
};
</script>

<style scoped>
h1 {
    margin-bottom: 1rem;
}
</style>
