class GraphqlAPI {
    constructor(api_name) {
        this.url = '/graphql'
        this.data = {}
        this.apiName = api_name
    }

    getQueryString(query) {
        let ret = ''
        for (let key in query) {
            if (typeof query[key] === 'object' && query[key] !== null) {
                ret += (key + ':{' + this.getQueryString(query[key]) + '},')
            } else {
                ret += `${key}:${query[key]},`
            }
        }
        return ret;
    }

    /**
     *
     * @param query
     * @param columnsToFetch
     * @returns {Promise<{}|[]>}
     */
    async fetch(query, columnsToFetch) {

        let name = query ? `${this.apiName}(${this.getQueryString(query)})` : this.apiName

        let q = {
            operationName: 'MyQuery',
            query: `query MyQuery {${name}{${columnsToFetch.join(',')}}}`
        }

        let data = await axios.post(this.url, q);

        if (data.data.hasOwnProperty('errors') || data.status !== 200) {
            console.error(data)
            return {}
        }


        return data.data.data[this.apiName]
    }

}

export {GraphqlAPI}
