<template>
    <b-container class="mt-5">
        <b-row class="justify-content-center">
            <b-col cols="6">
                <b-card title="Реєстрація">
                    <b-form @submit.prevent="register">
                        <b-form-group label="Ім'я" label-for="name">
                            <b-form-input
                                id="name"
                                type="text"
                                v-model="name"
                                required
                                placeholder="Введіть своє ім'я"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group label="Email" label-for="email">
                            <b-form-input
                                id="email"
                                type="email"
                                v-model="email"
                                required
                                placeholder="Введіть email"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group label="Пароль" label-for="password">
                            <b-form-input
                                id="password"
                                type="password"
                                v-model="password"
                                required
                                placeholder="Введіть пароль"
                            ></b-form-input>
                        </b-form-group>
                        <b-button type="submit" variant="primary" block>Зареєструватись</b-button>
                    </b-form>
                </b-card>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
        };
    },
    methods: {
        async register() {
            try {
                const response = await axios.post('http://localhost/api/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                });

                // Зберегти токен в localStorage
                localStorage.setItem('token', response.data.access_token);

                // Перенаправлення після успішної реєстрації
                this.$router.push('/');
            } catch (error) {
                console.error(error.response.data.message);
            }
        },
    },
};
</script>
