<script>
export default {
    name: "PosBtn",
    data() {
        return {
            click: false
        }
    },
    props: {
        arrow: String,
        pre_click: {
            type: Boolean,
            default: false
        }
    },
    mounted() {
        this.click = this.pre_click
    },
    computed: {
        getStyle() {
            return this.click ? 'border: 0.5px solid white;' : ''
        },
        getMdiArrow() {
            return 'mdi-arrow-' + this.arrow
        }
    },
    methods: {
        btnHandler() {
            this.click = !this.click
        }
    }
}
</script>

<template>
    <div :style="getStyle">
        <v-icon @click="btnHandler">{{ getMdiArrow }}</v-icon>
    </div>
</template>

<style scoped>

</style>
