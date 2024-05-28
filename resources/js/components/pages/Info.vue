<script setup>

import Page from "@/components/templates/Page.vue";
import Logo from "@/components/organisms/Logo.vue";
import {ref} from "vue";
import VButton from "@/components/molecules/VButton.vue";

const l = ref(true)

</script>

<template>
    <page>
        <v-row>
            <v-col cols="4">
                <v-button block @click="l = true" class="d-block">Проект ELVIS V</v-button>
                <v-button block @click="l = false" class="mt-5 d-block">Описание программы</v-button>
            </v-col>
            <v-col cols="1" style="max-width: 30px">
                <v-divider style="border-style: dashed" vertical class="h-100 border-opacity-100"/>
            </v-col>
            <v-col>
                <v-card style="height: 550px">
                    <v-card-text v-if="l">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-center ">
                                <logo/>
                            </div>
                            <div class="d-flex justify-center ">
                                <span>Кортикальная протезная зрительная система</span>
                            </div>
                        </div>
                    </v-card-text>
                    <v-card-text v-else>
                        <div class="d-flex justify-center">
                            Программа предназначена для того-то (проведение клиники, например и зачем она нужна)
                            Состоит из таких блоков, которые последовательно решают задачи обучения и оценки функций
                            1 пункт такое делает
                            второй пункт такое и тд
                        </div>
                    </v-card-text>

                </v-card>
            </v-col>
        </v-row>
    </page>
</template>

<style scoped>

</style>
