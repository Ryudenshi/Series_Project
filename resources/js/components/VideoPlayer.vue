<template>
    <div>
        <video ref="videoPlayer" class="video-js vjs-default-skin" controls preload="auto" :poster="poster">
            <source :src="videoUrl" type="video/mp4" />
            Ваш браузер не підтримує відтворення відео.
        </video>
    </div>
</template>

<script>
import videojs from 'video.js';
import 'video.js/dist/video-js.css';

export default {
    props: {
        videoUrl: {
            type: String,
            required: true,
        },
        poster: {
            type: String,
            default: null,
        },
    },
    mounted() {
        if (!this.videoUrl) {
            console.error('Помилка: URL відео відсутній або недійсний');
            return;
        }

        this.player = videojs(this.$refs.videoPlayer, {}, () => {
            console.log('Video player is ready');
        });

        this.player.on('error', () => {
            const error = this.player.error();
            console.error('VideoJS Error:', error);
        });
    },
    beforeUnmount() {
        if (this.player) {
            this.player.dispose();
            this.player = null;
        }
    },
};
</script>

<style scoped>
.video-js {
    width: 100%;
    height: 400px;
}
</style>