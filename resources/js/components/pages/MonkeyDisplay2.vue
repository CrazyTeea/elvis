<script setup>

import MonkeyMonitor2 from "@/components/organisms/MonkeyMonitor2.vue";
import {useExperiment2Store} from "@/store/experiment2Store.js";
import {computed} from "vue";
const experimentStore = useExperiment2Store()
const isActive = computed(() => experimentStore.getActive)


let w = window.innerWidth;
let h = window.innerHeight

</script>

<template>

    <v-card :width="w" :height="h">
        <monkey-monitor2 :active="isActive"/>
    </v-card>


</template>

<style scoped>

</style>
