<script setup>

import Page from "@/components/templates/Page.vue";
import {useRouter} from "vue-router";
import VButton from "@/components/molecules/VButton.vue";
const router = useRouter()
</script>

<template>
    <page class="pa-3">
        <div >
            <v-row>
                <v-col cols="2">
                    <v-button block :to="{name:'experiment1', params:{monkey_id:router.options.id}}" >Рефлекс на стимул</v-button>
                    <v-button :to="{name:'experiment2', params:{monkey_id:router.options.id}}" class="mt-3">Рефлекс на вспышку</v-button>
                    <v-button color="info" block rounded variant="outlined" class="mt-3">Рефлекс на фигуры</v-button>
                    <v-button color="info" block rounded variant="outlined" class="mt-3">Выбор по образцу</v-button>
                    <v-button color="info" block rounded variant="outlined" class="mt-3">Настройка стимулятора</v-button>
                </v-col>
                <v-col>
                    <slot/>
                </v-col>
            </v-row>
        </div>

        <slot name="footer"/>
    </page>
</template>

<style scoped>

</style>
