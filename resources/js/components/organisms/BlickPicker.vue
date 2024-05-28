<script setup>

const model = defineModel()

const btnHandler = (values) => {
    model.value = {
        ...values
    }
}

</script>

<template>
    <div class="d-flex flex-column">
        <v-card class="shadow rounded1" width="153" height="165" rounded>
            <div class="d-flex pa-4 flex-column">
                <v-btn class="mt-2" @click="btnHandler({type:'blink'})">Мигание</v-btn>
                <v-btn class="mt-2" @click="btnHandler({type:'light'})">Вспышка</v-btn>
            </div>
            <p class="text-center ">
                режим стимулов
            </p>

        </v-card>
        <v-card class="mt-5 rounded1 shadow" width="153" height="30" rounded>
            <div class="h-100 align-content-center">
                <div class="d-flex justify-center">
                    <span style="font-size: 12px">Длительность</span>
                    <input class="ml-2"  type="number" v-model="model.length">
                </div>
            </div>


        </v-card>
        <v-card v-if="model.type === 'blink'" class="mt-5 rounded1 shadow" width="153" height="30" rounded>
            <div class="h-100 align-content-center">
                <div class="d-flex justify-center">
                    <span style="font-size: 12px">Частота</span>
                    <input class="ml-2"  type="number" v-model="model.frequency">
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
</style>
