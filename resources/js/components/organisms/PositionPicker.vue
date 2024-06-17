<script setup>

import PosBtn from "@/components/molecules/PosBtn.vue";
import {ref} from "vue";

const model = defineModel()

const btnHandler = (name) => {
    let i = model.value.findIndex(item => item === name);
    if (i === -1) {
        model.value.push(name)
    } else {
        model.value.splice(i, 1)
    }
}

const isClicked = (val) => model.value.findIndex(item => item === val) !== -1;

</script>

<template>
    <div>
        <v-card class="rounded1 shadow overflow-auto" width="135" height="127">
            <div class="align-content-center text-center h-100 pa-4">
                <div class="d-flex justify-space-around">
                    <pos-btn :pre_click="isClicked('top-left')" style="width: 50px" @click="btnHandler('top-left')" arrow="top-left"/>

                    <pos-btn :pre_click="isClicked('top-right')" style="width: 50px" @click="btnHandler('top-right')" arrow="top-right"/>

                </div>
                <div class="d-flex justify-space-around">

                    <pos-btn :pre_click="isClicked('bottom-left')" style="width: 50px;" @click="btnHandler('bottom-left')" arrow="bottom-left"/>


                    <pos-btn :pre_click="isClicked('bottom-right')" style="width: 50px;" @click="btnHandler('bottom-right')" arrow="bottom-right"/>
                </div>


                <p class="text-center">
                    Место стимулов
                </p>
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
</style>
