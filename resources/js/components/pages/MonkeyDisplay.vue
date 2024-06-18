<script setup>

import MonkeyMonitor from "@/components/organisms/MonkeyMonitor.vue";

import {useExperimentStore} from "@/store/experiment1Store.js";
import {computed, onMounted} from "vue";

const experimentStore = useExperimentStore()
const isActive = computed(() => experimentStore.getActive)
const data = computed(() => experimentStore.getData)


let w = window.innerWidth;
let h = window.innerHeight

onMounted(()=>{
    window.addEventListener('beforeunload', (event)=>{
        event.preventDefault()
        experimentStore.is_window = false
    })
})

</script>

<template>

    <v-card oncontextmenu="return false" class="no-cursor" :width="w" :height="h">
        <monkey-monitor :active="isActive" :figure="data.figure" :oblast="data.oblast"/>
    </v-card>


</template>

<style scoped>

</style>
