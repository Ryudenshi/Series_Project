<template>
    <b-navbar type="light" variant="light" class="mb-3">
        <b-container>
            <b-navbar-brand href="http://localhost/my_series_app/public/">Головна</b-navbar-brand>
            <b-navbar-nav>
                <!-- Кнопка для адміністратора -->
                <b-nav-item v-if="isAuthenticated && isAdmin" to="/admin" router>
                    Панель адміністратора
                </b-nav-item>

                <!-- Кнопки для неавторизованих користувачів -->
                <b-nav-item v-if="!isAuthenticated" to="/login" router>
                    Увійти
                </b-nav-item>
                <b-nav-item v-if="!isAuthenticated" to="/register" router>
                    Реєстрація
                </b-nav-item>

                <!-- Кнопка виходу -->
                <b-nav-item v-if="isAuthenticated" @click="logout" href="#">
                    Вийти
                </b-nav-item>
            </b-navbar-nav>
        </b-container>
    </b-navbar>
</template>

<script>
import axios from 'axios';

export default {
    computed: {
        isAuthenticated() {
            return !!localStorage.getItem('token');
        },
        isAdmin() {
            return localStorage.getItem('role') === 'admin'; // Перевірка ролі
        },
    },
    methods: {
        async logout() {
    try {
        const token = localStorage.getItem('token');
        if (!token) throw new Error('Token not found');

        const response = await axios.post('/api/logout', {}, {
            headers: {
                Authorization: `Bearer ${token}`,
            },
        });

        localStorage.removeItem('token');
        localStorage.removeItem('role');
        this.$router.push('/login');
    } catch (error) {
        console.error('Logout failed:', error.response?.data || error.message);
        alert('Сталася помилка під час виходу!');
    }
    }
    },
};
</script>

<style scoped>
</style>
