<script setup>
import {computed, ref, watch} from "vue";
import {useExperiment3Store} from "@/store/experiment3Store.js";
import {getRandom} from "@mixins/utils.js";

const store = useExperiment3Store()
let bc = new BroadcastChannel('timer-event3');

let color = ref(0)
let figureLeft = ref(false)
let figureRight = ref(false)

const props = defineProps({
    figure: Object,
    oblast: Object,
    active: Boolean
})

watch(() => props.oblast.brightness, () => {
    color.value = getRandom(props.oblast.brightness?.min ?? 0, props.oblast.brightness?.max ?? 0)
    color.value = color.value * 255 / 100
}, {deep: true})

let click = false

function sendClick(event) {

    localStorage.setItem('x', event.clientX)
    localStorage.setItem('y', event.clientY)
    localStorage.setItem('react', 'false')

    bc.postMessage('stop')
    console.log('click1')
}

function sendStop(event, pos) {
    if (store.canClick) {
        localStorage.setItem('x', event.clientX)
        localStorage.setItem('y', event.clientY)

        let react = false

        if (((store.data.figure.angle < 45 && store.data.figure.angle >= 0)
            ||
            (store.data.figure.angle > 200)) && pos === 'right') {
            react = true
        }
        if (store.data.figure.angle >= 45 && pos === 'left') {
            react = true
        }

        localStorage.setItem('react', (String)(react))

        console.log('click2')
        bc.postMessage('stop')
    }


}

function getOblastPos(props) {
    let x = store.data.oblast.position.x1;
    let y = store.data.oblast.position.y1;
    let w = store.data.oblast.position.x2 - store.data.oblast.position.x1
    let h = store.data.oblast.position.y2 - store.data.oblast.position.y1
    if (props) {
        return {x, y, w, h}
    }
    return `left:${x}px;top:${y}px;width:${w}px;height:${h}px;`
}

const setOblastPosition1 = computed(() => {

    const w = window.innerWidth / 3
    const h = window.innerHeight - 15

    figureLeft = false;
    figureRight = false

    return `width: ${w}px; height: ${h}px; left: 1px; top: 0px;`;
})
const setOblastPosition2 = computed(() => {
    const w = window.innerWidth / 3
    const h = window.innerHeight - 15
    return `width: ${w}px; height: ${h}px; left: ${w}px; top: 0px`;
})
const setOblastPosition3 = computed(() => {
    const w = window.innerWidth / 3
    const h = window.innerHeight - 15
    let left = w * 2

    return `width: ${w}px; height: ${h}px; left: ${left}px; top: 0px;`
})


let showHelperLeft = computed(() => {
    let {x,y,w,h} = getOblastPos(true);

    let left = 0

    let obl = ` height: ${h}px; top: 0px;`;

    figureLeft = false;
    figureRight = false;

    if (store.showHelper) {

        if (store.data.helper.name === 'figure') {
            figureLeft = true
            let l = store.data.helper.br / 100
            obl += `background-color: rgba(241, 213, 0, ${l}); `
        }

        if (store.data.helper.name === 'oblast') {
            left = -store.data.helper.offset;
            w -= left
            let l = store.data.figure.angle == 90 ? store.data.helper.brTrue / 100 : store.data.helper.brFalse / 100

            obl += `background-color: rgba(255, 255, 255, ${l}); left: ${left}px;`
        }

        if (store.data.helper.name === 'ramka') {
            let l = store.data.helper.br / 100
            obl += `border-width :${store.data.helper.thickness}px; border: rgba(241, 213, 0, ${l}) solid;`
        }

    }
    obl += `width: ${w}px;`;

    return obl;
})

let showHelperRight = computed(() => {
    let {x,y,w,h} = getOblastPos(true);

    let obl = ` height: ${h}px; top: 0px;`;
    let left = 0;

    figureLeft = false;
    figureRight = false;

    if (store.showHelper) {

        if (store.data.helper.name === 'figure') {
            figureLeft = true
            let l = store.data.helper.br / 100
            obl += `background-color: rgba(241, 213, 0, ${l}); `
        }

        if (store.data.helper.name === 'oblast') {
            left = store.data.helper.offset;
            w -= left;
            let l = store.data.figure.angle == 0 ? store.data.helper.brTrue / 100 : store.data.helper.brFalse / 100

            obl += `background-color: rgba(255, 255, 255, ${l}); left: ${left}px;`
        }

        if (store.data.helper.name === 'ramka') {
            let l = store.data.helper.br / 100
            obl += `border-width :${store.data.helper.thickness}px; border: rgba(241, 213, 0, ${l}) solid;`
        }

    }
    obl += `width: ${w}px;`

    return obl;


})


</script>

<template>
    <div class="pa-1 h-100 w-100 ">
        <div v-if="active" :style="`background-color: rgb(${color} ${color} ${color});`"
             class="wh position-absolute border-dashed">
            <div v-if="store.showFigure" oncontextmenu="return false" @click="event=>sendStop(event, 'left')"
                 :style="setOblastPosition1"
                 class="position-absolute ">
                    <div :style="getOblastPos()" class="position-absolute kek2">

                        <div v-if="figureLeft" class="position-relative" :style="store.getFigurePositionCenter(true)">

                        </div>
                        <div class="position-relative " :style="showHelperLeft">
                            helper left
                        </div>
                    </div>
            </div>
            <div oncontextmenu="return false" :style="setOblastPosition2"
                 class="position-absolute">
                <div class="position-relative">
                    <div :style="getOblastPos()" class="position-absolute kek2">
                        <div class="position-relative" :style="store.getFigurePositionCenter()">
                        </div>
                    </div>
                </div>


            </div>
            <div v-if="store.showFigure" oncontextmenu="return false" @click="event=>sendStop(event, 'right')"
                 :style="setOblastPosition3"
                 class="position-absolute ">

                    <div :style="getOblastPos()" class="position-absolute  kek2">

                        <div v-if="figureRight" class="position-relative" :style="store.getFigurePositionCenter(true)">

                        </div>
                        <div class="position-relative" :style="showHelperRight">
                            helper right
                        </div>
                    </div>


            </div>
        </div>
        <div v-else class="d-flex h-100 w-100 justify-center align-center align-content-center center">
            <p>Экран обезьяны</p>
        </div>
    </div>

</template>

<style scoped>
.wh {
    height: 99% !important;
    width: 99% !important;
}
</style>
