<script setup>

import {computed, onMounted, ref, watch} from "vue";

const model = defineModel()
const crntType = ref({
  name: '',
  thickness: 0,
  brightness: 0
})
const helpers = ref([])
const types = ref({
  'ramka': {
    name: 'ramka',
    click: false,
    thickness: 0,
    brightness: 0
  },
  'oblast': {
    name: 'oblast',
    click: false,
    thickness: 0,
    brightness: 0
  },
  'none': {
    name: 'none',
    click: false,
    thickness: 0,
    brightness: 0
  }
})

let getStyleButton = function (t) {
  let styles = {
    'ramka': 'width: 27px; height: 27px;',
    'oblast': 'width: 27px; height: 27px;',
    'none': 'width: 120px; height: 27px;'
  }
  if (types.value[t].click) {
    return styles[t] + 'background-color:red;'
  }

  return styles[t]
}
onMounted(() => {
  console.log(model.value)
})

watch(() => helpers.value.length, () => {
  model.value = helpers.value
}, {deep: true})

const typeText = computed(()=>helpers.value.at(helpers.value.length-1)?.name)

watch(crntType, () => {
  let index = model.value.findIndex(item=>item.name === crntType.value.name)
  if (index !== -1) {
    let obj = model.value.at(index)
    obj = {...obj, ...crntType.value}
    model.value[index] = obj
  }
}, {deep: true})
let btnHandler = function (name) {
  types.value[name].click = !types.value[name].click
  let index = helpers.value.findIndex(item => {
    return item.name === name
  })


  if (types.value[name].click) {
    crntType.value = {name}
    if (index === -1) {
      helpers.value.push({...crntType.value})
    } else {
      crntType.value = {...helpers.value.at(index)}
    }

  } else {
    helpers.value.splice(index, 1)
  }
}

</script>

<template>
  <div>
    <div class="d-flex justify-center">
      <v-card width="300" height="114" class="pa-2 shadow rounded1">
        <div class="d-flex pa-4  justify-space-around">
          <button @click="btnHandler('oblast')" :style="getStyleButton('oblast')"
                  class="v-btn v-btn--border v-btn--variant-outlined">
            <div class="pa-1">
              <div style="width: 15px; height: 15px;background-color: grey"></div>
            </div>
          </button>
          <button @click="btnHandler('ramka')" :style="getStyleButton('ramka')"
                  class="v-btn v-btn--border v-btn--variant-outlined">
            <v-icon>mdi-square-rounded-outline</v-icon>
          </button>
          <button @click="btnHandler('none')" :style="getStyleButton('none')"
                  class="v-btn--rounded v-btn--variant-outlined">
            Без подсказки
          </button>
        </div>
        <p class="text-center ">
          подсказки
        </p>
      </v-card>
    </div>
    <div>
      <div class="d-flex mt-5 justify-center ga-5">
        <v-card v-if="typeText === 'oblast' || typeText === 'ramka'"
                class="rounded1 shadow pa-2"
                width="200">
          <div class="h-100 align-content-center">
            <div class="d-flex justify-center">
              <span style="font-size: 12px">Яркость</span>
              <input style="width: 50px; font-size: 12px" class="ml-2" type="number" v-model="crntType.brightness">
            </div>
          </div>
        </v-card>
        <v-card class="rounded1 pa-2 shadow" v-if="typeText === 'ramka'" width="200">
          <div class="h-100 align-content-center">
            <div class="d-flex justify-center">
              <span style="font-size: 12px">Толщина контура</span>
              <input style="width: 50px; font-size: 12px" class="ml-2" type="number" v-model="crntType.thickness">
            </div>
          </div>
        </v-card>
      </div>
    </div>
  </div>

</template>

<style scoped>
.rounded1 {
  border-radius: 32px !important;
}

.shadow {
  box-shadow: -7px -7px 15px 0 rgba(51, 55, 60, 1), 14px 14px 20px 0 rgba(40, 42, 46, 1);

}
input {
  background: rgba(175, 175, 175, 1);
  border-radius: 5px;
  padding-left: 5px;
}
</style>
