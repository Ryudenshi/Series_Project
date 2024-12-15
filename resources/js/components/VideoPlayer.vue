<template>
    <div>
        <video
            ref="videoPlayer"
            class="video-js vjs-default-skin"
            controls
            preload="auto"
            :poster="poster"
        >
            <source :src="videoUrl" type="video/mp4" codecs="avc,aac\" />
            Ваш браузер не підтримує відтворення відео.
        </video>
    </div>
</template>

<script>
import videojs from "video.js";
import "video.js/dist/video-js.css";

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
            console.error("Помилка: URL відео відсутній або недійсний");
            return;
        }

        // Перевіряємо формат посилання (AWS або інше)
        if (!this.isValidVideoUrl(this.videoUrl)) {
            console.error(
                `Недійсна URL для відео: ${this.videoUrl}. Перевірте, чи є це посиланням AWS або Presigned URL.`
            );
            return;
        }

        // Ініціалізація Video.js
        this.player = videojs(this.$refs.videoPlayer, {}, () => {
            console.log("Video player is ready");
        });

        this.player.on("error", () => {
            const error = this.player.error();
            console.error("VideoJS Error:", error);
        });
    },
    beforeUnmount() {
        if (this.player) {
            this.player.dispose();
            this.player = null;
        }
    },
    methods: {
    isValidVideoUrl(url) {
        // Базовий шаблон для AWS S3
        const awsRegex = /^https:\/\/.*\.s3\.[a-z0-9-]+\.amazonaws\.com\/.+$/;
        // Шаблон для Presigned URL
        const presignedRegex = /^https:\/\/.*\?.*X-Amz-Signature=.*$/;

        // Вивід для налагодження
        console.log('Перевірка URL:', url);

        // Перевірка відповідності шаблонам
        return awsRegex.test(url) || presignedRegex.test(url);
    },
}
};
</script>

<style scoped>
.video-js {
    width: 100%;
    height: 400px;
}
</style>
