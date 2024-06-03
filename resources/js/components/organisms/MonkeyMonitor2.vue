<script setup>
import {computed, onDeactivated, ref, watch} from "vue";
import {useExperiment2Store} from "@/store/experiment2Store.js";
import {storeToRefs} from "pinia";

const store = useExperiment2Store()

const props = defineProps({
    active: Boolean,
    line: Object,
    position: String
})

const wh = ref({w: 0, h: 0})
let box = ref(null);

watch(() => props.active, (n, o) => {
    console.log({n, o})
})

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
        const {x, y, w, h} = ramki.value[props.position]
        let s = `width: ${w}px; height: ${h}px;  left: ${x}px; top: ${y}px;`;
        if (props.line.crntHelper?.name === 'oblast') {
            s += "background-color:yellow;"
        }
        if (props.line.crntHelper?.name === 'ramka') {
            s += "border-width :15px;"
        }
        return s
    }
    return '';

}

onDeactivated(() => {
    store.is_window = false
})


</script>

<template>
    <div class="pa-3 h-100 w-100 ">
        <div ref="box" v-if="active" class="h-100 w-100 position-relative border-dashed">
            <hr :style="lineStyle.vert" class="position-absolute border-solid yellow">
            <hr :style="lineStyle.hor" class="position-absolute border-solid yellow">
            <div :style="setOblastPosition()" class="position-absolute  border-solid yellow">
                {{store.getActive}}
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
