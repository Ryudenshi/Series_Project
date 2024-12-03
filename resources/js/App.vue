<template>
    <div id="app" class="app-container">
        <Header />
        <router-view />
    </div>
</template>

<script>
import Header from './components/Header.vue';

export default {
    components: {
        Header,
    },
    name: 'App',
};
</script>

<style scoped>
.app-container {
    position: relative;
    min-height: 100vh;
    overflow: hidden;
    background: #0d0d0d; /* Темний фон для безодні */
    color: #fff;
}

/* Створення ефекту зірок */
.app-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('https://cdn.pixabay.com/photo/2017/08/30/00/52/space-2695561_960_720.jpg') no-repeat center center fixed;
    background-size: cover;
    z-index: -1;
    animation: starfield 60s linear infinite; /* Анімація з рухом зірок */
}

/* Анімація для зірок */
@keyframes starfield {
    0% {
        transform: translateY(0);
    }
    100% {
        transform: translateY(-100%);
    }
}

/* Додаткові стилі для контенту */
#app {
    position: relative;
    z-index: 1;
}
</style>
