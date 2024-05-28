<script setup>
const volume = defineModel()


import svgMixin from "@mixins/svgMixin.js";

const {rounded} = svgMixin()
</script>

<template>
    <v-card class="ml-3" width="125" height="127">
        <v-card-text>
            <div class="d-flex justify-center" style="height: 60px">
                <div class="v-btn--variant-outlined rounded-pill rounded" style="height: 52px; width: 52px; "
                     v-html="rounded(50)"></div>
                <div class="v-btn--variant-outlined rounded-pill small" style="height: 52px; width: 52px; "
                     v-html="rounded(50)"></div>
            </div>
            <div class="d-flex justify-space-around">
                <input style="width: 50px; border: 1px solid white" v-model="volume.min" type="number">
                <div>-</div>
                <input style="width: 50px; border: 1px solid white" v-model="volume.max" type="number">
            </div>
            <p class="text-center">Яркость</p>

        </v-card-text>
    </v-card>
</template>

<style scoped>

</style>
