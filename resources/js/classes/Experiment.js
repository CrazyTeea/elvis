import axios from "axios";

export class Experiment {
    constructor(experiment, helpers, positions, stimul, line) {
        this.helpers = helpers
        this.positions = positions
        this.stimul = stimul
        this.experiment = experiment
        this.line=line
        this.id = null
    }
    async storeExperiment(){
        let data = await axios.post('/experiment/store', {
            'experiment': this.experiment,
            'helpers': this.helpers,
            'stimul': this.stimul,
            'positions': this.positions,
            'line':this.line
        })
        this.id = data.data['experiment']['id'];
    }
}
