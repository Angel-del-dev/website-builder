export const Request = async ({
    url,
    data = {},
    method = 'POST'
}) => {
    const params = {
        method,
        headers: {
            Accept: 'application/json'
        }
    };

    if(['POST'].includes(method.toUpperCase())) {
        params.headers['Content-type'] = 'application/x-www-form-urlencoded';
        params.body =  new URLSearchParams(data);
    }
    return await fetch(url, params)
        .then(r =>  r.ok || r.status === 400 ? r.json() : { message: r.statusText })
        .then(r => r)
        .catch(err => {return { message: err }});
};