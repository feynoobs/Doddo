import axios from 'axios';

const http = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
})

export default http