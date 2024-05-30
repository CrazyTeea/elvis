import {defineStore} from "pinia";
import {getRandom, SuperTimer} from "@mixins/utils.js";


export const useExperiment2Store = defineStore('experiment2', {
    state:()=> {
        return {
            active: false,
            comment: '',
            positions: [],
            position: '',
            stimul: {
                type: '',
                frequency: 0,
                length: 0,
            },
            helpers: [],
            timer: null,
            line: {
                countProbs: 3,
                currentProb: 0,
                startDelay: 1000,
                showHelpers: false,
                crntHelper: {},
                startHelp: {
                    min: 1000,
                    max: 1000
                },
                waitQuestion: {
                    min: 1000,
                    max: 1000
                },
                stopDelay: {
                    min: 1000,
                    max: 1000
                },
            }
        }
    },
    getters: {
        getActive(state){return state.active}
    },
    actions: {
        setActive(val){
            this.active = val
        },
        async runExperiment() {

            while (this.line.currentProb < this.line.countProbs) {
                this.line.crntHelper = this.helpers.at(getRandom(0, this.helpers.length - 1))
                this.position = this.positions.at(getRandom(0, this.positions.length - 1))
                this.setActive(true)
                this.comment += "<p>Сигнал</p>"
                let t = new SuperTimer()
                await t.timeout(() => {
                    this.comment += "<p>Сигнал ждем</p>"
                }, this.line.startDelay)
                this.comment += "<p>стимул</p>"
                t = new SuperTimer()
                await t.timeout(() => {
                    this.line.showHelpers = true
                    this.comment += "<p>Пауза перед подсказкой</p>"
                }, getRandom(this.line.startHelp.min, this.line.startHelp.max))
                this.comment += "<p>стимул</p>"
                this.timer = new SuperTimer()

                await (async () => {
                    try {
                        await this.timer.timeout(() => {
                            this.line.showHelpers = false
                            this.comment += "<p>Пауза перед подсказкой(нажала)</p>"
                        }, getRandom(this.line.waitQuestion.min, this.line.waitQuestion.max))
                    } catch (e) {
                        this.line.showHelpers = false
                    }
                })()
                t = new SuperTimer()
                this.comment += "<p>Пауза</p>"
                await t.timeout(() => {
                    this.comment += "<p>следующая</p>"
                }, getRandom(this.line.stopDelay.min, this.line.stopDelay.max))
                this.line.currentProb++
            }
            this.setActive(false)
        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
