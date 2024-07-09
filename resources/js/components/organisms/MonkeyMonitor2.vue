<script setup>
import {computed, onDeactivated, ref, watch} from "vue";
import {useExperiment2Store} from "@/store/experiment2Store.js";
import {storeToRefs} from "pinia";

const store = useExperiment2Store()

const chanel = new BroadcastChannel('experiment-2-kek')

const props = defineProps({
    active: Boolean,
    line: Object,
    position: String
})

const wh = ref({w: 0, h: 0})
let box = ref(null);

const ramki = computed(() => ({
    'top-left': {x: 1, y: 1, w: wh.value.w, h: wh.value.h,},
    'top-right': {x: wh.value.w, y: 0, w: wh.value.w, h: wh.value.h},
    'bottom-left': {x: 1, y: wh.value.h, w: wh.value.w, h: wh.value.h},
    'bottom-right': {x: wh.value.w, y: wh.value.h, w: wh.value.w, h: wh.value.h},
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

const setOblastPosition = () => {

    let b = box.value
    if (props.active && b) {
        const {width, height} = b.getBoundingClientRect();
        wh.value = {w: width / 2, h: height / 2}
        let {x, y, w, h} = ramki.value[props.position]
        w-=props.line.crntHelper.offset
        h-=props.line.crntHelper.offset
        switch (props.position) {
            case 'bottom-right':{
                x+=props.line.crntHelper.offset
                y+=props.line.crntHelper.offset
                break;
            }
            case 'top-right': {
                x+=props.line.crntHelper.offset

                break;
            }
            case 'bottom-left': {
                y+=props.line.crntHelper.offset
                break;
            }
        }
        let s = `width: ${w}px; height: ${h}px;  left: ${x}px; top: ${y}px;`;
        if (store.line.showHelpers) {
            if (props.line.crntHelper?.name === 'oblast') {
                let l = props.line.crntHelper.br / 100
                s += `background-color: rgba(241, 213, 0, ${l}); `
            }
            if (props.line.crntHelper?.name === 'ramka') {
                let l = props.line.crntHelper.br / 100
                s += `border-width :${props.line.crntHelper.thickness}px; border: rgba(241, 213, 0, ${l}) solid;`
            }
        }
        return s
    }
    return '';

}

onDeactivated(() => {
    store.is_window = false
})

let btnq = false

const stopClk = (evt) => {
    localStorage.setItem('x_clk', evt.clientX)
    localStorage.setItem('y_clk', evt.clientY)
    btnq = true
    localStorage.setItem('react', `${btnq}`)
    console.log(btnq)

    chanel.postMessage('stop')
}

const btnClick = (evt) => {
    localStorage.setItem('x_clk', evt.clientX)
    localStorage.setItem('y_clk', evt.clientY)
    if (!btnq) {
        localStorage.setItem('react', 'false')
    }
    btnq = false
    console.log("true2")
    chanel.postMessage('stop')
}

</script>

<template>
    <div class="pa-3 h-100 w-100 ">
        <div @click.prevent="btnClick" ref="box" v-if="active" class="h-100 w-100 position-relative border-dashed">
            <hr :style="lineStyle.vert" class="position-absolute  border-none">
            <hr :style="lineStyle.hor" class="position-absolute  border-none">
            <div :style="setOblastPosition()" @mousemove="stopClk" @mouseover="stopClk" @click="stopClk" class="position-absolute ">

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
