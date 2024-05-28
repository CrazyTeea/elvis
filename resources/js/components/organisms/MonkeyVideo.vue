<script setup>
defineProps({
    active: Boolean
})
</script>

<template>
    <div class="h-100 w-100">
        <video v-if="active" class="h-100 w-100"
               src="https://archive.org/download/BigBuckBunny_124/Content/big_buck_bunny_720p_surround.mp4"
               controls>
        </video>
        <div v-else class="text-center h-100 align-content-center">
            <p>Видео глаза</p>
        </div>
    </div>

</template>

<style scoped>

</style>
