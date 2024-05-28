<script setup>
import line from '@images/Line 6.svg'
import {useExperimentStore} from "@/store/experiment1Store.js";
const experimentStore = useExperimentStore()
</script>

<template>
    <v-row class="ml-0 mr-0">
        <v-col cols="1" class="text-end pr-0">
            <input v-model="experimentStore.line.maxCount" class="input">
        </v-col>
        <v-col class="ml-0 pl-0 pt-4">
            <div class="position-relative">
                <v-img class="pl-0 ml-0" :src="line"/>
                <div style="left: 8%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2 ml-2" size="large">mdi-volume-high</v-icon>
                    <br><br><br>
                    <span>Сигнал</span>
                </div>
                <div style="left: 20%; top: -20%" class="position-absolute">
                    <input v-model="experimentStore.line.startDelay" class="input">
                </div>
                <div style="left: 35%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2 ml-2" size="large">mdi-image</v-icon>
                    <br><br><br>
                    <span>Стимул</span>
                </div>
                <div style="left: 50%; top: -20%" class="position-absolute">
                    <input v-model="experimentStore.line.showRange.min" class="mr-2 input">
                    <input v-model="experimentStore.line.showRange.max" class="input">
                </div>
                <div style="left: 70%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2 ml-2" size="large">mdi-pause</v-icon>
                    <br><br><br>
                    <span>Пауза</span>
                </div>
                <div style="left: 80%; top: -20%" class="position-absolute">
                    <input v-model="experimentStore.line.stopRange.min" class="mr-2 input">
                    <input v-model="experimentStore.line.stopRange.max" class="input">
                </div>
                <div style="left: 98%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2" size="large">mdi-hospital</v-icon>
                    <br><br><br>
                    <span>Стоп</span>
                </div>
            </div>
        </v-col>
        <v-col cols="1">
            <slot/>
        </v-col>
    </v-row>
</template>

<style scoped>
.input {
    background: rgba(175, 175, 175, 1);
    width: 50px;
    border-radius: 5px;
    padding-left: 5px;
}
</style>
