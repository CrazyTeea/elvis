<script setup>

import {ref, watch} from "vue";

const model = defineModel()

const crnVal = ref({})
const btnHandler = (e, values) => {
    let i = model.value.findIndex((e) => e.name === values.name)
    if (i !== -1) {
        model.value.splice(i, 1)
    } else {
        model.value.push({...values})
        crnVal.value = {...values}
    }

}

watch(crnVal, (nv, ov) => {
    let i = model.value.findIndex((e) => e.name === nv.name)
    if (i !== -1) {
        model.value[i] = {...model.value[i], ...nv}
    }

}, {deep: true})

const getCSSClass = (name) => {
    let css = {
        'blink': 'btn ',
        'light': 'btn mt-2 '
    }
    if (model.value.findIndex(item => {
        return item?.name === name
    }) !== -1) {
        return css[name] + 'active'
    }
    return css[name]
}

</script>

<template>
    <div class="d-flex flex-column">
        <v-card class="shadow rounded1" width="153" rounded>
            <div class="d-flex pl-4 pt-4 pr-4 flex-column">
                <button @click="e=>btnHandler(e,{name:'blink'})" :class="getCSSClass('blink')">Мигание</button>
                <button @click="e=>btnHandler(e,{name:'light'})" :class="getCSSClass('light')">Вспышка</button>
                <!--                <v-btn class="mt-2" @click="e=>btnHandler(e,{name:'blink'})">Мигание</v-btn>-->
                <!--                <v-btn class="mt-2" @click="e=>btnHandler(e,{name:'light'})">Вспышка</v-btn>-->
            </div>
            <p class="text-center ">
                режим стимулов
            </p>


        </v-card>
        <v-card class="mt-5 rounded1 shadow" width="153" height="30" rounded>
            <div class="h-100 align-content-center">
                <div class="d-flex justify-center">
                    <span style="font-size: 12px">Длительность</span>
                    <input style="width: 55px" class="ml-2" type="number" v-model="crnVal.length">
                </div>
            </div>


        </v-card>
        <v-card v-if="crnVal?.name === 'blink'" class="mt-5 rounded1 shadow" width="153" height="30" rounded>
            <div class="h-100 align-content-center">
                <div class="d-flex justify-center">
                    <span style="font-size: 12px">Частота</span>
                    <input style="width: 55px" class="ml-2" type="number" v-model="crnVal.frequency">
                </div>
            </div>


        </v-card>
    </div>
</template>

<style scoped>
.rounded1 {
    border-radius: 32px !important;
}

.shadow {
    box-shadow: -7px -7px 15px 0px rgba(51, 55, 60, 1), 14px 14px 20px 0px rgba(40, 42, 46, 1);

}

input {
    background: rgba(175, 175, 175, 1);
    width: 40px;
    border-radius: 5px;
    padding-left: 5px;
    font-size: 12px
}

.btn {
    width: 115px;
    height: 50px;
    background: #24282D;
    border-radius: 15px;
}

.btn.active {
    background-color: #8C2CE1 !important;
}
</style>
