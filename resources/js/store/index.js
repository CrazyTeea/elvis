import {defineStore} from "pinia";
import {th} from "vuetify/locale";


function timeout(fn, ms) {
    return new Promise(resolve => {
        setTimeout(resolve(fn()), ms)
    })
}

export const useExperimentStore = defineStore('experiment', {
    state: () => ({
        active: false,
        data: {},
        monkey: {},
        result: {},
        line: {
            maxCount: 0,
            current: 0,
            startDelay: 0,
            showRange: {min: 0, max: 0},
            stopRange: {min: 0, max: 0}
        },
        id: null
    }),

    getters: {
        getActive: (state) => state.active,
        getData: (state) => state.data,
        getResult: (state) => state.result,
        getMonkey: (state) => state.monkey
    },
    actions: {
        setActive(value) {
            this.active = value
        },
        setData(value) {
            this.data = value
        },
        setResult(value) {
            this.result = value
        },
        setId(value) {
            this.id = value
        },
        async downloadMonkey() {
            this.monkey = (await axios.get(`/monkeys/${this.id}`)).data
        },
        async runExperiment() {
            while (this.line.current <= this.line.maxCount) {
                await timeout(()=>console.log('startDelay'))
                await timeout(()=>console.log('showRange'))
                await timeout(()=>console.log('stopRange'))
            }

        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
