<template>
    <div class="series-list">
        <h1 class="title">Серіали</h1>
        <div v-if="isLoading" class="loading">Завантаження...</div>
        <div v-else>
            <div v-if="seriesList && seriesList.length" class="series-grid">
                <div 
                    v-for="series in seriesList" 
                    :key="series.id" 
                    class="series-card"
                >
                    <img 
                        :src="series.poster ? `/storage/${series.poster}` : defaultPoster" 
                        alt="Постер серіалу" 
                        class="series-poster"
                    />
                    <router-link :to="`/series/${series.id}`" class="series-title">
                        {{ series.title }}
                    </router-link>
                    <p class="series-description">{{ series.description || 'Без опису' }}</p>
                </div>
            </div>
            <div v-else class="no-series">Серіали відсутні</div>
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
            console.log("Series in Vuex State:", this.$store.state.series);
            return this.$store.state.series || [];
        },
    },
    created() {
        this.$store
            .dispatch('fetchSeries')
            .then(() => {
                console.log("Data fetched successfully.");
            })
            .catch((error) => {
                console.error("Error fetching series:", error);
            })
            .finally(() => {
                this.isLoading = false;
            });
    },
};
</script>


<style scoped>
.series-list {
    padding: 20px;
    text-align: center;
}

.title {
    font-size: 2rem;
    margin-bottom: 20px;
}

.loading {
    font-size: 1.5rem;
    color: #777;
}

.series-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.series-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 10px;
    background-color: #fff;
    transition: box-shadow 0.3s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.series-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.series-poster {
    width: 100%;
    height: auto;
    border-radius: 5px;
}

.series-title {
    display: block;
    margin: 10px 0;
    font-weight: bold;
    color: #007bff;
    text-decoration: none;
}

.series-title:hover {
    text-decoration: underline;
}

.series-description {
    font-size: 0.9rem;
    color: #555;
}

.no-series {
    font-size: 1.5rem;
    color: #777;
}
</style>
