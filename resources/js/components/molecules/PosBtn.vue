<script>
export default {
    name: "PosBtn",
    data() {
        return {
            click: false
        }
    },
    props: ['arrow'],
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
