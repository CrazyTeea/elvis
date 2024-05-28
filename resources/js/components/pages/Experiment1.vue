<script setup>
import ExperimentTemplate from "@/components/templates/ExperimentTemplate.vue";
import ExperimentLine from "@/components/organisms/ExperimentLine.vue";
import {computed, onMounted, ref, watch} from "vue";
import {SVG} from '@svgdotjs/svg.js'
import FigurePicker from "@/components/organisms/FigurePicker.vue";
import VolumePicker from "@/components/organisms/VolumePicker.vue";
import svgMixin from "@/mixins/svgMixin.js"
import MonkeyVideo from "@/components/organisms/MonkeyVideo.vue";
import {useExperimentStore} from "@/store/experiment1Store.js";
import {useMonkeyStore} from "@/store/api/monkey.js";
import {Figure} from "@/classes/Figure.js";
import {SuperTimer} from "@mixins/utils.js";
import {deleteFile, downloadFile, fileHeaders, generateFile, getFiles} from "@mixins/files.js";

const {triangle} = svgMixin()
let figures = ref([])
const props = defineProps({
    monkey_id: String
})
const experimentStore = useExperimentStore();
const monkeyStore = useMonkeyStore()
const monkey = ref({id: props.monkey_id})
const monitor = ref(null)

const btns = ref({
    active: false,
    'ellipse': {
        click: false,
        options: ref({
            color: [],
            brightness: {min: 10, max: 50},
            size: {min: 10, max: 50},
            xx: [],
            yy: [],
        })
    },
    'rectangle': {
        click: false,
        options: ref({
            color: [],
            brightness: {min: 10, max: 50},
            size: {min: 10, max: 50},
            angle: [],
            xx: [],
            yy: [],
        },)
    },
    'rectangle2': {
        click: false,
        options: ref({
            color: [],
            brightness: {min: 10, max: 50},
            size: {min: 10, max: 50},
            angle: [],
            xx: [],
            yy: [],
        },)
    },
    'polygon': {
        click: false,
        options: ref({
            color: [],
            brightness: {min: 10, max: 50},
            size: {min: 10, max: 50},
            angle: [],
            xx: [],
            yy: [],
        },)
    },
    'rectangle3': {
        click: false,
        options: ref({
            color: [],
            brightness: {min: 10, max: 50},
            size: {min: 10, max: 50},
            angle: [],
            xx: [],
            yy: [],
        },)
    },
});
const files = ref([])
const fPicker = ref({
    show: false,
    btn: ''
});
const experiment = computed(() => ({
    active: experimentStore.getActive,
    data: experimentStore.getData,
    result: experimentStore.getResult,
    line: experimentStore.getLine
}))
let bc = new BroadcastChannel('timer-event');
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
    files.value = await getFiles(1, props.monkey_id)


    bc.addEventListener('message', function (e) {
        experimentStore.stopTimer()
    })

})

