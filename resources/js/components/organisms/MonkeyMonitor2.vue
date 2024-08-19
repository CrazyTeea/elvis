<script setup>
import {computed, onDeactivated, reactive, ref} from "vue";


const chanel = new BroadcastChannel('experiment-2-kek')

const props = defineProps({
    active: Boolean,
    line: Object,
    position: String
})

let wh = reactive({w: 0, h: 0})
const wh2 = ref({w: 0, h: 0})
const box = ref(null);
const boxl = ref(null);
const boxr = ref(null);
const boxbl = ref(null);
const boxbr = ref(null);

const ramki = computed(() => ({
    'top-left': {x: 1, y: 1, w: wh.w, h: wh.h,},
    'top-right': {x: wh.w, y: 0, w: wh.w, h: wh.h},
    'bottom-left': {x: 1, y: wh.h, w: wh.w, h: wh.h},
    'bottom-right': {x: wh.w, y: wh.h, w: wh.w, h: wh.h},
}))
const ramki2 = computed(() => ({
    'top-left': {x: 1, y: 1, w: wh2.value.w, h: wh2.value.h,},
    'top-right': {x: wh2.value.w, y: 0, w: wh2.value.w, h: wh2.value.h},
    'bottom-left': {x: 1, y: wh2.value.h, w: wh2.value.w, h: wh2.value.h},
    'bottom-right': {x: wh2.value.w, y: wh2.value.h, w: wh2.value.w, h: wh2.value.h},
}))

const lineStyle = computed(() => {
    let b = box.value
    if (props.active && b && (props.line.crntHelper?.name === 'oblast' || props.line.crntHelper?.name === 'ramka')) {
        const {width, height} = b.getBoundingClientRect();
        return {
            vert: `width: ${width}px; size:5px; left: 0; top: ${height / 2}px;`,
            hor: `width: ${height}px; transform:rotate(90deg); size:5px; left: ${width / 2 - height / 2}px; top: ${height / 2}px;`,
        }
    }
    return {}
})

const setHelpers = (pos) => {
    if (pos !== props.position)
        return '';
    let b = box.value
    if (pos == 'top-left') {
        b = boxl.value
    }
    if (pos == 'top-right') {
        b = boxr.value
    }
    if (pos == 'bottom-left') {
        b = boxbl.value
    }
    if (pos == 'bottom-right') {
        b = boxbr.value
    }
    if (props.active && b) {
        const {width, height} = b.getBoundingClientRect();
        wh2.value = {w: width, h: height}
        let {w, h} = ramki2.value[pos]
        let x = 0;
        let y = 0;
        w -= 10
        h -= 10

        x += props.line.crntHelper.offsetX
        w -= props.line.crntHelper.offsetX
        y += props.line.crntHelper.offsetY
        h -= props.line.crntHelper.offsetY

        let s = `width: ${w}px; height: ${h}px;  left: ${x}px; top: ${y}px;`;
        if (props.line.showHelpers) {
            if (props.line.crntHelper?.name === 'oblast') {
                let l = props.line.crntHelper.br / 100
                s += `background-color: rgba(255, 255, 255, ${l}); `
            }
            if (props.line.crntHelper?.name === 'ramka') {
                let l = props.line.crntHelper.br / 100
                s += `border-width :${props.line.crntHelper.thickness}px; border: rgba(241, 213, 0, ${l}) solid;`
            }
        }
        //console.log(s)
        return s
    }
    return '';
}
const setOblastPosition = (pos) => {
    let b = box.value
    if (props.active && b) {
        const {width, height} = b.getBoundingClientRect();
        wh = {w: width / 2, h: height / 2}
        let {x, y, w, h} = ramki.value[pos]

        return `width: ${w}px; height: ${h}px;  left: ${x}px; top: ${y}px;`
    }
    return ''
}

onDeactivated(() => {
    // store.is_window = false
})

let btnq = false

const stopClk = (evt, pos) => {
    btnq = pos == props.position
    if (props.line.canClick) {
        localStorage.setItem('x_clk', evt.clientX)
        localStorage.setItem('y_clk', evt.clientY)

        localStorage.setItem('react', `${btnq}`)
        console.log(btnq)

        chanel.postMessage('stop')
    }

}

const btnClick = (evt) => {
    if (props.line.canClick) {
        localStorage.setItem('x_clk', evt.clientX)
        localStorage.setItem('y_clk', evt.clientY)
        if (!btnq) {
            localStorage.setItem('react', 'false')
        }
        btnq = false
        console.log("true2")
        chanel.postMessage('stop')
    }

}

</script>

<template>
    <div class="pa-3 h-100 w-100 ">
        <div oncontextmenu="return false" @click.prevent="btnClick" ref="box" v-if="active"
             class="h-100 w-100 position-relative border-dashed">
            <hr :style="lineStyle.vert" class="position-absolute  border-none">
            <hr :style="lineStyle.hor" class="position-absolute  border-none">
            <div :style="setOblastPosition('top-left')" ref="boxl"
                 @mousemove="event=>stopClk(event, 'top-left')"
                 @mouseover="event=>stopClk(event, 'top-left')"
                 @click="event=>stopClk(event, 'top-left')"
                 class="position-absolute ">
                <div :style="setHelpers('top-left')" class="position-relative ">
                </div>
            </div>
            <div :style="setOblastPosition('top-right')" ref="boxr"
                 @mousemove="event=>stopClk(event, 'top-right')"
                 @mouseover="event=>stopClk(event, 'top-right')"
                 @click="event=>stopClk(event, 'top-right')"
                 class="position-absolute ">
                <!--                <div style="height: 100px; width: 100px; left: 400px; top: 100px; background-color: aliceblue" class="position-relative"></div>-->
                <div :style="setHelpers('top-right')" class="position-relative ">
                </div>
            </div>
            <div :style="setOblastPosition('bottom-left')" ref="boxbl"
                 @mousemove="event=>stopClk(event, 'bottom-left')"
                 @mouseover="event=>stopClk(event, 'bottom-left')"
                 @click="event=>stopClk(event, 'bottom-left')"
                 class="position-absolute ">
                <div :style="setHelpers('bottom-left')" class="position-relative ">
                </div>
            </div>
            <div :style="setOblastPosition('bottom-right')" ref="boxbr"
                 @mousemove="event=>stopClk(event, 'bottom-right')"
                 @mouseover="event=>stopClk(event, 'bottom-right')"
                 @click="event=>stopClk(event, 'bottom-right')"
                 class="position-absolute  ">
                <div :style="setHelpers('bottom-right')" class="position-relative ">
                </div>
            </div>
        </div>
        <div v-else class="d-flex h-100 w-100 justify-center align-center align-content-center center">
            <p>Экран обезьяны</p>
        </div>
    </div>

</template>

<style scoped>
.yellow {
    border-color: yellow;
}

</style>
