<template>
    <div class="series-list">
        <h1 class="title">Series</h1>
        <div v-if="isLoading" class="loading">Loadong...</div>
        <div v-else>
            <div v-if="seriesList && seriesList.length" class="series-grid">
                <router-link v-for="series in seriesList" :key="series.id" :to="`/series/${series.id}`"
                    class="series-card">
                    <img :src="series.poster ? `/my_series_app/storage/app/public/${series.poster}` : defaultPoster" alt="Постер серіалу"
                        class="series-poster" />
                    <div class="overlay">
                        <h3 class="series-title">{{ series.title }}</h3>
                    </div>
                </router-link>
            </div>
            <div v-else class="no-series">No series available</div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            defaultPoster: 'https://via.placeholder.com/150?text=No+Poster',
            isLoading: true,
        };
    },
    computed: {
        seriesList() {
            return this.$store.state.series || [];
        },
    },
    created() {
        this.$store
            .dispatch('fetchSeries')
            .finally(() => {
                this.isLoading = false;
            });
    },
};
</script>

<style scoped>
/* Основний контейнер */
.series-list {
    padding: 20px;
    background-color: #2c3e50;
    /* Темний фон */
    color: #fff;
}

/* Заголовок */
.title {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #f1c40f;
    text-align: left;
}

/* Контейнер для сітки */
.series-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 150px));
    /* Вертикальна форма */
    gap: 20px;
    justify-content: start;
    /* Початок з лівого верхнього кута */
}

/* Карточка серіалу */
.series-card {
    position: relative;
    overflow: hidden;
    height: 300px;
    /* Фіксована висота */
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-decoration: none;
    color: inherit;
}

.series-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

/* Зображення серіалу */
.series-poster {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}

/* Оверлей з інформацією */
.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(44, 62, 80, 0.9);
    /* Напівпрозорий темний фон */
    color: #fff;
    opacity: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: opacity 0.3s ease;
}

.series-card:hover .overlay {
    opacity: 1;
}

/* Назва серіалу */
.series-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #f1c40f;
    text-align: center;
}

/* Повідомлення про відсутність серіалів */
.no-series {
    font-size: 1.5rem;
    color: #f1c40f;
    text-align: center;
}
</style>
