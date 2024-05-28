<script setup>
import {computed, onDeactivated, onMounted, ref, watch} from "vue";
import {useExperimentStore} from "@/store/experiment1Store.js";
import {share} from "pinia-shared-state";
import {getRandom} from "@mixins/utils.js";

const store = useExperimentStore()
let bc = new BroadcastChannel('timer-event');

let color = ref(0)

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
}

function sendStop(event) {
    localStorage.setItem('x', event.clientX)
    localStorage.setItem('y', event.clientY)
    localStorage.setItem('react', 'true')

    bc.postMessage('stop')

}

const setOblastPosition = computed(() => {

    let width = props.oblast.position.x2 - props.oblast.position.x1;
    let height = props.oblast.position.y2 - props.oblast.position.y1;

    return `width: ${width}px; height: ${height}px; left: ${props.oblast.position.x1}px; top: ${props.oblast.position.y1}px`;
})


</script>

<template>
    <div class="pa-1 h-100 w-100 ">
        <div v-if="active" @click="sendClick" :style="`background-color: rgb(${color} ${color} ${color});`"
             class="wh position-relative border-dashed">
            <div :style="setOblastPosition" class="position-absolute border-dashed">
                <div v-if="store.showFigure">
                    <div @click.stop="sendStop" :style="store.getFigurePosition()" class="position-relative kek1"
                         v-if="store.data.figure?.name === 'polygon'"
                         v-html="store.generateTriangle()"></div>
                    <div @click.stop="sendStop" v-else :style="store.getFigurePosition()"
                         class="position-relative kek2"></div>
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
