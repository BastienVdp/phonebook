import axios from "axios"

const axiosClient = axios.create({
    baseURL: `http://phonebook.test/`,
})

axiosClient.interceptors.request.use(
	config => {
		const token = localStorage.getItem('token')
		if (token) {
			config.headers['Authorization'] = `Bearer ${token}`
		}
		return config
	},
	error => {
		Promise.reject(error)
	}
)

axiosClient.interceptors.response.use(response => response, error => {
	if(error.response?.status === 401) {
        localStorage.clear()
		console.log(error);
        // window.location.href = '/login'
        return error
    }
    throw error
})

export default axiosClient