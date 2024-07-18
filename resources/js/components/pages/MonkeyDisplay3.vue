<script setup>

import MonkeyMonitor3 from "@/components/organisms/MonkeyMonitor3.vue";

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
        <monkey-monitor3 :active="isActive" :figure="data.figure" :oblast="data.oblast"/>
    </v-card>


</template>

<style scoped>

</style>
