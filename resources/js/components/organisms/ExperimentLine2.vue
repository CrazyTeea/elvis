<script setup>
import line from '@images/Line 6.svg'
import {useExperiment2Store} from "@/store/experiment2Store.js";
const experimentStore = useExperiment2Store()
</script>

<template>
    <v-row class="ml-0 mr-0">
        <v-col cols="1" class="text-end pr-0">
            <input v-model="experimentStore.line.countProbs">
        </v-col>
        <v-col class="ml-0 pl-0 pt-4">
            <div class="position-relative">

                <v-img class="pl-0 ml-0" :src="line"/>
                <div style="left: 3%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2" size="large">mdi-volume-high</v-icon>
                    <br><br><br>
                    <span>Сигнал</span>
                </div>
                <div style="left: 10%; top: -30%" class="position-absolute">
                    <input v-model="experimentStore.line.startDelay" >
                </div>
                <div style="left: 20%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2" size="large">mdi-weather-sunny</v-icon>
                    <br><br><br>
                    <span>Стимул</span>
                </div>
                <div style="left: 30%; top: -30%" class="position-absolute">
                    <input v-model="experimentStore.line.startHelp.min" class="mr-2" >
                    <input v-model="experimentStore.line.startHelp.max" >
                </div>
                <div style="left: 43%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2" size="large">mdi-image</v-icon>
                    <br><br><br>
                    <span>Подсказка</span>
                </div>
                <div style="left: 50%; top: -30%" class="position-absolute">
                    <input v-model="experimentStore.line.waitQuestion.min" class="mr-2" >
                    <input v-model="experimentStore.line.waitQuestion.max" >
                </div>
                <div style="left: 65%; top: -250%" class="position-absolute">
                    <v-icon color="blue-grey-darken-2" size="large">mdi-image</v-icon>
                    <br><br><br>
                    <span>Пауза</span>
                </div>
                <div style="left: 75%; top: -30%" class="position-absolute">
                    <input v-model="experimentStore.line.stopDelay.min" class="mr-2" >
                    <input v-model="experimentStore.line.stopDelay.max" >
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
input {
    background: rgba(175, 175, 175, 1);
    width: 50px;
    border-radius: 5px;
    padding-left: 5px;
}
</style>
