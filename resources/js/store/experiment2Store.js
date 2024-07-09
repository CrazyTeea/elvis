import {defineStore} from "pinia";
import {getRandom, SuperTimer, toNumber} from "@mixins/utils.js";
import axios from "axios";
import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";
import {reactive, ref} from "vue";
import audioFile from '@assets/clicker.m4a'

const audioCtx = new (window.AudioContext || window.webkitAudioContext || window.audioContext);

export const useExperiment2Store = defineStore('experiment2', {
    state: () => {
        return {
            active: false,
            comment: '',
            position: '',
            stimul: null,
            monkey_id: null,
            experiment_id: null,
            data: {
                stimuls: [],
                helpers: [],
                positions: [],
            },
            positions: [],
            timer: null,
            timer2: null,
            timer3: null,
            is_window: false,
            results: [],
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
            return state.active
        },
        getTrueValue: (state) => {
            return state.results.filter(item => !!item.reaction && item.reaction !== -1).length
        },
        getReactionTime: (state) => {
            return toNumber(state.results.reduce((a, item) => a + toNumber(item.reaction), 0) / state.results.length)
        }
    },
    actions: {
        setActive(val) {
            this.active = val
        },
        setExperimentId(val) {
            this.experiment_id = val
        },
        reset() {
            let line = this.line
            let win = this.is_window
            line.currentProb = 0;
            this.$reset()
            this.is_window = win === true
            this.line = {...line}
        },

        stopTimer() {
            this.timer?.stop()
            this.timer2?.stop()
            this.timer3?.stop()
        },

        sendStimul() {
            axios.post(`/experiment/send-com`, {...this.stimul, position: this.position}).catch(() => {
            })
        },

        beep(duration, frequency, volume, type, callback) {
            let oscillator = audioCtx.createOscillator();
            let gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            if (volume) {
                gainNode.gain.value = volume;
            }
            if (frequency) {
                oscillator.frequency.value = frequency;
            }
            if (type) {
                oscillator.type = type;
            }
            if (callback) {
                oscillator.onended = callback;
            }

            oscillator.start(audioCtx.currentTime);
            oscillator.stop(audioCtx.currentTime + ((duration || 500) / 1000));
        },

        async getExperimentData() {

            let columns =
                '    position_strings\n' +
                '    monkey_id\n' +
                '    id\n' +
                'positions {\n' +
                'name\n' +
                'id\n' +
                'experiment_id }\n' +
                '    helpers {\n' +
                '      thickness\n' +
                '      name\n' +
                '      id\n' +
                '      experiment_id\n' +
                '      br\n' +
                '      offset\n' +
                '    }\n' +
                '    stimuls {\n' +
                '      name\n' +
                '      length\n' +
                '      id\n' +
                '      frequency\n' +
                '      experiment_id\n' +
                '    }'
            let response = await GraphqlAPI.get_api('experiment', {id: this.experiment_id}, columns)
            this.data.helpers = response.helpers
            this.data.positions = response.position_strings
            this.positions = response.positions
            this.data.stimuls = response.stimuls
        },
        async storeResults() {
            await axios.post('/experiment/store-exp2-results', {results: this.results})
        },
        async storeExperiment() {
            let data = await axios.post('/experiment/store', {
                'experiment': {
                    monkey_id: this.monkey_id,
                    number: 2,
                    name: 'Рефлекс на стимул',
                },
                'helpers': this.helpers,
                'stimul': this.stimul,
                'positions': this.positions
            })
            this.experiment_id = data.data['experiment']['id'];
            return data.data
        },
        async runExperiment() {
            await this.getExperimentData()

            this.setActive(true)
            while (this.line.currentProb < this.line.countProbs) {
                this.line.crntHelper = this.data.helpers.at(getRandom(0, this.data.helpers.length - 1))
                this.position = this.data.positions.at(getRandom(0, this.data.positions.length - 1))
                this.stimul = this.data.stimuls.at(getRandom(0, this.data.stimuls.length - 1))
                this.line.currentProb++
                this.comment += "<p>Сигнал</p>"
                this.beep();
                let time = (new Date()).getTime()
                let reaction = -1
                this.timer = new SuperTimer()
                await (async () => {
                    try {
                        this.timer = new SuperTimer()
                        await this.timer.timeout(() => {
                            this.comment += "<p>Сигнал ждем</p>"
                            this.sendStimul()
                        }, this.line.startDelay)

                        this.comment += "<p>стимул</p>"
                        this.line.showHelpers = true
                        this.timer2 = new SuperTimer()
                        await this.timer2.timeout(() => {
                            this.comment += "<p>Пауза перед подсказкой</p>"
                        }, (+this.stimul.length) + getRandom(this.line.startHelp.min, this.line.startHelp.max))

                        this.comment += "<p>подсказка</p>"
                        this.timer3 = new SuperTimer()
                        await this.timer.timeout(() => {
                            this.line.showHelpers = false
                            this.comment += "<p>Пауза перед подсказкой</p>"
                        }, getRandom(this.line.waitQuestion.min, this.line.waitQuestion.max))


                    } catch (e) {
                        let t = (new Date()).getTime() - time

                        reaction = localStorage.getItem('react') === 'true' ? t : -1
                        if (localStorage.getItem('react') === 'true') {
                            //this.beep(500, 500)
                            const audio = new Audio(audioFile);
                            await audio.play();
                            axios.post('/experiment/send-com', {name: 'feed',}).catch(e => console.info(e))
                        }

                        this.line.showHelpers = false
                    }
                })()


                let t = new SuperTimer()
                this.comment += "<p>Пауза</p>"
                await t.timeout(() => {
                    this.comment += "<p>следующая</p>"
                }, getRandom(this.line.stopDelay.min, this.line.stopDelay.max))
                this.results.push({
                    experiment_id: this.experiment_id,
                    stimul_id: this.stimul.id,
                    position_id: (this.positions.find(item => item.name === this.position)).id,
                    helper_id: this.line.crntHelper.id,
                    x: localStorage.getItem('x_clk'),
                    y: localStorage.getItem('y_clk'),
                    reaction
                })

            }

            await this.storeResults()
            await axios.post(`/files/add/2/${this.monkey_id}`)
            this.setActive(false)

        }
    },
    share: {
        enable: true,
        initialize: true,
    }
})
