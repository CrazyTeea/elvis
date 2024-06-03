import {defineStore} from "pinia";
import {getRandom, SuperTimer} from "@mixins/utils.js";
import axios from "axios";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";
import {reactive, ref} from "vue";


export const useExperiment2Store = defineStore('experiment2', {
    state: () => {
        return {
            active: false,
            zopa: false,
            comment: '',
            position: '',
            monkey_id: null,
            experiment_id: null,
            data: {
                stimul: {
                    name: '',
                    frequency: 0,
                    length: 0,
                },
                helpers: [],
                positions: [],
            },

            timer: null,
            is_window: false,
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
        getActive: (state) => {
            return state.zopa
        }
    },
    actions: {
        setActive(val) {
            this.zopa = val
        },
        setExperimentId(val) {
            this.experiment_id = val
        },
        reset() {
            let line = this.line
            let win = this.is_window
            line.currentProb = 0;
            this.$reset()
            this.is_window = win
            this.line = {...line}
        },

        async getExperimentData() {

            let columns =
                '    position_strings\n' +
                '    monkey_id\n' +
                '    id\n' +
                '    helpers {\n' +
                '      thickness\n' +
                '      name\n' +
                '      id\n' +
                '      experiment_id\n' +
                '      br\n' +
                '    }\n' +
                '    stimul {\n' +
                '      name\n' +
                '      length\n' +
                '      id\n' +
                '      frequency\n' +
                '      experiment_id\n' +
                '    }'
            let response = await GraphqlAPI.get_api('experiment', {id: this.experiment_id}, columns)
            this.data.helpers = response.helpers
            this.data.positions = response.position_strings
            this.data.stimul = response.stimul
        },
        async runExperiment() {
            await this.getExperimentData()

            this.setActive(true)
            while (this.line.currentProb < this.line.countProbs) {
                this.line.crntHelper = this.data.helpers.at(getRandom(0, this.data.helpers.length - 1))
                this.position = this.data.positions.at(getRandom(0, this.data.positions.length - 1))
                this.line.currentProb++
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

            }
            this.setActive(false)

        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
