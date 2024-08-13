<script setup>
import {computed, reactive, watch} from "vue";
import {SVG} from "@svgdotjs/svg.js";
import VolumePicker from "@/components/organisms/VolumePicker.vue";
import svgMixin from "@mixins/svgMixin.js";

const {rounded} = svgMixin()

const options = defineProps({
    modelValue: {
        size: Object,
        brightness: Object,
        color: Array,
        angle: Array,
        angle_value: Number,
        show_time: Number
    },
    anglePicker: Boolean,
    experiment3: Boolean
})

let values = reactive(options.modelValue)
values.size = options.modelValue.size
values.brightness = options.modelValue.brightness

const arr = {
    0: '0',
    45: '45',
    90: '90',
    135: '135',
}


const emit = defineEmits(['update:modelValue']);


watch(() => values.brightness.min, (v) => {
    emit('update:modelValue', values)
})
watch(() => values.brightness.max, (v) => {
    emit('update:modelValue', values)
})
watch(() => values.size.min, (v) => {
    emit('update:modelValue', values)
})
watch(() => values.size.max, (v) => {
    emit('update:modelValue', values)
})
watch(() => values.color, (c) => {
    emit('update:modelValue', values)
}, {deep: true})
watch(() => values.angle, (c) => {
    emit('update:modelValue', values)
}, {deep: true})

let rounds = {
    value1: 34,
    value2: 58
}

const palka = (a) => {
    let i = values.angle.indexOf(a)
    let co = i !== -1 ? '#624587' : '#000000'
    const svg = SVG();
    return svg.rect(5, 30).center(17, 17).fill(co).rotate(a).parent().svg()
}

const selectPalka = (a) => {
    let index = values.angle.indexOf(a)
    if (index !== -1) {
        values.angle.splice(index, 1)
    } else {
        values.angle.push(a)
    }
}

const selectColor = (c) => {
    let index = values.color.indexOf(c)
    if (index !== -1) {
        values.color.splice(index, 1)
    } else {
        values.color.push(c)
    }
}
const getColorStyle = (c) => {
    let index = values.color.indexOf(c)
    if (index !== -1) {
        return 'border: 0.5px solid white;'
    }
    return ''
}

const round = computed(() => {
    /*
    36 => 0%
    ? => 50%
    58 => 100%
     */

    if (values.size.min < 0 || values.size.max < 0) {
        values.size.min = 1
        values.size.max = 1
    }

    rounds.value1 = values.size.min
    rounds.value2 = values.size.max

    let style_1 = `height: ${rounds.value1 + 2}px; width: ${rounds.value1 + 2}px; --h:${rounds.value1}px;`;
    let style_2 = `height: ${rounds.value2 + 2}px; width: ${rounds.value2 + 2}px; --h1:${rounds.value2}px;`;

    return {
        div_1_w: style_1,
        round_1: rounded(rounds.value1),
        div_2_w: style_2,
        round_2: rounded(rounds.value2)
    }
})

</script>

<template>
    <v-row>
        <v-col class="d-flex justify-center">
            <v-card class="shadow rounded1" width="135" height="127">
                <v-card-text>
                    <div class="d-flex justify-center" style="height: 60px">
                        <div class="v-btn--variant-outlined rounded-pill rounded" :style="round.div_1_w"
                             v-html="round.round_1"></div>
                        <div class="v-btn--variant-outlined rounded-pill small" :style="round.div_2_w"
                             v-html="round.round_2"></div>
                        <div></div>
                    </div>
                    <div class="d-flex justify-space-around">
                        <input style="width: 55px; border: 1px solid white" v-model="values.size.min" type="number">
                        <div>-</div>
                        <input style="width: 55px; border: 1px solid white" v-model="values.size.max" type="number">
                    </div>
                    <p class="text-center">Размер</p>

                </v-card-text>
            </v-card>
            <volume-picker class="shadow rounded1" v-model="values.brightness"/>
            <v-card class="ml-3 shadow rounded1 overflow-auto" width="125" height="127">
                <v-card-text class="align-content-center text-center h-100">
                    <v-row>
                        <v-col @click="selectColor('#05FF00')" :style="getColorStyle('#05FF00')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#05FF00')"></v-col>
                        <v-col @click="selectColor('#FFF500')" :style="getColorStyle('#FFF500')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#FFF500')"></v-col>
                        <v-col @click="selectColor('#FF0000')" :style="getColorStyle('#FF0000')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#FF0000')"></v-col>
                    </v-row>
                    <v-row>
                        <v-col @click="selectColor('#00F0FF')" :style="getColorStyle('#00F0FF')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#00F0FF')"></v-col>
                        <v-col @click="selectColor('#0500FF')" :style="getColorStyle('#0500FF')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#0500FF')"></v-col>
                        <v-col @click="selectColor('#FA00FF')" :style="getColorStyle('#FA00FF')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#FA00FF')"></v-col>
                    </v-row>
                    <v-row>
                        <v-col @click="selectColor('#FFFFFF')" :style="getColorStyle('#FFFFFF')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#FFFFFF')"></v-col>
                        <v-col @click="selectColor('#FF007A')" :style="getColorStyle('#FF007A')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#FF007A')"></v-col>
                        <v-col @click="selectColor('#FF5C00')" :style="getColorStyle('#FF5C00')" class="pa-0 svg-svg"
                               v-html="rounded(30, '#FF5C00')"></v-col>
                    </v-row>
                </v-card-text>
            </v-card>

        </v-col>

        <v-col cols="1"></v-col>
    </v-row>
    <v-row v-if="anglePicker">
        <v-col>
            <div class="d-flex justify-center">
                <v-card class="rounded-pill" width="300">
                    <v-card-text class="d-flex justify-space-around">
                        <div @click="selectPalka(0)" class=" svg-svg " v-html="palka(0)"/>
                        <div v-if="!experiment3" @click="selectPalka(45)" class=" svg-svg " v-html="palka(45)"/>
                        <div @click="selectPalka(90)" class=" svg-svg " v-html="palka(90)"/>
                        <div v-if="!experiment3" @click="selectPalka(135)" class=" svg-svg" v-html="palka(135)"/>
                    </v-card-text>
                </v-card>
                <v-card v-if="experiment3" class="rounded-pill" width="300">
                    <v-card-text class="d-flex justify-space-around">
                        <input type="number" v-model="values.angle_value">
                    </v-card-text>
                </v-card>
                <v-card v-if="experiment3" class="rounded-pill" width="300">
                    <v-card-text class="d-flex justify-space-around">
                        <input type="number" v-model="values.show_time">
                    </v-card-text>
                </v-card>
            </div>

        </v-col>
        <v-col cols="1"></v-col>
    </v-row>
</template>

<style scoped>
:deep(.svg-svg) svg {
    height: 30px;
    width: 30px;

}
.rounded1 {
    border-radius: 32px !important;
}

.shadow {
    box-shadow: -7px -7px 15px 0px rgba(51, 55, 60, 1), 14px 14px 20px 0px rgba(40, 42, 46, 1);

}
</style>
