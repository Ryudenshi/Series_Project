<template>
    <b-container class="mt-5">
        <b-row class="justify-content-center">
            <b-col cols="6">
                <b-card title="Вхід">
                    <b-form @submit.prevent="login">
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
                        <b-button type="submit" variant="primary">Увійти</b-button>
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
            email: '',
            password: '',
        };
    },
    methods: {
        async login() {
            try {
                const response = await axios.post('/login', {
                    email: this.email,
                    password: this.password,
                });

                localStorage.setItem('token', response.data.access_token);
                this.$router.push('/');
            } catch (error) {
                console.error(error.response.data.message);
            }
        },
    },
};
</script>
