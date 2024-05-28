<script setup>

import Logo from "@components/organisms/Logo.vue";
import {useRouter} from "vue-router";
const router = useRouter();
</script>

<template>
    <div class="page container-fluid ml-10 mr-10 mt-10">
        <v-row>
            <v-col cols="3">
                <div >
                    <logo @click="router.push({name:'home'})" class="text-start" size="200px"/>
                </div>

            </v-col>
            <v-col></v-col>
            <v-col cols="1" class="align-self-center align-center">
                <v-btn :to="{name:'info'}" color="info" size="sm" icon="mdi-information-variant-circle"/>
            </v-col>
        </v-row>

        <div class="card text-white border-0 bg-dark h-100">
            <div class="card-body">
                <div class="mt-2">
                    <slot/>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.page {
}
</style>
