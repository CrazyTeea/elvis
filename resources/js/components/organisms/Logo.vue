<script setup>
import elvis_logo from "@images/ELVIS logo color 2.svg";
import elvis_text from "@images/Week day, Day.svg";

const props = defineProps({
    size: String
})

</script>

<template>
    <div class="img" >
        <v-img
            width="300"
               aspect-ratio="16/9"
               cover :src="elvis_logo" />
        <v-img
            width="300"
               aspect-ratio="16/9"
               cover :src="elvis_text" />
    </div>
</template>

<style scoped>

</style>
