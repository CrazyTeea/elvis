<script setup>

import MonkeyMonitor4 from "@/components/organisms/MonkeyMonitor4.vue";

import {useExperiment3Store} from "@/store/experiment3Store.js";
import {computed, onMounted} from "vue";

const experimentStore = useExperiment3Store()
const isActive = computed(() => experimentStore.getActive)
const data = computed(() => experimentStore.getData)


let w = window.innerWidth;
let h = window.innerHeight

onMounted(() => {
    window.addEventListener('beforeunload', (event) => {
        event.preventDefault()
        experimentStore.is_window = false
    })
})

</script>

<template>

    <v-card oncontextmenu="return false" class="no-cursor" :width="w" :height="h">
        <monkey-monitor4 :active="isActive" :figure="data.figure" :oblast="data.oblast"/>
    </v-card>


</template>

<style scoped>

</style>
