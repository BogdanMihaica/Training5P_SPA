import axios from 'axios';
import { defineStore } from 'pinia'
import Swal from 'sweetalert2';

export const useAuthStore = defineStore('auth',
	{
		state: ()=>({
			isAuthenticated: false,
			isInitialized: false
		}),

		actions: {
			/**
			 * Requests the server for the login of the user and updates the stored variables accordingly
			 * 
			 * @param {Object} userParam 
			 * @param {string} token 
			 */
			async login(email, password){
				const body = {
					email: email,
					password: password
				};

				await axios.get('/sanctum/csrf-cookie');

				await axios.post(`/spa/login`, body).then((res) => {
					Swal.fire({
						title: "Success",
						text: "Successfully logged in!",
						icon: "success"
					});

					this.isAuthenticated = true;
					localStorage.setItem('token', res.data.token);
					
				}).catch(error => {
					Swal.fire({
						title: "Ugh...",
						text: error.response.data.message,
						icon: "error"
					});
					console.log(error.response);
				});
			},

			/**
			 * Logs the user out
			 */
			async logout(){
				await axios.get('/sanctum/csrf-cookie');

				await axios.post(`/spa/logout`).then(()=>{
					this.isAuthenticated = false;
					localStorage.removeItem('token');
				});
			},

			/**
			 * Initializes the store
			 */
			async initialize() {
				if(!this.isInitialized)
				{
					await axios.get(`/spa/user`).then(() => {
						this.isAuthenticated = true;
					}) .catch(() => {
						this.isAuthenticated = false;
					});
					this.isInitialized=true;
				}
			}
		},
	}
)