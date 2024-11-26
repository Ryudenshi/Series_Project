<template>
    <b-navbar type="light" variant="light" class="mb-3">
        <b-container>
            <b-navbar-brand href="/">Головна</b-navbar-brand>
            <b-navbar-nav>
                <b-nav-item v-if="!isAuthenticated" to="/login" router>
                    Увійти
                </b-nav-item>
                <b-nav-item v-if="!isAuthenticated" to="/register" router>
                    Реєстрація
                </b-nav-item>
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
    },
    methods: {
        async logout() {
            try {
                await axios.post('/api/logout', {}, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`,
                    },
                });
                localStorage.removeItem('token');
                this.$router.push('/login');
            } catch (error) {
                console.error('Logout failed', error);
            }
        },
    },
};
</script>

<style scoped>
</style>
