<template>
    <div>
        <video
            id="video-player"
            class="video-js vjs-default-skin"
            controls
            preload="auto"
            :data-setup="{}"
            :poster="poster"
        >
            <source :src="videoUrl" type="video/mp4" />
            Ваш браузер не підтримує відтворення відео.
        </video>
    </div>
</template>

<script>
import videojs from 'video.js';

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
        this.player = videojs(this.$refs.videoPlayer, {}, function onPlayerReady() {
            console.log('Player is ready!');
        });
    },
    beforeDestroy() {
        if (this.player) {
            this.player.dispose();
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
