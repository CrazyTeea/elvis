import {defineStore} from "pinia";
import {getRandom, SuperTimer, toNumber} from "../mixins/utils.js";
import axios from "axios";
import svgMixin from "@mixins/svgMixin.js";


const {triangle} = svgMixin()
const audioCtx = new (window.AudioContext || window.webkitAudioContext || window.audioContext);
export const useExperimentStore = defineStore('experiment1', {
    state: () => {
        return {
            active: false,
            is_window: false,
            data: {
                figures: [],
                figure_results: [],
                figure: {},
                oblast: {},
                timer: null
            },
            result: {},
            line: {
                maxCount: 2,
                current: 0,
                startDelay: 1000,
                showRange: {min: 3000, max: 5000},
                stopRange: {min: 1000, max: 5000}
            },
            id: null,
            monkey_id: null,
            showFigure: false,
            text: '',
            timer: null,
            pause1: false,
            p: null,
            pause2: false,
            click: {x: 0, y: 0},
            react: false
        }
    },

    getters: {
        getActive: (state) => state.active,
        getData: (state) => state.data,
        getResult: (state) => state.result,
        getLine: (state) => state.line,
        getTrueValue(state) {
            return state.data.figure_results?.filter(item => !!item.reaction_time && item.reaction_time !== -1).length
        },
        getReactionTime(state) {
            return toNumber(state.data.figure_results?.reduce((a, item) => a + toNumber(item.reaction_time), 0) / state.data.figure_results?.length)
        },

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
        updateFigure(figure, values, addResults = true) {
            this.data.figure = {
                ...this.data.figure,
                ...values
            }
            let i = this.data.figures.findIndex((element) => element.id === figure.id)
            if (i !== -1) {
                this.data.figures[i] = {
                    ...this.data.figures[i],
                    ...values
                }
                if (addResults) {
                    this.data.figure_results.push({
                        figure_id: figure.id,
                        experiment_id: this.data.figures.at(i).experiment_id,
                        ...values
                    })
                }
            }
        },
        async startStopPause() {
            this.pause1 = !this.pause1
        },
        setClick(x, y) {
            this.click = {x, y}
        },
        updateClickPosition(figure) {
            let i = this.data.figure_results.findIndex(element =>
                element.figure_id === figure.id
                && element.experiment_id === figure.experiment_id
                && element.x === figure.x
                && element.y === figure.y);
            if (i !== -1) {
                this.data.figure_results[i] = {
                    ...this.data.figure_results[i],
                    x_click: localStorage.getItem('x'),
                    y_click: localStorage.getItem('y')
                }
            }
        },

        async sleep() {
            if (this.pause1) {
                const date = Date.now();
                let currentDate = null;
                do {
                    currentDate = Date.now();
                    await (new SuperTimer()).sleep(1000)
                    this.text += '<p>пауза от пользователя </p>'
                    if (!this.pause1) {
                        break;
                    }
                } while (currentDate - date < 999999);
            }

        },

        reset(oblast) {
            let {monkey_id, line, is_window} = this
            this.line.current = 0
            this.$reset();
            this.monkey_id = monkey_id
            this.is_window = is_window
            this.setData({
                oblast: oblast.value.options
            })
            this.line = line
        },

        //duration of the tone in milliseconds. Default is 500
//frequency of the tone in hertz. default is 440
//volume of the tone. Default is 1, off is 0.
//type of tone. Possible values are sine, square, sawtooth, triangle, and custom. Default is sine.
//callback to use on end of tone
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

        async runExperiment(figure) {
            this.data.figures = await figure.fetchFigures()
            this.data.figure_results = []

            this.setActive(true)


            while (this.line.current < this.line.maxCount) {
                await this.sleep()
                this.data.figure = this.data.figures.at(getRandom(0, this.data.figures.length - 1))
                let x = this.data.figure.xx.at(getRandom(0, this.data.figure.xx.length - 1))
                let y = this.data.figure.yy.at(getRandom(0, this.data.figure.yy.length - 1))
                let w = this.data.figure.ww.at(getRandom(0, this.data.figure.ww.length - 1))
                let h = this.data.figure.hh.at(getRandom(0, this.data.figure.hh.length - 1))
                let color = this.data.figure.colors.at(getRandom(0, this.data.figure.colors.length - 1))
                let brightness = getRandom(this.data.figure.brightness_min, this.data.figure.brightness_max)
                this.updateFigure(this.data.figure, {x, y, w, h, color, brightness}, false)
                this.line.current++
                this.text += '<p>Ждем сигнал </p>'
                this.beep();
                let ap = new SuperTimer();
                await ap.timeout(() => {
                    this.showFigure = true
                    this.text += '<p>Ждем фигуру </p>'
                }, this.line.startDelay);
                await this.sleep()
                this.text += '<p>Показываем фигуру </p>'
                this.timer = new SuperTimer();

                let time = (new Date()).getTime()

                await (async () => {
                    let params = {
                        x: this.data.figure.x,
                        y: this.data.figure.y,
                        w: this.data.figure.h,
                        h: this.data.figure.w,
                        color,
                        x_oblast: this.data.oblast.position.x1,
                        y_oblast: this.data.oblast.position.y1
                    }
                    try {
                        await this.sleep()
                        await this.timer.timeout(() => {
                            this.showFigure = false
                            this.updateFigure(this.data.figure, {
                                reaction_time: -1,
                                ...params
                            })
                            this.text += '<p>Отключили фигуру </p>'
                        }, getRandom(this.line.showRange.min, this.line.showRange.max))
                    } catch (e) {
                        await this.sleep()
                        this.showFigure = false
                        let t = (new Date()).getTime() - time
                        this.updateFigure(this.data.figure, {
                            reaction_time: localStorage.getItem('react') === 'true' ? t : -1,
                            ...params
                        })
                    }
                })()
                this.updateClickPosition(this.data.figure)
                await this.sleep()
                this.text += '<p>пауза </p>'
                ap = new SuperTimer({h: this.line.stopRange.min, h1: this.line.stopRange.max})

                await ap.timeout(() => {

                    this.showFigure = false
                }, getRandom(this.line.stopRange.min, this.line.stopRange.max))

            }
            figure.setFigureResults(this.data.figure_results)
            figure.store();
            await axios.post(`/files/add/${this.monkey_id}`)
            this.setActive(false)
        },
        stopTimer() {
            this.timer.stop()
        },
        async storeExperiment() {
            let data = await axios.post('/experiment/store', {
                monkey_id: this.monkey_id,
                number: 1,
                name: 'Рефлекс на стимул',
                br_min: this.data.oblast.brightness.min,
                br_max: this.data.oblast.brightness.min,
                x1: this.data.oblast.position.x1,
                x2: this.data.oblast.position.x2,
                y1: this.data.oblast.position.y1,
                y2: this.data.oblast.position.y2,
            })
            return data.data
        },
        generateTriangle() {
            const {w, color} = this.data.figure;
            return triangle(w, color)
        },
        getFigurePosition() {
            let style = '';
            if (!this.active) {
                return style
            }

            try {
                let figure = this.data.figure
                let {color, w: width, h, angles, brightness, x, y} = figure

                switch (figure.name) {
                    case 'ellipse': {
                        style = `background-color: ${color};filter: brightness(${brightness}%);width: ${width}px; height: ${width}px; left: ${x}px; top: ${y}px; border-radius:100%`;
                        break;
                    }
                    case 'polygon': {
                        style = `width: ${width}px;filter: brightness(${brightness}%); height: ${width}px; left: ${x}px; top: ${y}px; `;
                        break;
                    }
                    case 'rectangle3': {
                        let angle = angles.at(getRandom(0, angles.length - 1))
                        style = `background-color: ${color};filter: brightness(${brightness}%);width: ${width}px; height: ${h}px; left: ${x}px; top: ${y}px; transform:rotate(${angle}deg)`;
                        break;
                    }
                    case 'rectangle2': {
                        style = `background-color: ${color};filter: brightness(${brightness}%);width: ${width}px; height: ${h}px; left: ${x}px; top: ${y}px; `;
                        break;
                    }

                    case 'rectangle': {
                        style = `background-color: ${color};filter: brightness(${brightness}%);width: ${width}px; height: ${width}px; left: ${x}px; top: ${y}px; `;
                        break;
                    }

                }
            } catch (e) {
            }
            return style

        },
    },
    share: {
        enable: true,
        initialize: true,
    }
})
