const currentApiVersion = 'v2'
const currentHost = 'localhost'
const port = '8000'
const apiPrefix = 'api'
const protocol = 'http://'

function getServerEndpoint() {
    return `${protocol}${currentHost}:${port}/${apiPrefix}/${currentApiVersion}`
}

export default {
    getServerEndpoint,
}