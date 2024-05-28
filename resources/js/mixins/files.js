import {GraphqlAPI} from "@/store/api/GraphqlAPI.js";

async function getFiles(number, monkey_id) {
    let fileQuery = new GraphqlAPI('files')
    return await fileQuery.fetch({
        monkey_id: monkey_id,
        where: {
            value: number,
            operator: 'EQ',
            column: 'NUMBER'
        }
    }, [
        'id', 'name'
    ])
}
async function deleteFile(id) {
    await axios.post(`/files/delete/${id}`)
    await getFiles()
}

function downloadFile(id) {
    window.open(`/files/download/${id}`)
}

async function generateFile(monkey_id) {
    await axios.post(`/files/add/${monkey_id}`)
    await getFiles()
}
const fileHeaders = [
    {title: 'name', key: 'name'},
    {title: ':-)', key: 'actions'},
]

export {
    fileHeaders,
    getFiles,
    generateFile,
    deleteFile,
    downloadFile
}
