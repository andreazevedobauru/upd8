const baseURL = 'http://localhost:8000/'

const apiRoutes = {
    client: {
        create: baseURL + 'api/clients',
        get: baseURL + 'api/clients',
        update: baseURL + 'api/clients/',
        delete: baseURL + 'api/clients/',
    },
    sellers: {
        create: baseURL + 'api/sellers',
        get: baseURL + 'api/sellers',
        update: baseURL + 'api/sellers/',
        delete: baseURL + 'api/sellers/',
        byClient: baseURL + 'api/sellers-by-client/',
        byCity: baseURL + 'api/sellers-by-city/'
    },
    cities: {
        create: baseURL + 'api/cities',
        get: baseURL + 'api/cities',
        update: baseURL + 'api/cities/',
        delete: baseURL + 'api/cities/',
    },
    countries: {
        get: baseURL + 'api/countries',
    },
    states: {
        get: baseURL + 'api/states',
    },
}

export default apiRoutes;
