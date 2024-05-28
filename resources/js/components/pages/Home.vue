<script setup>
import Page from "../templates/Page.vue";
import {onMounted, reactive, ref, watch} from "vue";
import Loading from "@/components/pages/Loading.vue";
import axios from "axios";
import {useRouter} from "vue-router";
import {useExperimentStore} from "@/store/experiment1Store.js";
import {useMonkeyStore} from "@/store/api/monkey.js";
import SimpleCard from "@/components/molecules/SimpleCard.vue";

const loadingVal = ref(true);
const monkey = ref({
    name: null,
    id: null,
    elvis_id: null,
    age: null,
    weight: null,
    comment: null
});
const monkeys = reactive({list: []});
const router = useRouter()
const experimentStore = useExperimentStore()
const monkeyStore = useMonkeyStore()

let count = 1;

const deleteDialog = ref(false);
let res, rej = null;

function dialogOpen() {
    return new Promise((resolve, reject) => {
        res = resolve;
        rej = reject;
    })
}

function dialogAgree() {
    res(true);
    deleteDialog.value = false
}

function dialogCansel() {
    res(false);
    deleteDialog.value = false;
}

async function saveMonkey() {
    monkey.value = (await axios.post('/monkeys', monkey.value)).data

    await getMonkeys()
}

async function getMonkeys() {
    monkeys.list = await monkeyStore.getMonkeys()
}

async function deleteMonkey() {
    deleteDialog.value = true;
    if (await dialogOpen()) {
        if (monkey.value.id) {
            await axios.delete(`/monkeys/${monkey.value.id}`);
            await getMonkeys();
        } else {
            let index = monkeys.list.findIndex(it => it.elvis_id === monkey.value.elvis_id && it.name === monkey.value.name)
            if (index !== -1) {
                monkeys.list.splice(index, 1)
            }
        }

    }
    monkey.value = {}
}

function selectMonkey(index) {
    monkey.value = monkeys.list[index];
}

async function submit(form) {
    await saveMonkey()
    experimentStore.monkey_id = monkey.value.id
    await router.push({name: 'experiment1', params: {monkey_id: monkey.value.id}});
}

onMounted(async () => {

    await getMonkeys();

    setTimeout(() => {
        loadingVal.value = false
    }, 1000)

})

</script>

<template>
    <page v-if="!loadingVal">

        <v-row class="mt-10">
            <v-col cols="4">

                <div class="overflow-y-auto pl-5 pt-5 pr-5" style="height: 550px">
                    <v-card v-if="monkeys.list.length"
                            v-for="(item, index) in monkeys.list"
                            @click="selectMonkey(index)"
                            class="mb-2 shadow"
                    >
                        <v-card-text>
                            <p>{{ item.name }}</p>
                            <p class="text-disabled">ID: {{ item.elvis_id }}</p>
                        </v-card-text>
                    </v-card>
                    <span v-else>добавьте макаку</span>
                </div>
                <div style="height: 100px" class="d-flex justify-center align-center">
                    <v-btn class="mt-3" @click="monkey = {}" rounded> Новое исследование</v-btn>
                </div>


            </v-col>
            <v-col cols="1" style="max-width: 30px">
                <v-divider style="border-style: dashed" vertical class="h-100 border-opacity-100"/>
            </v-col>
            <v-col>
                <v-card class="h-100" border="lg">
                    <v-form class="h-100" @submit.prevent="submit">
                        <v-container class="h-100">
                            <div class="position-relative" style="min-height: 600px">
                                <v-text-field
                                    v-model="monkey.name"
                                    label="Имя животного:"
                                    hide-details
                                    required
                                    name="name"
                                    class="mb-2"
                                />
                                <v-text-field
                                    v-model="monkey.elvis_id"
                                    label="ID животного:"
                                    hide-details
                                    name="id"
                                    required
                                    class="mb-2"
                                />
                                <v-text-field
                                    v-model="monkey.age"
                                    name="age"
                                    label="Возраст"
                                    hide-details
                                    required
                                    class="mb-2"
                                />
                                <v-text-field
                                    v-model="monkey.weight"
                                    name="weight"
                                    label="Вес"
                                    hide-details
                                    required
                                    class="mb-2"
                                />
                                <v-textarea
                                    name="comment"
                                    v-model="monkey.comment"
                                    label="Комментарии"
                                    hide-details
                                    required
                                    class="mb-2"
                                />
                                <div style="height: 40px"></div>
                                <div style="bottom: 0; left: 80%" class="position-absolute">
                                    <v-btn @click="deleteMonkey" class="ml-2" density="compact" icon="mdi-delete"
                                           color="red"></v-btn>
                                    <v-btn @click="saveMonkey" class="ml-2" density="compact"
                                           icon="mdi-content-save"></v-btn>
                                    <v-btn type="submit" class="ml-2" rounded density="compact">Далее</v-btn>
                                </div>
                            </div>

                        </v-container>
                    </v-form>
                </v-card>

            </v-col>
        </v-row>
        <v-dialog v-model="deleteDialog" max-width="400" persistent>

            <v-card
                color="red"
                prepend-icon="mdi-map-marker"
                text="Вы уверены, что хотите удалить? (данные придется занести заново)"
                title="Удалить"
            >
                <template v-slot:actions>
                    <v-spacer></v-spacer>

                    <v-btn @click="dialogCansel">
                        Отмена
                    </v-btn>

                    <v-btn @click="dialogAgree">
                        Да
                    </v-btn>
                </template>
            </v-card>
        </v-dialog>


    </page>
    <loading v-else/>
</template>

<style scoped>
    .shadow {
        box-shadow: /* ie */
            0 -10px 15px -6px  rgba(38, 46, 50, 1), /* top - THE RED SHADOW */
            0  5px  15px  0  rgba(16, 16, 18, 0.5), /* bottom */
            5px  0  15px  0  rgba(16, 16, 18, 0.5), /* right */
            -5px  0  15px  0  rgba(38, 46, 50, 1); /* left */
        border-radius: 16px !important;

    }
</style>
