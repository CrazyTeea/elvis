<script setup>

import MonkeyMonitor2 from "@/components/organisms/MonkeyMonitor2.vue";
import {useExperiment2Store} from "@/store/experiment2Store.js";
import {computed, onMounted, watch} from "vue";

const experimentStore = useExperiment2Store()
const isActive = computed(() => experimentStore.getActive)
const line = computed(() => experimentStore.line)
const position = computed(() => experimentStore.position)

const chanel = new BroadcastChannel('experiment-2-kek');

let w = window.innerWidth;
let h = window.innerHeight

onMounted(() => {
    window.addEventListener('beforeunload', (event) => {
        event.preventDefault()
        localStorage.setItem('is_window', 'false')
        experimentStore.is_window = false
    })
})


</script>

<template>

    <v-card oncontextmenu="return false" class="no-cursor" :width="w" :height="h">
        <monkey-monitor2 :line="line" :position="position" :active="isActive"/>
    </v-card>


</template>

<style scoped>

</style>
