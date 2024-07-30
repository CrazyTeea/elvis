<script setup>
import ExperimentTemplate from "@/components/templates/ExperimentTemplate.vue";
import ExperimentLine3 from "@/components/organisms/ExperimentLine3.vue";
import {computed, onMounted, ref, watch} from "vue";
import {SVG} from '@svgdotjs/svg.js'
import FigurePicker from "@/components/organisms/FigurePicker.vue";
import VolumePicker from "@/components/organisms/VolumePicker.vue";
import svgMixin from "@/mixins/svgMixin.js"
import MonkeyVideo from "@/components/organisms/MonkeyVideo.vue";
import {useExperiment3Store} from "@/store/experiment3Store.js";
import {useMonkeyStore} from "@/store/api/monkey.js";
import {Figure} from "@/classes/Figure.js";
import {SuperTimer} from "@mixins/utils.js";
import {deleteFile, downloadFile, fileHeaders, generateFile, getFiles} from "@mixins/files.js";
import HelpersPicker from "@/components/organisms/HelpersPicker.vue";
import {Experiment} from "@/classes/Experiment.js";

const {triangle} = svgMixin()
let figures = ref([])
const props = defineProps({
    monkey_id: String
})
const experimentStore = useExperiment3Store();
const monkeyStore = useMonkeyStore()
const monkey = ref({id: props.monkey_id})
const monitor = ref(null)

const btns = ref({
    active: false,
    'rectangle3': {
        click: true,
        options: ref({
            color: [],
            brightness: {min: 10, max: 50},
            size: {min: 10, max: 50},
            angle: [0, 90],
            xx: [],
            yy: [],
            angle_value: 0,
            show_time: 2000
        },)
    },
});
const files = ref([])
const fPicker = ref({
    show: false,
    btn: ''
});
const hPicker = ref({shaw: false})
const experiment = computed(() => ({
    active: experimentStore.getActive,
    data: experimentStore.getData,
    result: experimentStore.getResult,
    line: experimentStore.getLine
}))
let bc = new BroadcastChannel('timer-event3');
onMounted(async () => {
    experimentStore.monkey_id = props.monkey_id
    monkey.value = await monkeyStore.getMonkey(props.monkey_id)
    let granted = false;
    try {
        const {state} = await navigator.permissions.query({name: 'window-management'});
        granted = state === 'granted';
    } catch {
        // Nothing.
    }
    files.value = await getFiles(3, props.monkey_id)


    bc.addEventListener('message', function (e) {
        console.log('click')
        experimentStore.stopTimer()
    })

    window.addEventListener('beforeunload', ()=>{
        console.log('unmount')
        saveValues()
        experimentStore.saveValues()
    })

    fPicker.value.btn = 'rectangle3'

    restoreValues()
    experimentStore.restoreValues()

})

function saveValues() {
    localStorage.setItem('figures3', JSON.stringify(figures.value))
    localStorage.setItem('btns3', JSON.stringify(btns.value))
    localStorage.setItem('oblast3', JSON.stringify(oblast.value))
    localStorage.setItem('helper3', JSON.stringify(helpers.value))
}

function restoreValues() {
    let figs = localStorage.getItem('figures3')
    if (figs) {
        figures.value = JSON.parse(figs)
    }
    let btts = localStorage.getItem('btns3')
    if (btts) {
        btns.value = JSON.parse(btts)
    }
    let obl = localStorage.getItem('oblast3')
    if (obl) {
        oblast.value = JSON.parse(obl)
    }
    let helper3 = localStorage.getItem('figures3')
    if (helper3) {
        helpers.value = JSON.parse(helper3)
    }

}

let helpers = ref([])

const oblast = ref({
    show: false,
    options: {
        brightness: {min: 0, max: 0},
        position: {x1: 10, x2: 200, y1: 10, y2: 300}
    }
})
const openOblast = () => {
    oblast.value.show = !oblast.value.show;
    fPicker.value.show = false;
}

watch(btns, () => {
    updateFigures()
}, {deep: true})

const doSetup = async () => {
    const screenDetails = await window.getScreenDetails()

    const newChildWindow = window.open(
        '/experiment-3-monitor',
        '_blank',
        `popup=1,left=0,top=0`
    )
    newChildWindow.resizeTo(screen.width, screen.height);
    newChildWindow.moveTo(screenDetails.screens[0].left, 0)

}


