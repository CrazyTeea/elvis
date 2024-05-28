import {defineStore} from "pinia";


export const useExperiment2Store = defineStore('experiment2', {
    state() {
        return {
            active: false,
            comment: '',
            position: [],
            stimul: {
                type: '',
                frequency: 0,
                length: 0,
            },
            helpers: [],
            line: {
                countProbs: 3,
                currentProb: 0,
                startDelay: 0,
                startHelp: {
                    min: 0,
                    max: 0
                },
                waitQuestion: {
                    min: 0,
                    max: 0
                },
                stopDelay: {
                    min: 0,
                    max: 0
                },
            }
        }
    },
    getters: {
        getTrueValue(state){},
        getReactionTime(state){}
    },
    actions: {
        async runExperiment(){
            this.active = true
            while (this.line.currentProb < this.line.countProbs){
                this.comment += "<p>kek</p>"
                this.line.currentProb++
            }
        }
    }
})
