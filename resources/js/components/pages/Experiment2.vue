<script setup>

import ExperimentTemplate from "@/components/templates/ExperimentTemplate.vue";
import ExperimentLine2 from "@/components/organisms/ExperimentLine2.vue";
import PositionPicker from "@/components/organisms/PositionPicker.vue";
import {onMounted, ref} from "vue";
import BlickPicker from "@/components/organisms/BlickPicker.vue";
import HelpersPicker from "@/components/organisms/HelpersPicker.vue";
import {useExperiment2Store} from "@/store/experiment2Store.js";
import {SuperTimer} from "@mixins/utils.js";
import MonkeyVideo from "@/components/organisms/MonkeyVideo.vue";
import {useMonkeyStore} from "@/store/api/monkey.js";
import {deleteFile, downloadFile, fileHeaders, generateFile, getFiles} from "@mixins/files.js";

const experimentStore = useExperiment2Store()
const monkeyStore = useMonkeyStore()

const btn = ref({
    btn1: false,
    btn2: false
})

const monkey = ref({})
const files = ref([])
const props = defineProps(['monkey_id'])

const btnOff = () => btn.value.btn1 = btn.value.btn2 = false;
const bttHandler = (b) => {
    btnOff()
    btn.value[b] = true
}

onMounted(async () => {
    monkey.value = await monkeyStore.getMonkey(props.monkey_id)
    files.value = await getFiles(2, props.monkey_id)
})

const doSetup = async () => {
    const screenDetails = await window.getScreenDetails()

    const newChildWindow = window.open(
        '/experiment-2-monitor',
        '_blank',
        `popup=1,width=${1920},height=${1080},left=0,top=0`
    )
    newChildWindow.moveTo(screenDetails.screens[0].left, 0)

}

const run = async () => {
    btnOff()
    let {helpers, positions, line} = experimentStore
    experimentStore.$reset()
    experimentStore.helpers = helpers
    experimentStore.positions = positions
    experimentStore.line = line
    await doSetup()
    let l = new SuperTimer();
    await l.sleep(1000)
    await experimentStore.runExperiment()
    experimentStore.setActive(false)

}
const stopExp = async () => {
    experimentStore.setActive(false)
}


</script>

<template>
    <experiment-template>
        <div v-if="!experimentStore.active">
            <experiment-line2>
                <v-btn @click="run" icon="mdi-play"/>
            </experiment-line2>
            <div class="d-flex mt-10 justify-center ga-16">
                <v-btn @click="bttHandler('btn1')" icon="mdi-pencil"/>
                <v-btn @click="bttHandler('btn2')" class="ml-16" icon="mdi-pencil"/>
            </div>
            <div v-if="btn.btn1" class="d-flex mt-5 justify-center ga-10">
                <position-picker v-model="experimentStore.positions"/>
                <blick-picker v-model="experimentStore.stimul"/>
            </div>
            <div v-if="btn.btn2" class="d-flex mt-5 justify-center ga-10">
                <helpers-picker v-model="experimentStore.helpers"/>
            </div>
        </div>
        <div v-else>
            <v-row>
                <v-col cols="11">
                    <v-card height="400">
                        <v-card-text v-html="experimentStore.comment"/>
                    </v-card>
                </v-col>
                <v-col>
                    <v-btn @click="stopExp" icon="mdi-stop"/>
                </v-col>
            </v-row>
        </div>
        <template #footer>
            <v-row>
                <v-col lg="300" cols="5">
                    <v-card min-width="600" height="317" border="lg">

                        <iframe id="scaled-frame" src="/experiment-2-monitor" width="100%" height="100%"/>


                        <!--                        <monkey-monitor ref="monitor" :active="experiment.active" :figure="getFigure"
                                        :oblast="oblast.options"/>-->
                    </v-card>
                </v-col>
                <v-col md>
                    <v-card min-width="300" height="317" border="lg">
                        <monkey-video/>
                    </v-card>

                </v-col>
                <v-col sm cols="1">
                    <v-card min-width="300" height="317" border="lg">
                        <div class="h-100 w-100 pa-4">
                            <p class="text-center">павиан {{ monkey.id }} </p>
                            <p class="text-center text-disabled">{{ monkey.elvis_id }}</p>
                            <div class="ml-12 mr-12 pl-12 pr-12">
                                <div class="d-flex mb-8 justify-space-between">
                                    <div>Количество проб</div>
                                    <div>{{ experimentStore.line.current }}/{{ experimentStore.line.maxCount }}</div>
                                </div>
                                <div class="d-flex mb-8 justify-space-between">
                                    <div>Правильные ответы</div>
                                    <div>{{ experimentStore.getTrueValue }}</div>
                                </div>
                                <div class="d-flex justify-space-between">
                                    <div>Среднее время реакции</div>
                                    <div>{{ experimentStore.getReactionTime }}</div>
                                </div>
                            </div>
                            <div style="height:50px;" class="d-flex mt-5 align-center justify-center">
                                <v-dialog max-width="600">
                                    <template v-slot:activator="{ props: kek }">
                                        <v-btn
                                            v-bind="kek"
                                            @click="getFiles(2, monkey_id)"
                                            text="Посмотреть файл"
                                            variant="outlined"
                                        ></v-btn>
                                    </template>

                                    <template v-slot:default="{ isActive }">
                                        <v-card title="Файлы">
                                            <v-container>
                                                <div class="d-flex justify-end">
                                                    <v-btn color="blue" @click="generateFile">Сгенерировать файл</v-btn>
                                                </div>
                                                <v-data-table :headers="fileHeaders" :items="files">
                                                    <template #item.actions={item}>
                                                        <v-icon
                                                            size="small"
                                                            @click="downloadFile(item.id)"
                                                        >
                                                            mdi-download
                                                        </v-icon>
                                                        <v-icon
                                                            size="small"
                                                            @click="deleteFile(item.id)"
                                                        >
                                                            mdi-delete
                                                        </v-icon>
                                                    </template>
                                                </v-data-table>
                                            </v-container>
                                        </v-card>
                                    </template>
                                </v-dialog>
                            </div>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </template>


    </experiment-template>
</template>

<style scoped>

</style>
