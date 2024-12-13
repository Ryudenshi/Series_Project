<template>
    <b-navbar type="light" variant="light" class="custom-navbar">
        <b-container>
            <div class="navbar-content">
                <b-navbar-brand href="http://localhost/my_series_app/public/" class="navbar-brand">
                    SERIESMEGA
                </b-navbar-brand>
                <b-navbar-nav class="ml-auto">
                    <!-- Кнопка для адміністратора -->
                    <b-nav-item v-if="isAuthenticated && isAdmin" to="/admin" router>
                        Admin panel
                    </b-nav-item>

                    <!-- Кнопки для неавторизованих користувачів -->
                    <b-nav-item v-if="!isAuthenticated" to="/login" router>
                        Sing-in
                    </b-nav-item>
                    <b-nav-item v-if="!isAuthenticated" to="/register" router>
                        Registration
                    </b-nav-item>

                    <!-- Кнопка виходу -->
                    <b-nav-item v-if="isAuthenticated" @click="logout" href="#">
                        Logout
                    </b-nav-item>
                </b-navbar-nav>
            </div>
        </b-container>
        <div class="navbar-bottom-line"></div>
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
                        Authorization: `Bearer ${token}`
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
.custom-navbar {
    background-color: #333;
    color: #333;
    border-bottom: 2px solid #f1c40f;
    position: relative;
}

.navbar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-brand {
    font-size: 1.8rem;
    color: #f1c40f;
    font-weight: bold;
}

.b-navbar-nav {
    display: flex;
    justify-content: flex-end;
}

.b-nav-item {
    color: #333;
    font-size: 1.1rem;
    margin-left: 20px;
    transition: color 0.3s ease;
}

.b-nav-item:hover {
    color: #f1c40f;
}

.navbar-bottom-line {
    width: 100%;
    height: 2px;
    background-color: #f1c40f;
    position: absolute;
    bottom: 0;
    left: 0;
}

@media (max-width: 768px) {
    .navbar-brand {
        font-size: 1.5rem;
    }

    .b-nav-item {
        font-size: 1rem;
        margin-left: 15px;
    }
}
</style>
