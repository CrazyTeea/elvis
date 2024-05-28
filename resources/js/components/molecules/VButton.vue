<script setup>
import {useRouter} from "vue-router";
import {computed, ref} from "vue";

const props = defineProps(['to','block','width','height'])
const emit = defineEmits(['click'])
const click = ref(false);
const router = useRouter()
const onClick = function (e) {
    click.value = !click.value
    emit('click')
}
const widthComputed = computed(()=>{
    if (props.width) {
        return 'width:'+props.width+'px;'
    }
    return 'width:100%;'

})
const heightComputed = computed(()=>{
    if (props.height) {
        return 'height:'+props.height+'px;'
    }
    return ''

})
const isClick = computed(()=>{
    if (click.value){
        return true
    }
    return router.currentRoute.value.name === props.to?.name
})
const getColor = computed(() => {
    if (isClick.value) {
        return "background: linear-gradient(131.99deg, #8E2DE2 6.23%, #6A19D8 85.46%);"
    }
    return ''
})
const getColor2 = computed(() => {
    if (isClick.value) {
        return "border-radius:15px; box-shadow: -5px -5px 25px 0 #8E2DE2, 5px 5px 25px 0 #6A19D8"
    }
    return ''
})
</script>

<template>
    <div>
        <a :style="getColor" class="btn" v-if="to" @click="router.push(to)">
            <div :style="getColor2">
                <slot></slot>
            </div>

        </a>
        <button v-else @click="onClick"  class="btn">
            <span :style="getColor2">
                <slot/>
            </span>
        </button>
    </div>


</template>

<style scoped>
.btn:hover {
    cursor: pointer;
}

.btn {
    display: flex;
    width: 170px;
    height: 67.2px;
    justify-content: center;
    align-items: center;
    font-weight: 700;
    font-size: 16px;
    padding: 10pt 10pt;
    text-align: center;
    font-family: sans-serif;
    line-height: 18px;
    border-radius: 15px;
    border: 1px solid rgba(57, 57, 57, 1);
    background-color: rgba(57, 57, 57, 1);
    box-shadow: -10px -15px 40px 0 rgba(82, 89, 94, 0.5),
    10px 15px 40px 0 rgba(0, 0, 0, 0.5);

}
</style>