const startExperiment = async () => {

    experimentStore.reset(oblast);

    btns.value.active = false
    fPicker.value.show = false
    oblast.value.show = false

    experimentStore.data.helpers = helpers.value

    let exp = new Experiment({
            name: 'Стимуляция',
            number: 3,
            monkey_id: experimentStore.monkey_id,
            oblast: oblast.value
        }, helpers.value, null,
        null, experimentStore.line)
    await exp.storeExperiment()
    experimentStore.id = exp.id


    let figure = new Figure({id: exp.id, number: 3}, figures.value)
    figure.generate(oblast.value.options.position)
    await figure.store()

    let t = new SuperTimer()

    if (!experimentStore.is_window) {
        await doSetup()
        experimentStore.is_window = true
    }
    await t.sleep(5000)

    await experimentStore.runExperiment(figure)

}

const stopExperiment = async () => {
    experimentStore.reset(oblast)
    //await window.open('/experiment-1-monitor', '_blank')
}

const btnClick = (btnName) => {

    oblast.value.show = false;
    btns.value[btnName].click = !btns.value[btnName].click;

    if (['rectangle3'].indexOf(btnName) !== -1) {
        fPicker.value.btn = btnName;
        fPicker.value.show = btns.value[btnName].click;
    } else {
        fPicker.value.show = false;
        fPicker.value.btn = '';
    }
};

function updateFigures() {

    for (let btn in btns.value) {
        if (btn === 'active') continue
        let i = figures.value.findIndex((item) => {
            return item.name === btn
        })
        if (i !== -1 && !btns.value[btn].click) {
            figures.value.splice(i, 1)
        } else if (btns.value[btn].click) {
            if (!figures.value[i]) {
                figures.value.push({
                    name: btn,
                    options: btns.value[btn].options
                })
            } else {
                figures.value[i].options = btns.value[btn].options
            }
        }
    }


}

const rect = (w, btn) => {
    const draw = SVG();
    return draw
        .rect(w, 25).size(w, 25)
        .fill(btn ? 'purple' : 'grey')
        .parent()
        .svg();
}


const btnIconRect4 = computed(() => {
    return rect(5, btns.value.rectangle3.click)
})

const get_files = async () => {
    files.value = await getFiles(3, props.monkey_id)
}
const generate_file = async () => {
    await generateFile(3, props.monkey_id);
    await get_files()
}
const delete_file = async (id) => {
    await deleteFile(id)
    await get_files()
}

</script>