const btnIconEllipse = computed(() => {
    const draw = SVG();
    return draw
        .ellipse(25, 25)
        .fill(btns.value.ellipse.click ? 'purple' : 'grey')
        .parent()
        .svg();
});

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
        '/experiment-1-monitor',
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

    let exp = await experimentStore.storeExperiment();

    let figure = new Figure(exp.id, figures.value)
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

    if (['ellipse', 'rectangle2', 'polygon', 'rectangle', 'rectangle3'].indexOf(btnName) !== -1) {
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

const btnIconRect1 = computed(() => {
    return rect(25, btns.value.rectangle.click)
})
const btnIconRect2 = computed(() => {
    return rect(35, btns.value.rectangle2.click)
})
const btnIconPolygon = computed(() => {
    return triangle(25, btns.value.polygon.click ? 'purple' : 'grey')
})
const btnIconRect4 = computed(() => {
    return rect(5, btns.value.rectangle3.click)
})



</script>

<template>
    <experiment-template>
        <template #default>

            <div v-if="!experiment.active">
                <experiment-line>
                    <v-btn @click="startExperiment" variant="outlined" size="x-large"
                           icon="mdi-play-circle"></v-btn>
                </experiment-line>


                <div class="mt-11">
                    <v-row>
                        <v-col class="text-center">
                            <v-btn @click="()=>{
                            btns.active = !btns.active;
                            fPicker.show = btns.active;
                        }" class="shadow" :color="btns.active ? 'blue' : 'purple'" icon="mdi-pencil"/>
                        </v-col>
                        <v-col cols="1">

                        </v-col>
                    </v-row>
                    <v-row v-if="btns.active">
                        <v-col>
                            <div class="d-flex justify-center">
                                <v-card class="rounded-pill shadow d-flex justify-center align-center">
                                    <div class="pl-2 pr-2 pt-2 pb-2">
                                        <v-btn @click="btnClick('ellipse')" width="25" rounded
                                               size="sm">
                                            <div class="svg" v-html="btnIconEllipse"></div>
                                        </v-btn>
                                        <v-btn @click="btnClick('rectangle')" width="25" size="sm" class="ml-2">
                                            <div class="svg" v-html="btnIconRect1"></div>
                                        </v-btn>
                                        <v-btn @click="btnClick('rectangle2')" width="35" size="sm" class="ml-2">
                                            <div class="svg2" v-html="btnIconRect2"></div>
                                        </v-btn>
                                        <v-btn @click="btnClick('polygon')" width="25" size="sm" class="ml-2">
                                            <div class="svg" v-html="btnIconPolygon"></div>
                                        </v-btn>
                                        <v-btn @click="btnClick('rectangle3')" width="5" size="sm" class="ml-2 mr-2">
                                            <div class="svg3" v-html="btnIconRect4"></div>
                                        </v-btn>
                                    </div>

                                </v-card>

                                <v-btn style="border-radius: 25px !important;" width="168" height="57" class="mt-1 ml-2 shadow" @click="openOblast">Фоновая
                                    область
                                </v-btn>

                            </div>

                        </v-col>
                        <v-col cols="1"></v-col>
                    </v-row>
                    <div v-if="fPicker.show">
                        <figure-picker v-if="fPicker.btn === 'ellipse'" v-model="btns.ellipse.options"/>
                        <figure-picker v-if="fPicker.btn === 'rectangle'" v-model="btns.rectangle.options"/>
                        <figure-picker v-if="fPicker.btn === 'rectangle2'" v-model="btns.rectangle2.options"/>
                        <figure-picker v-if="fPicker.btn === 'polygon'" v-model="btns.polygon.options"/>
                        <figure-picker v-if="fPicker.btn === 'rectangle3'" v-model="btns.rectangle3.options"
                                       angle-picker/>
                    </div>
                    <div v-if="oblast.show">
                        <v-row>
                            <v-col class="d-flex justify-center">
                                <volume-picker v-model="oblast.options.brightness"/>
                                <v-card  class="ml-3" width="150" height="127">
                                    <v-card-text>
                                        <div >
                                            <div class="d-flex justify-space-around">
                                                <input style="width: 50px" id="x2" v-model="oblast.options.position.x1" placeholder="x1"
                                                       type="number">
                                                <div class="ml-2 mr-2">-</div>
                                                <input style="width: 50px" id="y1" v-model="oblast.options.position.y1" placeholder="y1"
                                                       type="number">
                                            </div>

                                            <div class="d-flex mt-2 justify-space-around">
                                                <input style="width: 50px" id="x2" v-model="oblast.options.position.x2" placeholder="x2"
                                                       type="number">
                                                <div class="ml-2 mr-2">-</div>
                                                <input style="width: 50px" id="y2" v-model="oblast.options.position.y2" placeholder="y2"
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

                        <iframe id="scaled-frame" src="/experiment-1-monitor" width="100%" height="100%"/>


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
                                            @click="getFiles(1, monkey_id)"
                                            text="Посмотреть файл"
                                            variant="outlined"
                                        ></v-btn>
                                    </template>

                                    <template v-slot:default="{ isActive }">
                                        <v-card title="Файлы">
                                            <v-container>
                                                <div class="d-flex justify-end">
                                                    <v-btn color="blue" @click="generateFile(monkey_id)">Сгенерировать файл</v-btn>
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
