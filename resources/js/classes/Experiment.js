import axios from "axios";

export class Experiment {
    constructor(experiment, helpers, positions, stimul) {
        this.helpers = helpers
        this.positions = positions
        this.stimul = stimul
        this.experiment = experiment
        this.id = null
    }
    async storeExperiment(){
        let data = await axios.post('/experiment/store', {
            'experiment': this.experiment,
            'helpers': this.helpers,
            'stimul': this.stimul,
            'positions': this.positions
        })
        this.id = data.data['experiment']['id'];
    }
}