<template>
    <experiment-template>
        <template #default>

            <div v-if="!experiment.active">
                <experiment-line3>
                    <v-btn @click="startExperiment" variant="outlined" size="x-large"
                           icon="mdi-play-circle"></v-btn>
                </experiment-line3>


                <div class="mt-11">
                    <v-row>
                        <v-col cols="2">

                        </v-col>
                        <v-col cols="3" class="text-center">
                            <v-btn @click="()=>{
                            btns.active = !btns.active;
                            fPicker.show = btns.active;
                            oblast.show = false;
                            hPicker.shaw = false
                        }" class="shadow" :color="btns.active ? 'blue' : 'purple'" icon="mdi-pencil"/>
                        </v-col>
                        <v-col cols="2" class="text-center">
                            <v-btn @click="()=>{
                            btns.active = false;
                            oblast.show = false;
                            fPicker.show = false;
                            hPicker.shaw = true
                        }" class="shadow" :color="hPicker.shaw ? 'blue' : 'purple'" icon="mdi-pencil"/>
                        </v-col>
                        <v-col cols="3">

                        </v-col>
                    </v-row>
                    <v-row v-if="btns.active">
                        <v-col>
                            <div class="d-flex justify-center">
                                <v-card class="rounded-pill shadow d-flex justify-center align-center">
                                    <div class="pl-2 pr-2 pt-2 pb-2">

                                        <v-btn @click="btnClick('rectangle3')" width="5" size="sm" class="ml-2 mr-2">
                                            <div class="svg3" v-html="btnIconRect4"></div>
                                        </v-btn>
                                    </div>

                                </v-card>

                                <v-btn style="border-radius: 25px !important;" width="168" height="57"
                                       class="mt-1 ml-2 shadow" @click="openOblast">Фоновая
                                    область
                                </v-btn>

                            </div>

                        </v-col>
                        <v-col cols="1"></v-col>
                    </v-row>
                    <div v-if="fPicker.show">
                        <figure-picker experiment3 v-if="fPicker.btn === 'rectangle3'" v-model="btns.rectangle3.options"
                                       angle-picker/>
                    </div>
                    <div v-if="oblast.show">
                        <v-row>
                            <v-col class="d-flex justify-center">
                                <volume-picker v-model="oblast.options.brightness"/>
                                <v-card class="ml-3" width="150" height="127">
                                    <v-card-text>
                                        <div>
                                            <div class="d-flex justify-space-around">
                                                <input style="width: 50px" id="x2" v-model="oblast.options.position.x1"
                                                       placeholder="x1"
                                                       type="number">
                                                <div class="ml-2 mr-2">-</div>
                                                <input style="width: 50px" id="y1" v-model="oblast.options.position.y1"
                                                       placeholder="y1"
                                                       type="number">
                                            </div>

                                            <div class="d-flex mt-2 justify-space-around">
                                                <input style="width: 50px" id="x2" v-model="oblast.options.position.x2"
                                                       placeholder="x2"
                                                       type="number">
                                                <div class="ml-2 mr-2">-</div>
                                                <input style="width: 50px" id="y2" v-model="oblast.options.position.y2"
                                                       placeholder="y2"
                                                       type="number">
                                            </div>
                                        </div>

                                        <p class="text-center">Зона стимулов</p>

                                    </v-card-text>
                                </v-card>
                            </v-col>
                            <v-col cols="1"></v-col>
                        </v-row>

                    </div>
                    <div v-if="hPicker.shaw" class="d-flex mt-5 justify-center ga-10">
                        <helpers-picker experiment3 v-model="helpers"/>
                    </div>

                </div>
            </div>

            <div v-else>
                <v-row>
                    <v-col cols="1"></v-col>
                    <v-col>
                        <v-card height="450">
                            <div class="overflow-y-auto h-100">
                                <p v-html="experimentStore.text"></p>
                            </div>

                        </v-card>
                    </v-col>
                    <v-col cols="1">
                        <v-btn @click="stopExperiment" variant="outlined" size="x-large"
                               icon="mdi-stop-circle"></v-btn>
                        <v-btn @click="experimentStore.startStopPause" variant="outlined" size="x-large"
                               :icon="experimentStore.pause1 ? 'mdi-play-circle':'mdi-pause-circle'"></v-btn>
                    </v-col>
                </v-row>

            </div>

        </template>
        <template #footer>
            <v-row>
                <v-col lg="300" cols="5">
                    <v-card min-width="600" height="317" border="lg">

                        <iframe id="scaled-frame" src="/experiment-3-monitor" width="100%" height="100%"/>


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
                                    <div>{{ experiment.line.current }}/{{ experiment.line.maxCount }}</div>
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
                                            @click="get_files"
                                            text="Посмотреть файл"
                                            variant="outlined"
                                        ></v-btn>
                                    </template>

                                    <template v-slot:default="{ isActive }">
                                        <v-card title="Файлы">
                                            <v-container>
                                                <div class="d-flex justify-end">
                                                    <v-btn color="blue" @click="generate_file">Сгенерировать файл
                                                    </v-btn>
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
                                                            @click="delete_file(item.id)"
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

#scaled-frame {
    width: 200%;
    height: 200%;
    transform: scale(0.5);
    transform-origin: top left;
}

:deep(.svg) svg {
    height: 25px;
    width: 25px;
}

:deep(.svg2) svg {
    height: 25px;
    width: 35px;
}

:deep(.svg3) svg {
    height: 25px;
    width: 5px;
}

:deep(.rounded) svg {
    height: var(--h);
    width: var(--h);
}

:deep(.small) svg {
    height: var(--h1);
    width: var(--h1);
}

.shadow {
    box-shadow: -7px -7px 15px 0px rgba(51, 55, 60, 1), 14px 14px 20px 0px rgba(40, 42, 46, 1);

}

.shadow2 {
    box-shadow: -7px -7px 15px 0px rgba(51, 55, 60, 1), 14px 14px 20px 0px rgba(40, 42, 46, 1);

}

input {
    border: 1px solid white;
    border-radius: 15%;
    width: 50px;
    height: 35px;
}
</style>
